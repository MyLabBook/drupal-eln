<?php

namespace Drupal\Tests\double_field\Functional;

use Drupal\Component\Render\FormattableMarkup;

/**
 * A test for Double Field type.
 *
 * @group double_field
 */
final class FieldTypeTest extends TestBase {

  /**
   * Test field storage settings.
   */
  public function testFieldStorageSettings(): void {

    // Not random to avoid different plural form in messages.
    $maxlength = 50;

    // -- Boolean and varchar.
    $storage_settings['storage']['first']['type'] = 'boolean';
    $storage_settings['storage']['second']['type'] = 'string';
    $storage_settings['storage']['second']['maxlength'] = $maxlength;
    $this->saveFieldStorageSettings($storage_settings);

    $values = [
      // The valid boolean value is 0 or 1.
      2,
      $this->randomString($maxlength + 1),
    ];
    $expected_messages = [
      'This value should be of the correct primitive type.',
      new FormattableMarkup('This value is too long. It should have @maxlength characters or less.', ['@maxlength' => $maxlength]),
    ];
    $this->assertViolations($values, $expected_messages);

    // Set the field optional as zero value will cause 'No blank' violation.
    $settings['first']['required'] = FALSE;
    $this->saveFieldSettings($settings);

    $values = [
      mt_rand(0, 1),
      $this->randomString($maxlength),
    ];
    $this->assertNoViolations($values);

    // -- Text (long) and integer.
    $storage_settings['storage']['first']['type'] = 'text';
    $storage_settings['storage']['second']['type'] = 'integer';
    $this->saveFieldStorageSettings($storage_settings);

    $values = [
      // Text storage has no restrictions.
      $this->randomString(1000),
      // Float value should not be accepted.
      123.456,
    ];
    $expected_messages = [
      'This value should be of the correct primitive type.',
    ];
    $this->assertViolations($values, $expected_messages);

    $values = [
      $this->randomString(1000),
      mt_rand(0, 1000),
    ];
    $this->assertNoViolations($values);

    // -- Float and numeric.
    $storage_settings['storage']['first']['type'] = 'float';
    $storage_settings['storage']['second']['type'] = 'numeric';
    $this->saveFieldStorageSettings($storage_settings);

    $values = [
      'abc',
      'abc',
    ];
    $expected_messages = [
      'This value should be of the correct primitive type.',
      'This value should be of the correct primitive type.',
    ];
    $this->assertViolations($values, $expected_messages);

    $values = [
      mt_rand(0, 1000) + mt_rand(),
      mt_rand(0, 1000) + mt_rand(),
    ];
    $this->assertNoViolations($values);

    // -- Email and URI.
    $storage_settings['storage']['first']['type'] = 'email';
    $storage_settings['storage']['second']['type'] = 'uri';
    $this->saveFieldStorageSettings($storage_settings);

    $values = [
      'abc',
      'abc',
    ];
    $expected_messages = [
      'This value is not a valid email address.',
      'This value should be of the correct primitive type.',
    ];
    $this->assertViolations($values, $expected_messages);

    $values = [
      'qwe@rty.ui',
      'http://example.com',
    ];
    $this->assertNoViolations($values);

    // -- Datetime.
    $storage_settings['storage']['first']['type'] = 'datetime_iso8601';
    $storage_settings['storage']['second']['type'] = 'string';
    $this->saveFieldStorageSettings($storage_settings);

    $values = [
      'abc',
      'abc',
    ];
    $expected_messages = [
      'This value should be of the correct primitive type.',
    ];
    $this->assertViolations($values, $expected_messages);

    $values = [
      '2017-10-10T01:00:00',
      'abc',
    ];
    $this->assertNoViolations($values);
  }

  /**
   * Test field storage settings form.
   */
  public function testFieldStorageSettingsForm(): void {
    $this->drupalGet($this->fieldStorageAdminPath);

    foreach (['first', 'second'] as $subfield) {

      $prefix = sprintf('//form//details[@id = "edit-settings-storage-%s"]', $subfield);

      $select_prefix = $prefix . "//select[@name = 'settings[storage][$subfield][type]' and count(option) = 10]";
      $this->assertXpath($select_prefix . '/option[@value = "boolean" and text() = "Boolean"]');
      $this->assertXpath($select_prefix . '/option[@value = "string" and text() = "Text"]');
      $this->assertXpath($select_prefix . '/option[@value = "text" and text() = "Text (long)"]');
      $this->assertXpath($select_prefix . '/option[@value = "integer" and text() = "Integer"]');
      $this->assertXpath($select_prefix . '/option[@value = "float" and text() = "Float"]');
      $this->assertXpath($select_prefix . '/option[@value = "numeric" and text() = "Decimal"]');
      $this->assertXpath($select_prefix . '/option[@value = "email" and text() = "Email"]');
      $this->assertXpath($select_prefix . '/option[@value = "telephone" and text() = "Telephone"]');
      $this->assertXpath($select_prefix . '/option[@value = "datetime_iso8601" and text() = "Date"]');
      $this->assertXpath($select_prefix . '/option[@value = "uri" and text() = "Url"]');

      $this->assertXpath($prefix . "//input[@name = 'settings[storage][$subfield][maxlength]' and @value = 50]");
      $this->assertXpath($prefix . "//input[@name = 'settings[storage][$subfield][precision]' and @value = 10]");
      $this->assertXpath($prefix . "//input[@name = 'settings[storage][$subfield][scale]' and @value = 2]");

      $datetime_xpath = '//input[@value = "datetime"]/following-sibling::label[text() = "Date and time"]';
      $date_xpath = '//input[@value = "date"]/following-sibling::label[text() = "Date only"]';
      $this->assertXpath(sprintf('//fieldset/legend[span[text() = "Date type"]]/following-sibling::div[%s and %s]', $datetime_xpath, $date_xpath));
    }

    // Submit some example settings and check if they are accepted.
    $edit = [
      'settings[storage][first][type]' => 'string',
      'settings[storage][first][maxlength]' => 15,
      'settings[storage][second][type]' => 'numeric',
      'settings[storage][second][precision]' => 30,
      'settings[storage][second][scale]' => 5,
    ];
    $this->drupalPostForm($this->fieldStorageAdminPath, $edit, 'Save field settings');

    $message = new FormattableMarkup('Updated field %field_name field settings.', ['%field_name' => $this->fieldName]);
    $this->assertStatusMessage($message);
    $this->assertWarningMessage('Since storage type has been changed you need to verify configuration of related widget on manage form display page.');

    $this->drupalGet($this->fieldStorageAdminPath);

    $this->assertXpath('//select[@name = "settings[storage][first][type]"]/option[@selected = "selected" and @value = "string"]');
    $this->assertXpath('//input[@name = "settings[storage][first][maxlength]" and @value = 15]');
    $this->assertXpath('//select[@name = "settings[storage][second][type]"]/option[@selected = "selected" and @value = "numeric"]');
    $this->assertXpath('//input[@name = "settings[storage][second][precision]" and @value = 30]');
    $this->assertXpath('//input[@name = "settings[storage][second][scale]" and @value = 5]');
  }

  /**
   * Test field settings.
   */
  public function testFieldSettings(): void {

    // -- Boolean and string.
    $storage_settings['storage']['first']['type'] = 'boolean';
    $storage_settings['storage']['second']['type'] = 'string';
    $this->saveFieldStorageSettings($storage_settings);

    $settings['second']['list'] = TRUE;
    $settings['second']['allowed_values'] = [
      'aaa' => 'Aaa',
      'bbb' => 'Bbb',
      'ccc' => 'Ccc',
    ];
    $this->saveFieldSettings($settings);

    $expected_messages = [
      'The value you selected is not a valid choice.',
    ];
    $this->assertViolations([1, 'abc'], $expected_messages);

    $values = [
      // Boolean has no field level settings that may cause violations.
      1,
      array_rand($settings['second']['allowed_values']),
    ];
    $this->assertNoViolations($values);

    // -- Integer.
    $storage_settings['storage']['first']['type'] = 'integer';
    $storage_settings['storage']['second']['type'] = 'integer';
    $this->saveFieldStorageSettings($storage_settings);

    $min_limit = mt_rand(-1000, 1000);
    $max_limit = mt_rand($min_limit, $min_limit + 1000);
    foreach (['first', 'second'] as $subfield) {
      $settings[$subfield]['list'] = FALSE;
      $settings[$subfield]['min'] = $min_limit;
      $settings[$subfield]['max'] = $max_limit;
    }
    $this->saveFieldSettings($settings);

    $values = [
      $min_limit - 1,
      $max_limit + 1,
    ];
    $expected_messages = [
      new FormattableMarkup('This value should be @min_limit or more.', ['@min_limit' => $min_limit]),
      new FormattableMarkup('This value should be @max_limit or less.', ['@max_limit' => $max_limit]),
    ];
    $this->assertViolations($values, $expected_messages);

    $values = [
      mt_rand($min_limit, $max_limit),
      mt_rand($min_limit, $max_limit),
    ];
    $this->assertNoViolations($values);

    // -- Float and numeric.
    $storage_settings['storage']['first']['type'] = 'float';
    $storage_settings['storage']['second']['type'] = 'numeric';
    $this->saveFieldStorageSettings($storage_settings);

    $min_limit = mt_rand(-1000, 1000);
    $max_limit = mt_rand($min_limit, $min_limit + 1000);
    $settings = $this->field->getSettings();
    foreach (['first', 'second'] as $subfield) {
      $settings[$subfield]['list'] = FALSE;
      $settings[$subfield]['min'] = $min_limit;
      $settings[$subfield]['max'] = $max_limit;
    }
    $this->saveFieldSettings($settings);

    $values = [
      $min_limit - mt_rand(1, 100),
      $max_limit + mt_rand(1, 100),
    ];
    $expected_messages = [
      new FormattableMarkup('This value should be @min_limit or more.', ['@min_limit' => $min_limit]),
      new FormattableMarkup('This value should be @max_limit or less.', ['@max_limit' => $max_limit]),
    ];
    $this->assertViolations($values, $expected_messages);

    // Test allowed values.
    $settings['first']['list'] = TRUE;
    $settings['first']['allowed_values'] = [
      '-12.379' => 'Aaa',
      '4565' => 'Bbb',
      '93577285' => 'Ccc',
    ];
    $settings['second']['list'] = TRUE;
    $settings['second']['allowed_values'] = [
      '-245' => 'Aaa',
      'sssssss' => 'Bbb',
      '7738854' => 'Ccc',
    ];
    $settings['second']['max'] = $max_limit;
    $this->saveFieldSettings($settings);

    $values = [
      123.356,
      300.12,
    ];
    $expected_messages = [
      'The value you selected is not a valid choice.',
      'The value you selected is not a valid choice.',
    ];
    $this->assertViolations($values, $expected_messages);
    $this->assertNoViolations([4565, 7738854]);

    // -- Email and telephone.
    $storage_settings['storage']['first']['type'] = 'email';
    $storage_settings['storage']['second']['type'] = 'telephone';
    foreach (['first', 'second'] as $subfield) {
      $settings[$subfield]['list'] = FALSE;
    }
    $this->saveFieldSettings($settings);
    $this->saveFieldStorageSettings($storage_settings);

    $values = [
      'aaa',
      str_repeat('x', 51),
    ];
    $expected_messages = [
      'This value is not a valid email address.',
      'This value is too long. It should have 50 characters or less.',
    ];
    $this->assertViolations($values, $expected_messages);

    $values = [
      'abc@example.com',
      str_repeat('x', 50),
    ];
    $this->assertNoViolations($values);

    // -- Uri and date.
    $storage_settings['storage']['first']['type'] = 'uri';
    $storage_settings['storage']['second']['type'] = 'datetime_iso8601';
    foreach (['first', 'second'] as $subfield) {
      $settings[$subfield]['list'] = FALSE;
    }
    $this->saveFieldSettings($settings);
    $this->saveFieldStorageSettings($storage_settings);

    $values = [
      'aaa',
      'bbb',
    ];
    $expected_messages = [
      'This value should be of the correct primitive type.',
      'This value should be of the correct primitive type.',
    ];
    $this->assertViolations($values, $expected_messages);

    $values = [
      'http://example.com',
      '2016-10-11T01:12:14',
    ];
    $this->assertNoViolations($values);
  }

  /**
   * Test field settings form.
   */
  public function testFieldSettingsForm(): void {

    // -- Boolean and string.
    $storage_settings['storage']['first']['type'] = 'boolean';
    $storage_settings['storage']['second']['type'] = 'string';
    $this->saveFieldStorageSettings($storage_settings);
    $this->drupalGet($this->fieldAdminPath);

    $this->assertXpath('//details[@id = "edit-settings-first"]/summary[text() = "First subfield - Boolean"]');
    $this->assertXpath('//input[@name = "settings[first][label]"]');
    $this->assertXpath('//input[@name = "settings[first][required]" and @checked = "checked"]');
    $this->assertNoXpath('//input[@name = "settings[first][list]"]');
    $this->assertXpath('//input[@name = "settings[first][on_label]" and @value = "On"]');
    $this->assertXpath('//input[@name = "settings[first][off_label]" and @value = "Off"]');
    $this->assertXpath('//details[@id = "edit-settings-second"]/summary[text() = "Second subfield - Text"]');
    $this->assertXpath('//input[@name = "settings[second][label]"]');
    $this->assertXpath('//input[@name = "settings[second][required]" and @checked = "checked"]');
    $this->assertXpath('//input[@name = "settings[second][list]" and not(@checked)]');
    $this->assertXpath('//textarea[@name = "settings[second][allowed_values]"]');

    // -- Text and email.
    $storage_settings['storage']['first']['type'] = 'text';
    $storage_settings['storage']['second']['type'] = 'email';

    // SQLite database gets locked here by some reason.
    usleep(2500);
    $this->saveFieldStorageSettings($storage_settings);
    $this->drupalGet($this->fieldAdminPath);

    $this->assertXpath('//details[@id = "edit-settings-first"]/summary[text() = "First subfield - Text (long)"]');
    $this->assertXpath('//input[@name = "settings[first][label]"]');
    $this->assertXpath('//input[@name = "settings[first][required]" and @checked = "checked"]');
    $this->assertNoXpath('//input[@name = "settings[first][list]"]');
    $this->assertNoXpath('//textarea[@name = "settings[first][allowed_values]"]');
    $this->assertXpath('//details[@id = "edit-settings-second"]/summary[text() = "Second subfield - Email"]');
    $this->assertXpath('//input[@name = "settings[second][label]"]');
    $this->assertXpath('//input[@name = "settings[second][required]" and @checked = "checked"]');
    $this->assertXpath('//input[@name = "settings[second][list]" and not(@checked)]');
    $this->assertXpath('//textarea[@name = "settings[second][allowed_values]"]');

    // -- Telephone and URL.
    $storage_settings['storage']['first']['type'] = 'telephone';
    $storage_settings['storage']['second']['type'] = 'uri';
    $this->saveFieldStorageSettings($storage_settings);
    $this->drupalGet($this->fieldAdminPath);

    $this->assertXpath('//details[@id = "edit-settings-first"]/summary[text() = "First subfield - Telephone"]');
    $this->assertXpath('//input[@name = "settings[first][label]"]');
    $this->assertXpath('//input[@name = "settings[first][required]" and @checked = "checked"]');
    $this->assertXpath('//input[@name = "settings[first][list]" and not(@checked)]');
    $this->assertXpath('//textarea[@name = "settings[first][allowed_values]"]');
    $this->assertXpath('//details[@id = "edit-settings-second"]/summary[text() = "Second subfield - Url"]');
    $this->assertXpath('//input[@name = "settings[second][label]"]');
    $this->assertXpath('//input[@name = "settings[second][required]" and @checked = "checked"]');
    $this->assertXpath('//input[@name = "settings[second][list]" and not(@checked)]');
    $this->assertXpath('//textarea[@name = "settings[second][allowed_values]"]');

    // -- Date and integer.
    $storage_settings['storage']['first']['type'] = 'datetime_iso8601';
    $storage_settings['storage']['second']['type'] = 'integer';
    $this->saveFieldStorageSettings($storage_settings);
    $this->drupalGet($this->fieldAdminPath);

    $this->assertXpath('//details[@id = "edit-settings-first"]/summary[text() = "First subfield - Date"]');
    $this->assertXpath('//input[@name = "settings[first][label]"]');
    $this->assertXpath('//input[@name = "settings[first][required]" and @checked = "checked"]');
    $this->assertXpath('//input[@name = "settings[first][list]" and not(@checked)]');
    $this->assertXpath('//textarea[@name = "settings[first][allowed_values]"]');
    $this->assertXpath('//details[@id = "edit-settings-second"]/summary[text() = "Second subfield - Integer"]');
    $this->assertXpath('//input[@name = "settings[second][label]"]');
    $this->assertXpath('//input[@name = "settings[second][required]" and @checked = "checked"]');
    $this->assertXpath('//input[@name = "settings[second][list]" and not(@checked)]');
    $this->assertXpath('//textarea[@name = "settings[second][allowed_values]"]');
    $this->assertXpath('//input[@name = "settings[second][min]" and @type = "number"]');
    $this->assertXpath('//input[@name = "settings[second][max]" and @type = "number"]');

    // -- Float and decimal.
    $storage_settings['storage']['first']['type'] = 'float';
    $storage_settings['storage']['second']['type'] = 'numeric';
    $this->saveFieldStorageSettings($storage_settings);
    $this->drupalGet($this->fieldAdminPath);

    $this->assertXpath('//details[@id = "edit-settings-first"]/summary[text() = "First subfield - Float"]');
    $this->assertXpath('//input[@name = "settings[first][label]"]');
    $this->assertXpath('//input[@name = "settings[first][required]" and @checked = "checked"]');
    $this->assertXpath('//input[@name = "settings[first][list]" and not(@checked)]');
    $this->assertXpath('//textarea[@name = "settings[first][allowed_values]"]');
    $this->assertXpath('//input[@name = "settings[first][min]" and @type = "number"]');
    $this->assertXpath('//input[@name = "settings[first][max]" and @type = "number"]');
    $this->assertXpath('//details[@id = "edit-settings-second"]/summary[text() = "Second subfield - Decimal"]');
    $this->assertXpath('//input[@name = "settings[second][label]"]');
    $this->assertXpath('//input[@name = "settings[second][required]" and @checked = "checked"]');
    $this->assertXpath('//input[@name = "settings[second][list]" and not(@checked)]');
    $this->assertXpath('//textarea[@name = "settings[second][allowed_values]"]');
    $this->assertXpath('//input[@name = "settings[second][min]" and @type = "number"]');
    $this->assertXpath('//input[@name = "settings[second][max]" and @type = "number"]');

    // Submit some example settings and check whether they are accepted.
    $edit = [
      'settings[first][label]' => 'First',
      'settings[first][list]' => 1,
      'settings[first][allowed_values]' => '123|Aaa',
      'settings[second][label]' => 'Second',
      'settings[second][min]' => 10,
      'settings[second][max]' => 20,
    ];

    $this->drupalPostForm(NULL, $edit, 'Save settings');
    $this->drupalGet($this->fieldAdminPath);

    $this->assertXpath('//input[@name = "settings[first][label]" and @value = "First"]');
    $this->assertXpath('//input[@name = "settings[first][list]" and @checked = "checked"]');
    $this->assertXpath('//textarea[@name = "settings[first][allowed_values]" and text() = "123|Aaa"]');
    $this->assertXpath('//input[@name = "settings[second][label]" and @value = "Second"]');
    $this->assertXpath('//input[@name = "settings[second][min]" and @value = 10]');
    $this->assertXpath('//input[@name = "settings[second][max]" and @value = 20]');
  }

  /**
   * Test allowed values validation.
   */
  public function testAllowedValuesValidation(): void {

    // --
    $maxlength = 50;
    $storage_settings['storage']['first']['type'] = 'string';
    $storage_settings['storage']['first']['maxlength'] = $maxlength;
    $storage_settings['storage']['second']['type'] = 'float';
    $this->saveFieldStorageSettings($storage_settings);

    $edit = [
      'settings[first][list]' => 1,
      // Random sting may content '|' character.
      'settings[first][allowed_values]' => str_repeat('a', $maxlength + 1),
      'settings[second][list]' => 1,
      'settings[second][allowed_values]' => implode("\n", [123, 'abc', 789]),
    ];
    $this->drupalPostForm($this->fieldAdminPath, $edit, 'Save settings');

    $this->assertErrorMessage(new FormattableMarkup('Allowed values list: each key must be a string at most @maxlength characters long.', ['@maxlength' => $maxlength]));
    $this->assertErrorMessage('Allowed values list: each key must be a valid integer or decimal.');

    $edit = [
      'settings[first][allowed_values]' => str_repeat('a', $maxlength),
      'settings[second][allowed_values]' => implode("\n", [123, 456, 789]),
    ];
    $this->drupalPostForm($this->fieldAdminPath, $edit, 'Save settings');
    self::assertCount(0, $this->getMessages('error'), 'No error messages were found.');
    $this->assertStatusMessage(new FormattableMarkup('Saved %field_name configuration.', ['%field_name' => $this->fieldName]));

    // --
    $storage_settings['storage']['first']['type'] = 'integer';
    $storage_settings['storage']['second']['type'] = 'numeric';
    $this->saveFieldStorageSettings($storage_settings);

    $edit = [
      'settings[first][allowed_values]' => implode("\n", [123, 'abc', 789]),
      'settings[second][allowed_values]' => implode("\n", [123, 'abc', 789]),
    ];
    $this->drupalPostForm($this->fieldAdminPath, $edit, 'Save settings');
    $this->assertErrorMessage('Allowed values list: keys must be integers.');
    $this->assertErrorMessage('Allowed values list: each key must be a valid integer or decimal.');

    $edit = [
      'settings[first][allowed_values]' => implode("\n", [123, 456, 789]),
      'settings[second][allowed_values]' => implode("\n", [123, 456, 789]),
    ];
    $this->drupalPostForm($this->fieldAdminPath, $edit, 'Save settings');
    self::assertCount(0, $this->getMessages('error'), 'No error messages were found.');
    $this->assertStatusMessage(new FormattableMarkup('Saved %field_name configuration.', ['%field_name' => $this->fieldName]));
  }

  /**
   * Test required options.
   */
  public function testRequiredOptions(): void {
    $storage_settings['storage']['first']['type'] = 'integer';
    $storage_settings['storage']['second']['type'] = 'boolean';
    $this->saveFieldStorageSettings($storage_settings);

    $this->assertViolations([NULL, 1], ['This value should not be blank.']);

    // Zero should be treated as not empty value.
    $this->assertNoViolations([0, 1]);

    $settings['first']['required'] = FALSE;
    $this->saveFieldSettings($settings);
    $this->assertNoViolations([NULL, 1]);

    // For boolean field zero is an empty value.
    $this->assertViolations([123, 0], ['This value should not be blank.']);

    $settings['second']['required'] = FALSE;
    $this->saveFieldSettings($settings);
    $this->assertNoViolations([123, 0]);
  }

}
