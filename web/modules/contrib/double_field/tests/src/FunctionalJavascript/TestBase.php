<?php

namespace Drupal\Tests\double_field\FunctionalJavascript;

use Drupal\Component\Utility\NestedArray;
use Drupal\double_field\Plugin\Field\FieldFormatter\Base as BaseFormatter;
use Drupal\double_field\Plugin\Field\FieldType\DoubleField;
use Drupal\double_field\Plugin\Field\FieldWidget\DoubleField as DoubleFieldWidget;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Base class for Double Field JavaScript tests.
 */
abstract class TestBase extends WebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'double_field',
    'node',
    'field_ui',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'classy';

  /**
   * A user with relevant administrative privileges.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * The field name.
   *
   * @var string
   */
  protected $fieldName;

  /**
   * XPath prefix of an element in settings form.
   *
   * @var string
   */
  protected $fieldPrefix;

  /**
   * A content type id.
   *
   * @var string
   */
  protected $contentTypeId;

  /**
   * A path to field settings form.
   *
   * @var string
   */
  protected $fieldAdminPath;

  /**
   * A path to form display settings form.
   *
   * @var string
   */
  protected $formDisplayAdminPath;

  /**
   * A path to display settings form.
   *
   * @var string
   */
  protected $displayAdminPath;

  /**
   * A path to field storage settings form.
   *
   * @var string
   */
  protected $fieldStorageAdminPath;

  /**
   * A path to content type settings form.
   *
   * @var string
   */
  protected $contentTypeAdminPath;

  /**
   * A path to node add form.
   *
   * @var string
   */
  protected $nodeAddPath;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->fieldName = strtolower($this->randomMachineName());
    $this->fieldPrefix = "fields[{$this->fieldName}][settings_edit_form][settings]";
    $this->contentTypeId = $this->drupalCreateContentType(['type' => $this->randomMachineName()])->id();
    $this->contentTypeAdminPath = 'admin/structure/types/manage/' . $this->contentTypeId;
    $this->fieldAdminPath = "{$this->contentTypeAdminPath}/fields/node.{$this->contentTypeId}.{$this->fieldName}}";
    $this->fieldStorageAdminPath = $this->fieldAdminPath . '/storage';
    $this->formDisplayAdminPath = $this->contentTypeAdminPath . '/form-display';
    $this->displayAdminPath = $this->contentTypeAdminPath . '/display';
    $this->nodeAddPath = 'node/add/' . $this->contentTypeId;

    $this->adminUser = $this->drupalCreateUser([
      'administer content types',
      'administer node fields',
      'administer nodes',
      'administer node form display',
      'administer node display',
      "edit any {$this->contentTypeId} content",
      "delete any {$this->contentTypeId} content",
    ]);
    $this->drupalLogin($this->adminUser);
  }

  /**
   * {@inheritdoc}
   */
  protected function drupalPostForm($path, $edit, $submit, array $options = [], $form_html_id = NULL) {
    $new_edit = [];
    foreach ($edit as $name => $value) {
      $new_edit[str_replace('{field_name}', $this->fieldName, $name)] = $value;
    }
    parent::drupalPostForm($path, $new_edit, $submit, $options, $form_html_id);
  }

  /**
   * Creates a field.
   */
  protected function createField(array $settings): void {

    if ($field_storage_config = FieldStorageConfig::loadByName('node', $this->fieldName)) {
      $field_storage_config->delete();
    }

    // -- Build settings arrays acceptable by field API.
    $cardinality = $settings['storage']['cardinality'] ?? 1;
    unset($settings['storage']['cardinality']);

    $widget_settings = $settings['widget'] ?? [];
    unset($settings['widget']);

    $widget_type = $widget_settings['type'] ?? NULL;
    unset($widget_type['type']);

    $formatter_settings = $settings['formatter'] ?? [];
    unset($settings['formatter']);

    $formatter_type = $formatter_settings['type'] ?? NULL;
    unset($formatter_settings['type']);

    $field_storage = FieldStorageConfig::create([
      'field_name' => $this->fieldName,
      'entity_type' => 'node',
      'type' => 'double_field',
      'cardinality' => $cardinality,
      'settings' => NestedArray::mergeDeep(DoubleField::defaultStorageSettings(), $settings),
    ]);
    $field_storage->save();

    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => $this->contentTypeId,
      'required' => TRUE,
      'settings' => NestedArray::mergeDeep(DoubleField::defaultFieldSettings(), $settings),
    ]);
    $field->save();

    $form_display = \Drupal::entityTypeManager()
      ->getStorage('entity_form_display')
      ->load('node.' . $this->contentTypeId . '.default');

    $options = [
      'type' => $widget_type,
      'weight' => 100,
      'settings' => NestedArray::mergeDeep(DoubleFieldWidget::defaultSettings(), $widget_settings),
      'third_party_settings' => [],
    ];

    $form_display->setComponent($this->fieldName, $options);
    $form_display->save();

    $view_display = \Drupal::entityTypeManager()
      ->getStorage('entity_view_display')
      ->load("node.{$this->contentTypeId}.default");

    $options = [
      'label' => 'hidden',
      'type' => $formatter_type,
      'weight' => 100,
      'settings' => NestedArray::mergeDeep(BaseFormatter::defaultSettings(), $formatter_settings),
      'third_party_settings' => [],
    ];

    $view_display->setComponent($this->fieldName, $options);
    $view_display->save();
  }

  /**
   * Opens formatter form.
   */
  protected function openSettingsForm(): void {
    $this->getSession()->getPage()->pressButton($this->fieldName . '_settings_edit');
    $this->assertSession()->assertWaitOnAjaxRequest();
  }

  /**
   * Submits formatter form.
   */
  protected function submitSettingsForm(): void {
    $page = $this->getSession()->getPage();
    $page->pressButton('Update');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $page->pressButton('Save');
    $this->assertXpath('//div[contains(., "Your settings have been saved.")]');
  }

  /**
   * Assets formatter summary.
   */
  protected function assertSettingsSummary(array $expected_summary): void {
    $xpath = sprintf(
      '//tr[@id = "%s"]/td[@class = "field-plugin-summary-cell"]/div[@class = "field-plugin-summary"]',
      str_replace('_', '-', $this->fieldName)
    );
    $actual_summary = explode('<br>', $this->xpath($xpath)[0]->getHtml());
    self::assertEquals($expected_summary, $actual_summary);
  }

  /**
   * Checks that an element exists on the current page.
   */
  protected function assertXpath(string $xpath): void {
    $xpath = str_replace('{field_name}', $this->fieldName, $xpath);
    $this->assertSession()->elementExists('xpath', $xpath);
  }

  /**
   * Checks that an element does not exist on the current page.
   */
  protected function assertNoXpath(string $xpath): void {
    $xpath = str_replace('{field_name}', $this->fieldName, $xpath);
    $this->assertSession()->elementNotExists('xpath', $xpath);
  }

}
