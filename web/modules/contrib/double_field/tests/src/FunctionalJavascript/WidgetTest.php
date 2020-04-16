<?php

namespace Drupal\Tests\double_field\FunctionalJavascript;

/**
 * A tests for Double Field widget.
 *
 * @group double_field
 */
final class WidgetTest extends TestBase {

  /**
   * Test callback.
   */
  public function testWidgetForm(): void {

    // -- Boolean and string.
    $settings = [];
    $settings['storage']['first']['type'] = 'boolean';
    $settings['storage']['second']['type'] = 'string';
    $settings['first']['required'] = FALSE;
    $settings['widget']['first']['type'] = 'checkbox';
    $settings['widget']['first']['label'] = 'Foo';
    $settings['widget']['second']['type'] = 'textfield';
    $settings['widget']['second']['size'] = 25;
    $settings['widget']['second']['placeholder'] = 'Bar';
    $this->createField($settings);

    $this->drupalGet($this->nodeAddPath);

    $this->assertXpath('//input[@type = "checkbox" and @name = "{field_name}[0][first]"]/following-sibling::label[text() = "Foo"]');
    $this->assertXpath('//input[@name = "{field_name}[0][second]" and @type = "text" and @size = "25" and @value = "" and @placeholder = "Bar"]');

    $edit = [
      'title[0][value]' => $this->randomMachineName(),
      '{field_name}[0][first]' => 1,
      '{field_name}[0][second]' => 'abc',
    ];
    $this->drupalPostForm($this->nodeAddPath, $edit, 'Save');

    $xpath = '//div';
    $xpath .= '/div[@class = "double-field-first" and normalize-space() = "On"][1]';
    $xpath .= '/following-sibling::div[@class = "double-field-second" and normalize-space() = "abc"][1]';
    $this->assertXpath($xpath);

    // -- Text and integer.
    $settings = [];
    $settings['storage']['first']['type'] = 'text';
    $settings['storage']['second']['type'] = 'integer';
    $settings['second']['min'] = -1000;
    $settings['second']['max'] = 1000;
    $settings['widget']['first']['type'] = 'textarea';
    $settings['widget']['first']['cols'] = 15;
    $settings['widget']['first']['rows'] = 20;
    $settings['widget']['first']['placeholder'] = 'zoom';
    $settings['widget']['second']['type'] = 'number';
    $this->createField($settings);

    $this->drupalGet($this->nodeAddPath);
    $this->assertXpath('//textarea[@name = "{field_name}[0][first]" and @cols = "15" and @rows = "20" and @placeholder = "zoom"]');
    $this->assertXpath('//input[@type = "number" and @name = "{field_name}[0][second]"]');

    $edit = [
      'title[0][value]' => $this->randomMachineName(),
      '{field_name}[0][first]' => 'AbCdEf',
      '{field_name}[0][second]' => 135,
    ];
    $this->drupalPostForm($this->nodeAddPath, $edit, 'Save');

    $xpath = '//div';
    $xpath .= '/div[@class = "double-field-first" and normalize-space() = "AbCdEf"][1]';
    $xpath .= '/following-sibling::div[@class = "double-field-second" and normalize-space() = "135"][1]';
    $this->assertXpath($xpath);

    // -- String (color) and integer (range).
    $settings = [];
    $settings['storage']['first']['type'] = 'string';
    $settings['storage']['second']['type'] = 'integer';
    $settings['second']['min'] = -500;
    $settings['second']['max'] = 150;
    $settings['widget']['first']['type'] = 'color';
    $settings['widget']['second']['type'] = 'range';
    $this->createField($settings);

    $this->drupalGet($this->nodeAddPath);
    $this->assertXpath('//input[@type = "color" and @name = "{field_name}[0][first]"]');
    $this->assertXpath('//input[@type = "range" and @name = "{field_name}[0][second]" and @min = "-500" and @max = "150"]');

    // Do not submit the for as it is tricky for these widgets.

    // -- Float and numeric.
    $settings = [];
    $settings['storage']['first']['type'] = 'float';
    $settings['storage']['second']['type'] = 'numeric';
    $settings['first']['min'] = -10;
    $settings['first']['max'] = 10;
    $settings['second']['min'] = -105;
    $settings['second']['max'] = 105;
    $settings['widget']['first']['type'] = 'number';
    $settings['widget']['second']['type'] = 'textfield';
    $settings['widget']['second']['size'] = 15;
    $settings['widget']['second']['placeholder'] = 'bear';
    $this->createField($settings);

    $this->drupalGet($this->nodeAddPath);
    $this->assertXpath('//input[@type = "number" and @name = "{field_name}[0][first]" and @min = "-10" and @max = "10"]');
    $this->assertXpath('//input[@type = "text" and @name = "{field_name}[0][second]" and @size = "15" and @placeholder = "bear"]');

    $edit = [
      'title[0][value]' => $this->randomMachineName(),
      '{field_name}[0][first]' => 5,
      '{field_name}[0][second]' => 33,
    ];
    $this->drupalPostForm($this->nodeAddPath, $edit, 'Save');

    $xpath = '//div';
    $xpath .= '/div[@class = "double-field-first" and normalize-space() = "5.00"][1]';
    $xpath .= '/following-sibling::div[@class = "double-field-second" and normalize-space() = "33.00"][1]';
    $this->assertXpath($xpath);

    // -- Email and telephone.
    $settings = [];
    $settings['storage']['first']['type'] = 'email';
    $settings['storage']['second']['type'] = 'telephone';
    $settings['widget']['first']['type'] = 'email';
    $settings['widget']['first']['size'] = 30;
    $settings['widget']['first']['placeholder'] = 'example@localhost';
    $settings['widget']['second']['type'] = 'tel';
    $settings['widget']['second']['size'] = 35;
    $settings['widget']['second']['placeholder'] = '+79876554321';
    $this->createField($settings);

    $this->drupalGet($this->nodeAddPath);

    $this->assertXpath('//input[@type = "email" and @name = "{field_name}[0][first]" and @size = "30" and @placeholder = "example@localhost"]');
    $this->assertXpath('//input[@type = "tel" and @name = "{field_name}[0][second]" and @size = "35" and @placeholder = "+79876554321"]');

    $edit = [
      'title[0][value]' => $this->randomMachineName(),
      '{field_name}[0][first]' => 'admin@drupal.org',
      '{field_name}[0][second]' => '12345',
    ];
    $this->drupalPostForm($this->nodeAddPath, $edit, 'Save');

    $xpath = '//div';
    $xpath .= '/div[@class = "double-field-first" and normalize-space() = "admin@drupal.org"][1]';
    $xpath .= '/following-sibling::div[@class = "double-field-second" and normalize-space() = "12345"][1]';
    $this->assertXpath($xpath);

    // -- Url and Date.
    $settings = [];
    $settings['storage']['first']['type'] = 'uri';
    $settings['storage']['second']['type'] = 'datetime_iso8601';
    $settings['widget']['first']['type'] = 'url';
    $settings['widget']['first']['size'] = 28;
    $settings['widget']['first']['placeholder'] = 'https://www.drupal.org';
    $settings['widget']['second']['type'] = 'datetime';
    $this->createField($settings);

    $this->drupalGet($this->nodeAddPath);
    $this->assertXpath('//input[@type = "url" and @name = "{field_name}[0][first]" and @size = "28" and @placeholder = "https://www.drupal.org"]');
    $this->assertXpath('//input[@type = "date" and @name = "{field_name}[0][second][date]"]');
    $this->assertXpath('//input[@type = "time" and @name = "{field_name}[0][second][time]"]');

    $edit = [
      'title[0][value]' => $this->randomMachineName(),
      '{field_name}[0][first]' => 'https://github.com',
      '{field_name}[0][second][date]' => '12345',
      '{field_name}[0][second][time]' => '033005PM',
    ];
    $this->drupalPostForm($this->nodeAddPath, $edit, 'Save');

    $xpath = '//div';
    $xpath .= '/div[@class = "double-field-first" and normalize-space() = "https://github.com"][1]';
    $xpath .= '/following-sibling::div[@class = "double-field-second"][1]/time[@datetime = "0005-12-31T15:30:05Z" and normalize-space() = "Sat, 12/31/0005 - 15:30"]';
    $this->assertXpath($xpath);

    // -- Check prefixes and suffixes.
    $this->drupalGet($this->formDisplayAdminPath);
    $this->openSettingsForm();
    $page = $this->getSession()->getPage();
    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->fillField($this->fieldPrefix . '[first][prefix]', '[prefix-first]');
    $details_1->fillField($this->fieldPrefix . '[first][suffix]', '[suffix-first]');
    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->fillField($this->fieldPrefix . '[second][prefix]', '[prefix-second]');
    $details_2->fillField($this->fieldPrefix . '[second][suffix]', '[suffix-second]');
    $this->submitSettingsForm();

    $this->drupalGet($this->nodeAddPath);

    $xpath = '//div[contains(@class, "double-field-elements") and normalize-space() = "[prefix-first] [suffix-first][prefix-second] Date Time [suffix-second]"]';
    $this->assertXpath($xpath);

    // -- Check label display.
    $settings = [];
    $settings['storage']['first']['type'] = 'integer';
    $settings['storage']['second']['type'] = 'telephone';
    $settings['widget']['first']['type'] = 'number';
    $settings['widget']['first']['label_display'] = 'block';
    $settings['widget']['second']['type'] = 'tel';
    $settings['widget']['second']['label_display'] = 'inline';
    $this->createField($settings);

    $this->drupalGet($this->nodeAddPath);
    $this->assertXpath('//div[contains(@class, "form-item-{field_name}-0-first") and not(label)]');
    $this->assertXpath('//div[contains(@class, "form-item-{field_name}-0-second") and not(label)]');

    $settings['first']['label'] = 'First';
    $settings['second']['label'] = 'Second';
    $this->createField($settings);

    $this->drupalGet($this->nodeAddPath);
    $this->assertXpath('//div[contains(@class, "form-item-{field_name}-0-first") and not(contains(@class, "container-inline"))]/label');
    $this->assertXpath('//div[contains(@class, "form-item-{field_name}-0-second") and contains(@class, "container-inline")]/label');

    $settings['widget']['first']['label_display'] = 'invisible';
    $settings['widget']['second']['label_display'] = 'hidden';

    $this->createField($settings);

    $this->drupalGet($this->nodeAddPath);
    $this->assertXpath('//div[contains(@class, "form-item-{field_name}-0-first")]/label[@class = "visually-hidden"]');
    $this->assertXpath('//div[contains(@class, "form-item-{field_name}-0-second") and not(label)]');
  }

  /**
   * Test callback.
   */
  public function testWidgetSettingsForm(): void {

    $page = $this->getSession()->getPage();

    // -- Boolean and string.
    $settings = [];
    $settings['storage']['first']['type'] = 'boolean';
    $settings['storage']['second']['type'] = 'string';
    $settings['widget']['first']['type'] = 'boolean';
    $settings['widget']['second']['type'] = 'textfield';
    $settings['second']['label'] = 'Second';
    $this->createField($settings);

    $this->drupalGet($this->formDisplayAdminPath);
    $this->openSettingsForm();

    $this->assertXpath('//input[@type = "checkbox" and @name = "fields[{field_name}][settings_edit_form][settings][inline]"]');

    $this->assertXpath('//td//details[1]/summary[text() = "First subfield - Boolean"]');
    $this->assertXpath('//td//details[1]//select[@name = "fields[{field_name}][settings_edit_form][settings][first][type]"]/option[@value = "checkbox" and text() = "Checkbox" and @selected = "selected"]');
    $this->assertNoXpath('//select[@name = "fields[{field_name}][settings_edit_form][settings][first][label_display]"]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][label]" and @value = "Ok"]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][prefix]" and @value = ""]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][suffix]" and @value = ""]');

    $this->assertXpath('//td//details[2]/summary[text() = "Second subfield - Text"]');
    $this->assertXpath('//td//details[2]//select[@name = "fields[{field_name}][settings_edit_form][settings][second][type]"]/option[@value = "textfield" and text() = "Textfield" and @selected = "selected"]');
    $this->assertXpath('//td//details[2]//select[@name = "fields[{field_name}][settings_edit_form][settings][second][label_display]"]/option[@value = "block" and text() = "Block" and @selected = "selected"]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][size]" and @type = "number" and @value = "10"]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][prefix]" and @value = ""]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][suffix]" and @value = ""]');

    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->fillField($this->fieldPrefix . '[first][label]', 'Yes');
    $details_1->fillField($this->fieldPrefix . '[first][prefix]', 'pfx-1');
    $details_1->fillField($this->fieldPrefix . '[first][suffix]', 'sfx-1');
    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->selectFieldOption($this->fieldPrefix . '[second][type]', 'email');
    $details_2->selectFieldOption($this->fieldPrefix . '[second][label_display]', 'inline');
    $details_2->fillField($this->fieldPrefix . '[second][size]', 15);
    $details_2->fillField($this->fieldPrefix . '[second][prefix]', 'pfx-2');
    $details_2->fillField($this->fieldPrefix . '[second][suffix]', 'sfx-2');
    $this->submitSettingsForm();

    $expected_summary = [
      '<b>First subfield - boolean</b>',
      'Widget: checkbox',
      'Label: Yes',
      'Prefix (deprecated): pfx-1',
      'Suffix (deprecated): sfx-1',
      '<b>Second subfield - text</b>',
      'Widget: email',
      'Label display: inline',
      'Size: 15',
      'Prefix (deprecated): pfx-2',
      'Suffix (deprecated): sfx-2',
    ];
    $this->assertSettingsSummary($expected_summary);

    // -- Text and integer.
    $settings = [];
    $settings['storage']['first']['type'] = 'text';
    $settings['storage']['second']['type'] = 'integer';
    $settings['widget']['first']['type'] = 'textfield';
    $settings['widget']['second']['type'] = 'number';
    $this->createField($settings);

    $this->drupalGet($this->formDisplayAdminPath);
    $this->openSettingsForm();

    $this->assertXpath('//input[@type = "checkbox" and @name = "fields[{field_name}][settings_edit_form][settings][inline]"]');

    $this->assertXpath('//td//details[1]/summary[text() = "First subfield - Text (long)"]');
    $this->assertXpath('//td//details[1]//select[@name = "fields[{field_name}][settings_edit_form][settings][first][type]"]/option[@value = "textarea" and text() = "Text area" and @selected = "selected"]');
    $this->assertXpath('//td//details[1]//select[@name = "fields[{field_name}][settings_edit_form][settings][first][label_display]"]/option[@value = "block" and text() = "Block" and @selected = "selected"]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][placeholder]" and @value = ""]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][cols]" and @value = "10"]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][rows]" and @value = "5"]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][prefix]" and @value = ""]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][suffix]" and @value = ""]');

    $this->assertXpath('//td//details[2]/summary[text() = "Second subfield - Integer"]');
    $this->assertXpath('//td//details[2]//select[@name = "fields[{field_name}][settings_edit_form][settings][second][type]"]/option[@value = "number" and text() = "Number" and @selected = "selected"]');
    $this->assertXpath('//td//details[2]//select[@name = "fields[{field_name}][settings_edit_form][settings][second][label_display]"]/option[@value = "block" and text() = "Block" and @selected = "selected"]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][prefix]" and @value = ""]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][suffix]" and @value = ""]');

    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->fillField($this->fieldPrefix . '[first][placeholder]', 'Wine');
    $details_1->fillField($this->fieldPrefix . '[first][cols]', '18');
    $details_1->fillField($this->fieldPrefix . '[first][rows]', '12');
    $details_1->fillField($this->fieldPrefix . '[first][prefix]', 'pfx-1');
    $details_1->fillField($this->fieldPrefix . '[first][suffix]', 'sfx-1');

    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->fillField($this->fieldPrefix . '[second][prefix]', 'pfx-2');
    $details_2->fillField($this->fieldPrefix . '[second][suffix]', 'sfx-2');

    $this->submitSettingsForm();

    $expected_summary = [
      '<b>First subfield - text (long)</b>',
      'Widget: textarea',
      'Label display: block',
      'Columns: 18',
      'Rows: 12',
      'Placeholder: Wine',
      'Prefix (deprecated): pfx-1',
      'Suffix (deprecated): sfx-1',
      '<b>Second subfield - integer</b>',
      'Widget: number',
      'Label display: block',
      'Prefix (deprecated): pfx-2',
      'Suffix (deprecated): sfx-2',
    ];
    $this->assertSettingsSummary($expected_summary);

    // -- Float and decimal.
    $settings = [];
    $settings['storage']['first']['type'] = 'float';
    $settings['storage']['second']['type'] = 'numeric';
    $settings['widget']['first']['type'] = 'number';
    $settings['widget']['second']['type'] = 'number';
    $this->createField($settings);

    $this->drupalGet($this->formDisplayAdminPath);
    $this->openSettingsForm();

    $this->assertXpath('//input[@type = "checkbox" and @name = "fields[{field_name}][settings_edit_form][settings][inline]"]');

    $this->assertXpath('//td//details[1]//summary[text() = "First subfield - Float"]');
    $this->assertXpath('//td//details[1]//select[@name = "fields[{field_name}][settings_edit_form][settings][first][type]"]/option[@value = "number" and text() = "Number" and @selected = "selected"]');
    $this->assertXpath('//td//details[1]//select[@name = "fields[{field_name}][settings_edit_form][settings][first][label_display]"]/option[@value = "block" and text() = "Block" and @selected = "selected"]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][prefix]" and @value = ""]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][suffix]" and @value = ""]');

    $this->assertXpath('//td//details[2]/summary[text() = "Second subfield - Decimal"]');
    $this->assertXpath('//td//details[2]//select[@name = "fields[{field_name}][settings_edit_form][settings][second][type]"]/option[@value = "number" and text() = "Number" and @selected = "selected"]');
    $this->assertXpath('//td//details[2]//select[@name = "fields[{field_name}][settings_edit_form][settings][second][label_display]"]/option[@value = "block" and text() = "Block" and @selected = "selected"]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][prefix]" and @value = ""]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][suffix]" and @value = ""]');

    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->fillField($this->fieldPrefix . '[first][prefix]', 'pfx-1');
    $details_1->fillField($this->fieldPrefix . '[first][suffix]', 'sfx-1');
    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->fillField($this->fieldPrefix . '[second][prefix]', 'pfx-2');
    $details_2->fillField($this->fieldPrefix . '[second][suffix]', 'sfx-2');

    $this->submitSettingsForm();

    $expected_summary = [
      '<b>First subfield - float</b>',
      'Widget: number',
      'Label display: block',
      'Prefix (deprecated): pfx-1',
      'Suffix (deprecated): sfx-1',
      '<b>Second subfield - decimal</b>',
      'Widget: number',
      'Label display: block',
      'Prefix (deprecated): pfx-2',
      'Suffix (deprecated): sfx-2',
    ];
    $this->assertSettingsSummary($expected_summary);

    // -- Email and telephone.
    $settings['storage']['first']['type'] = 'email';
    $settings['storage']['second']['type'] = 'telephone';
    $settings['widget']['first']['type'] = 'email';
    $settings['widget']['second']['type'] = 'tel';
    $this->createField($settings);

    $this->drupalGet($this->formDisplayAdminPath);
    $this->openSettingsForm();

    $this->assertXpath('//input[@type = "checkbox" and @name = "fields[{field_name}][settings_edit_form][settings][inline]"]');

    $this->assertXpath('//td//details[1]/summary[text() = "First subfield - Email"]');
    $this->assertXpath('//td//details[1]//select[@name = "fields[{field_name}][settings_edit_form][settings][first][type]"]/option[@value = "email" and text() = "Email" and @selected = "selected"]');
    $this->assertXpath('//td//details[1]//select[@name = "fields[{field_name}][settings_edit_form][settings][first][label_display]"]/option[@value = "block" and text() = "Block" and @selected = "selected"]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][size]" and @value = "10"]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][placeholder]" and @value = ""]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][prefix]" and @value = ""]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][suffix]" and @value = ""]');

    $this->assertXpath('//td//details[2]/summary[text() = "Second subfield - Telephone"]');
    $this->assertXpath('//td//details[2]//select[@name = "fields[{field_name}][settings_edit_form][settings][second][type]"]/option[@value = "tel" and text() = "Telephone" and @selected = "selected"]');
    $this->assertXpath('//td//details[2]//select[@name = "fields[{field_name}][settings_edit_form][settings][second][label_display]"]/option[@value = "block" and text() = "Block" and @selected = "selected"]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][size]" and @value = "10"]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][placeholder]" and @value = ""]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][prefix]" and @value = ""]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][suffix]" and @value = ""]');

    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->fillField($this->fieldPrefix . '[first][size]', 25);
    $details_1->fillField($this->fieldPrefix . '[first][placeholder]', "White");
    $details_1->fillField($this->fieldPrefix . '[first][prefix]', 'pfx-1');
    $details_1->fillField($this->fieldPrefix . '[first][suffix]', 'sfx-1');
    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->fillField($this->fieldPrefix . '[second][size]', 15);
    $details_2->fillField($this->fieldPrefix . '[second][placeholder]', "Dark");
    $details_2->fillField($this->fieldPrefix . '[second][prefix]', 'pfx-2');
    $details_2->fillField($this->fieldPrefix . '[second][suffix]', 'sfx-2');

    $this->submitSettingsForm();

    $expected_summary = [
      '<b>First subfield - email</b>',
      'Widget: email',
      'Label display: block',
      'Size: 25',
      'Placeholder: White',
      'Prefix (deprecated): pfx-1',
      'Suffix (deprecated): sfx-1',
      '<b>Second subfield - telephone</b>',
      'Widget: tel',
      'Label display: block',
      'Size: 15',
      'Placeholder: Dark',
      'Prefix (deprecated): pfx-2',
      'Suffix (deprecated): sfx-2',
    ];
    $this->assertSettingsSummary($expected_summary);

    // -- URL and date.
    $settings['storage']['first']['type'] = 'uri';
    $settings['storage']['second']['type'] = 'datetime_iso8601';
    $settings['widget']['first']['type'] = 'url';
    $settings['widget']['second']['type'] = 'datetime';
    $this->createField($settings);

    $this->drupalGet($this->formDisplayAdminPath);
    $this->openSettingsForm();

    $this->assertXpath('//input[@type = "checkbox" and @name = "fields[{field_name}][settings_edit_form][settings][inline]"]');

    $this->assertXpath('//td//details[1]/summary[text() = "First subfield - Url"]');
    $this->assertXpath('//td//details[1]//select[@name = "fields[{field_name}][settings_edit_form][settings][first][type]"]/option[@value = "url" and text() = "Url" and @selected = "selected"]');
    $this->assertXpath('//td//details[1]//select[@name = "fields[{field_name}][settings_edit_form][settings][first][label_display]"]/option[@value = "block" and text() = "Block" and @selected = "selected"]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][size]" and @value = "10"]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][placeholder]" and @value = ""]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][prefix]" and @value = ""]');
    $this->assertXpath('//td//details[1]//input[@name = "fields[{field_name}][settings_edit_form][settings][first][suffix]" and @value = ""]');

    $this->assertXpath('//td//details[2]/summary[text() = "Second subfield - Date"]');
    $this->assertXpath('//td//details[2]//select[@name = "fields[{field_name}][settings_edit_form][settings][second][type]"]/option[@value = "datetime" and text() = "Date" and @selected = "selected"]');
    $this->assertNoXpath('//select[@name = "fields[{field_name}][settings_edit_form][settings][second][label_display]"]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][prefix]" and @value = ""]');
    $this->assertXpath('//td//details[2]//input[@name = "fields[{field_name}][settings_edit_form][settings][second][suffix]" and @value = ""]');

    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->fillField($this->fieldPrefix . '[first][size]', 25);
    $details_1->fillField($this->fieldPrefix . '[first][placeholder]', "Beer");
    $details_1->fillField($this->fieldPrefix . '[first][prefix]', 'pfx-1');
    $details_1->fillField($this->fieldPrefix . '[first][suffix]', 'sfx-1');
    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->fillField($this->fieldPrefix . '[second][prefix]', 'pfx-2');
    $details_2->fillField($this->fieldPrefix . '[second][suffix]', 'sfx-2');
    $this->submitSettingsForm();

    $expected_summary = [
      '<b>First subfield - url</b>',
      'Widget: url',
      'Label display: block',
      'Size: 25',
      'Placeholder: Beer',
      'Prefix (deprecated): pfx-1',
      'Suffix (deprecated): sfx-1',
      '<b>Second subfield - date</b>',
      'Widget: datetime',
      'Prefix (deprecated): pfx-2',
      'Suffix (deprecated): sfx-2',
    ];
    $this->assertSettingsSummary($expected_summary);
  }

}
