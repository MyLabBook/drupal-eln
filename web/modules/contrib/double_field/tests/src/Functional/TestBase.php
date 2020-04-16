<?php

namespace Drupal\Tests\double_field\Functional;

use Behat\Mink\Element\NodeElement;
use Drupal\Component\Utility\NestedArray;
use Drupal\Tests\BrowserTestBase;
use Drupal\double_field\Plugin\Field\FieldFormatter\Base as BaseFormatter;
use Drupal\double_field\Plugin\Field\FieldWidget\DoubleField;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;

/**
 * Tests the creation of text fields.
 */
abstract class TestBase extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * A field name used for testing.
   *
   * @var string
   */
  protected $fieldName;

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
   * A path to field storage settings form.
   *
   * @var string
   */
  protected $fieldStorageAdminPath;

  /**
   * Field storage instance.
   *
   * @var \Drupal\field\FieldStorageConfigInterface
   */
  protected $fieldStorage;

  /**
   * Field instance.
   *
   * @var \Drupal\Core\Field\FieldConfigInterface
   */
  protected $field;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'double_field',
    'node',
    'field_ui',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->fieldName = strtolower($this->randomMachineName());
    $this->contentTypeId = $this->drupalCreateContentType(['type' => $this->randomMachineName()])->id();
    $this->fieldAdminPath = "admin/structure/types/manage/{$this->contentTypeId}/fields/node.{$this->contentTypeId}.{$this->fieldName}";
    $this->fieldStorageAdminPath = $this->fieldAdminPath . '/storage';

    $permissions = [
      'administer content types',
      'administer node fields',
      'administer nodes',
      'administer node form display',
      'administer node display',
      "edit any {$this->contentTypeId} content",
      "delete any {$this->contentTypeId} content",
    ];
    $admin_user = $this->drupalCreateUser($permissions, NULL, NULL, ['timezone' => 'Europe/Moscow']);
    $this->drupalLogin($admin_user);

    // Create a field storage for testing.
    $storage_settings['storage'] = [
      'first' => [
        'type' => 'string',
        'maxlength' => 50,
        'precision' => 10,
        'scale' => 2,
        'datetime_type' => 'datetime',
      ],
      'second' => [
        'type' => 'string',
        'maxlength' => 50,
        'precision' => 10,
        'scale' => 2,
        'datetime_type' => 'datetime',
      ],
    ];

    $this->fieldStorage = FieldStorageConfig::create([
      'field_name' => $this->fieldName,
      'entity_type' => 'node',
      'type' => 'double_field',
      'settings' => $storage_settings,
    ]);
    $this->fieldStorage->save();

    // Create a field storage for testing.
    $this->field = FieldConfig::create([
      'field_storage' => $this->fieldStorage,
      'bundle' => $this->contentTypeId,
      'required' => TRUE,
    ]);
    $this->field->save();

    $this->saveWidgetSettings([]);
    $this->saveFormatterSettings('unformatted_list');

  }

  /**
   * Saves field settings.
   */
  protected function saveFieldSettings(array $settings): void {
    $persisted_settings = $this->field->getSettings();
    // Override allowed values instead of merging.
    foreach (['first', 'second'] as $subfield) {
      if (isset($persisted_settings[$subfield]['allowed_values'], $settings[$subfield]['allowed_values'])) {
        unset($persisted_settings[$subfield]['allowed_values']);
      }
    }
    $this->field->setSettings(
      NestedArray::mergeDeep($persisted_settings, $settings)
    );
    $this->field->save();
  }

  /**
   * Saves storage settings.
   */
  protected function saveFieldStorageSettings(array $settings): void {
    $this->fieldStorage->setSettings(
      NestedArray::mergeDeep($this->fieldStorage->getSettings(), $settings)
    );
    $this->fieldStorage->save();
  }

  /**
   * Saves widget settings.
   */
  protected function saveWidgetSettings(array $settings): void {
    /** @var \Drupal\Core\Entity\Entity\EntityFormDisplay $form_display */
    $form_display = \Drupal::entityTypeManager()
      ->getStorage('entity_form_display')
      ->load('node.' . $this->contentTypeId . '.default');

    $options = [
      'type' => 'double_field',
      'weight' => 100,
      'settings' => NestedArray::mergeDeep(DoubleField::defaultSettings(), $settings),
      'third_party_settings' => [],
    ];

    $form_display->setComponent($this->fieldName, $options);
    $form_display->save();
  }

  /**
   * Saves formatter settings.
   */
  protected function saveFormatterSettings(string $formatter, array $settings = []): void {

    /** @var \Drupal\Core\Entity\Entity\EntityViewDisplay $view_display */
    $view_display = \Drupal::entityTypeManager()
      ->getStorage('entity_view_display')
      ->load("node.{$this->contentTypeId}.default");

    $options = [
      'label' => 'hidden',
      'type' => 'double_field_' . $formatter,
      'weight' => 100,
      'settings' => NestedArray::mergeDeep(BaseFormatter::defaultSettings(), $settings),
      'third_party_settings' => [],
    ];

    $view_display->setComponent($this->fieldName, $options);
    $view_display->save();
  }

  /**
   * Checks that an element exists on the current page.
   *
   * @param string|array $xpath
   *   The XPath identifying the element to check.
   */
  protected function assertXpath($xpath): void {
    if (is_array($xpath)) {
      $xpath = implode($xpath);
    }
    $this->assertSession()->elementExists('xpath', $xpath);
  }

  /**
   * Checks that an element does not exist on the current page.
   *
   * @param string|array $xpath
   *   The XPath identifying the element to check.
   */
  protected function assertNoXpath($xpath): void {
    if (is_array($xpath)) {
      $xpath = implode($xpath);
    }
    $this->assertSession()->elementNotExists('xpath', $xpath);
  }

  /**
   * Finds Drupal messages on the page.
   *
   * @param string $type
   *   A message type (e.g. status, warning, error).
   *
   * @return array
   *   List of found messages.
   */
  protected function getMessages($type): array {
    $messages = [];
    $get_message = function (NodeElement $element) :string {
      // Remove hidden heading.
      $message = preg_replace('#<h2[^>]*>.*</h2>#', '', $element->getHtml());
      $message = strip_tags($message, '<em>');
      return trim(preg_replace('#\s+#', ' ', $message));
    };
    $xpath = sprintf('//div[@aria-label = "%s message"]', ucfirst($type));
    // Error messages have one more wrapper.
    if ($type == 'error') {
      $xpath .= '/div[@role = "alert"]';
    }
    $wrapper = $this->xpath($xpath);
    if (!empty($wrapper[0])) {
      unset($wrapper[0]->h2);
      $items = $wrapper[0]->findAll('xpath', '/ul/li');
      // Multiple messages are rendered with an HTML list.
      if ($items) {
        foreach ($items as $item) {
          $messages[] = $get_message($item);
        }
      }
      else {
        $messages[] = $get_message($wrapper[0]);
      }
    }
    return $messages;
  }

  /**
   * Passes if a given status message was found on the page.
   */
  protected function assertStatusMessage(string $message): void {
    $messages = $this->getMessages('status');
    self::assertTrue(in_array($message, $messages), 'Status message was found.');
  }

  /**
   * Passes if a given warning message was found on the page.
   */
  protected function assertWarningMessage(string $message): void {
    $messages = $this->getMessages('warning');
    self::assertTrue(in_array($message, $messages), 'Warning message was found.');
  }

  /**
   * Passes if a given error message was found on the page.
   */
  protected function assertErrorMessage(string $message): void {
    $messages = $this->getMessages('error');
    self::assertTrue(in_array($message, $messages), 'Error message was found.');
  }

  /**
   * Passes if all expected violations were found.
   */
  protected function assertViolations(array $values, array $expected_messages): void {
    $node = Node::create(['type' => $this->contentTypeId]);
    $node->{$this->fieldName} = [
      'first' => $values[0],
      'second' => $values[1],
    ];

    /** @var \Symfony\Component\Validator\ConstraintViolationInterface[] $violations */
    $violations = $node->{$this->fieldName}->validate();

    foreach ($violations as $index => $violation) {
      $message = strip_tags($violations[$index]->getMessage());
      $key = array_search($message, $expected_messages);
      self::assertNotFalse($key, sprintf('Found violation: "%s".', $message));
    }

    self::assertEquals(count($violations), count($expected_messages));
  }

  /**
   * Passes if no violations were found.
   */
  protected function assertNoViolations(array $values): void {
    $this->assertViolations($values, []);
  }

}
