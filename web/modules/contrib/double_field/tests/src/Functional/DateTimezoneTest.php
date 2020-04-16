<?php

namespace Drupal\Tests\double_field\Functional;

use Drupal\Component\Utility\NestedArray;
use Drupal\double_field\Plugin\Field\FieldFormatter\Base as BaseFormatter;

/**
 * A test for date timezone calculations.
 *
 * @group double_field
 */
final class DateTimezoneTest extends TestBase {

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $storage_settings['storage']['first']['type'] = 'datetime_iso8601';
    $storage_settings['storage']['first']['datetime_type'] = 'datetime';
    $storage_settings['storage']['second']['type'] = 'datetime_iso8601';
    $storage_settings['storage']['second']['datetime_type'] = 'date';

    $this->saveFieldStorageSettings($storage_settings);

    $view_display = \Drupal::entityTypeManager()
      ->getStorage('entity_view_display')
      ->load("node.{$this->contentTypeId}.default");

    $settings['first']['format_type'] = 'html_datetime';
    $settings['second']['format_type'] = 'html_datetime';
    $options = [
      'label' => 'hidden',
      'type' => 'double_field_unformatted_list',
      'settings' => NestedArray::mergeDeep(BaseFormatter::defaultSettings(), $settings),
      'third_party_settings' => [],
    ];
    $view_display->setComponent($this->fieldName, $options);
    $view_display->save();
  }

  /**
   * Test callback.
   */
  public function testDateSubfield(): void {

    $edit = [
      'title[0][value]' => 'Example',
      $this->fieldName . '[0][first][date]' => '2020-01-18',
      $this->fieldName . '[0][first][time]' => '03:12:00',
      $this->fieldName . '[0][second][date]' => '2020-01-15',
    ];
    $this->drupalPostForm('node/add/' . $this->contentTypeId, $edit, 'Save');

    // -- Test formatter.
    $xpath = [
      '//div[@class = "double-field-first"]',
      '/time[@datetime = "2020-01-18T03:12:00Z" and text() = "2020-01-18T03:12:00+0300"]',
    ];
    $this->assertXpath($xpath);

    $xpath = [
      '//div[@class = "double-field-second"]',
      '/time[@datetime = "2020-01-15T15:00:00Z" and text() = "2020-01-15T12:00:00+0000"]',
    ];
    $this->assertXpath($xpath);

    $this->drupalGet('node/1/edit');

    // -- Test widget.
    $xpath = [
      '//div[contains(@class, "double-field-elements")]',
      sprintf('/div[@id = "edit-%s-0-first" and position() = 1]', $this->fieldName),
      '/div[position() = 1]/input[@type = "date" and @value = "2020-01-18"]',
      '/../..',
      '/div[position() = 2]/input[@type = "time" and @value = "03:12:00"]',
    ];
    $this->assertXpath($xpath);

    $xpath = [
      '//div[contains(@class, "double-field-elements")]',
      sprintf('/div[@id = "edit-%s-0-second" and position() = 2]', $this->fieldName),
      '/div[position() = 1]/input[@type = "date" and @value = "2020-01-15"]',
    ];
    $this->assertXpath($xpath);

    // Date only field should not have time element.
    $xpath = [
      '//div[contains(@class, "double-field-elements")]',
      sprintf('/div[@id = "edit-%s-0-second" and position() = 2]', $this->fieldName),
      '//input[@type = "time"]',
    ];
    $this->assertNoXpath($xpath);
  }

}
