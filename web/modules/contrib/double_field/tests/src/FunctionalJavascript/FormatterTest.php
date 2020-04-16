<?php

namespace Drupal\Tests\double_field\FunctionalJavascript;

/**
 * A tests for Double Field formatter.
 *
 * @group double_field
 */
final class FormatterTest extends TestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'double_field',
    'node',
    'field_ui',
  ];

  /**
   * Test callback.
   */
  public function testBooleanAndString(): void {
    $page = $this->getSession()->getPage();

    $settings = [];
    $settings['storage']['first']['type'] = 'boolean';
    $settings['storage']['second']['type'] = 'string';
    $settings['first']['on_label'] = 'Yes';
    $settings['first']['off_label'] = 'No';
    $settings['first']['required'] = FALSE;
    $settings['formatter']['type'] = 'double_field_unformatted_list';
    $this->createField($settings);

    $this->submitNode([NULL, 'Bar']);

    // -- Default settings.
    $this->drupalGet($this->displayAdminPath);

    $expected_summary = [
      'Display as inline element',
      '<b>First subfield - boolean</b>',
      'Hidden: no',
      '<b>Second subfield - text</b>',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->openSettingsForm();

    // Check default formatter form.
    $this->assertXpath('//input[@type = "checkbox" and @name = "fields[{field_name}][settings_edit_form][settings][inline]" and @checked]');
    $this->assertXpath('//details[1]/summary[text() = "First subfield - Boolean"][1]');
    $this->assertXpath('//details[1]//div[1]/input[@name = "fields[{field_name}][settings_edit_form][settings][first][hidden]" and not(@checked)]');
    $this->assertXpath('//details[1]//div[2]/input[@name = "fields[{field_name}][settings_edit_form][settings][first][prefix]" and @value = ""]');
    $this->assertXpath('//details[1]//div[3]/input[@name = "fields[{field_name}][settings_edit_form][settings][first][suffix]" and @value = ""]');
    $this->assertXpath('//details[2]/summary[text() = "Second subfield - Text"][1]');
    $this->assertXpath('//details[2]//div[1]/input[@name = "fields[{field_name}][settings_edit_form][settings][second][hidden]" and not(@checked)]');
    $this->assertXpath('//details[2]//div[2]/input[@name = "fields[{field_name}][settings_edit_form][settings][second][prefix]" and @value = ""]');
    $this->assertXpath('//details[2]//div[3]/input[@name = "fields[{field_name}][settings_edit_form][settings][second][suffix]" and @value = ""]');

    $this->drupalGet('node/1');

    $prefix = '//div[contains(@class, "double-field-unformatted-list") and contains(@class, "container-inline")]';
    $this->assertXpath($prefix);
    $this->assertXpath($prefix . '/div[@class = "double-field-first" and normalize-space() = "No"]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second" and normalize-space() = "Bar"]');

    // Test "on_label".
    $this->drupalGet('node/1/edit');
    $page->checkField($this->fieldName . '[0][first]');
    $page->pressButton('Save');

    $prefix = '//div[contains(@class, "double-field-unformatted-list") and contains(@class, "container-inline")]';
    $this->assertXpath($prefix . '/div[@class = "double-field-first" and normalize-space() = "Yes"]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second" and normalize-space() = "Bar"]');

    // -- Without "inline" option.
    $this->drupalGet($this->displayAdminPath);

    $this->openSettingsForm();
    $page->uncheckField('Display as inline element');
    $this->submitSettingsForm();

    $expected_summary = [
      '<b>First subfield - boolean</b>',
      'Hidden: no',
      '<b>Second subfield - text</b>',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->drupalGet('node/1');
    $prefix = '//div[contains(@class, "double-field-unformatted-list") and not(contains(@class, "container-inline"))]';
    $this->assertXpath($prefix . '/div[@class = "double-field-first" and normalize-space() = "Yes"]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second" and normalize-space() = "Bar"]');

    // -- Hide first sub-field.
    $this->drupalGet($this->displayAdminPath);
    $this->openSettingsForm();
    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->checkField($this->fieldPrefix . '[first][hidden]');
    $this->submitSettingsForm();

    $expected_summary = [
      '<b>First subfield - boolean</b>',
      'Hidden: yes',
      '<b>Second subfield - text</b>',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->drupalGet('node/1');
    $prefix = '//div[contains(@class, "double-field-unformatted-list") and not(contains(@class, "container-inline"))]';
    $this->assertNoXpath($prefix . '/div[@class = "double-field-first"]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second" and normalize-space() = "Bar"]');

    // -- Hide second sub-field.
    $this->drupalGet($this->displayAdminPath);
    $this->openSettingsForm();
    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->checkField($this->fieldPrefix . '[second][hidden]');
    $this->submitSettingsForm();

    $expected_summary = [
      '<b>First subfield - boolean</b>',
      'Hidden: yes',
      '<b>Second subfield - text</b>',
      'Hidden: yes',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->drupalGet('node/1');
    $prefix = '//div[contains(@class, "double-field-unformatted-list") and not(contains(@class, "container-inline"))]';
    $this->assertNoXpath($prefix . '/div[@class = "double-field-first"]');
    $this->assertNoXpath($prefix . '/div[@class = "double-field-second"]');

    // -- Add prefixes and suffixes.
    $this->drupalGet($this->displayAdminPath);
    $this->openSettingsForm();

    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->unCheckField($this->fieldPrefix . '[first][hidden]');
    $details_1->fillField($this->fieldPrefix . '[first][prefix]', 'pfx-1');
    $details_1->fillField($this->fieldPrefix . '[first][suffix]', 'sfx-1');

    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->unCheckField($this->fieldPrefix . '[second][hidden]');
    $details_2->fillField($this->fieldPrefix . '[second][prefix]', 'pfx-2');
    $details_2->fillField($this->fieldPrefix . '[second][suffix]', 'sfx-2');
    $this->submitSettingsForm();

    $expected_summary = [
      '<b>First subfield - boolean</b>',
      'Hidden: no',
      'Prefix (deprecated): pfx-1',
      'Suffix (deprecated): sfx-1',
      '<b>Second subfield - text</b>',
      'Hidden: no',
      'Prefix (deprecated): pfx-2',
      'Suffix (deprecated): sfx-2',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->drupalGet('node/1');
    $prefix = '//div[contains(@class, "double-field-unformatted-list")]';
    $this->assertXpath($prefix . '/div[@class = "double-field-first"]/span[@class = "double-field-prefix" and text() = "pfx-1"]');
    $this->assertXpath($prefix . '//div[@class = "double-field-first" and contains(., "Yes")]');
    $this->assertXpath($prefix . '/div[@class = "double-field-first"]/span[@class = "double-field-suffix" and text() = "sfx-1"]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second"]/span[@class = "double-field-prefix" and text() = "pfx-2"]');
    $this->assertXpath($prefix . '//div[@class = "double-field-second" and contains(., "Bar")]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second"]/span[@class = "double-field-suffix" and text() = "sfx-2"]');
  }

  /**
   * Test callback.
   */
  public function testTextAndInteger(): void {
    $page = $this->getSession()->getPage();

    $settings = [];
    $settings['storage']['first']['type'] = 'text';
    $settings['storage']['second']['type'] = 'integer';
    $settings['formatter']['type'] = 'double_field_unformatted_list';
    $this->createField($settings);

    $this->drupalGet($this->displayAdminPath);

    $expected_summary = [
      'Display as inline element',
      '<b>First subfield - text (long)</b>',
      'Hidden: no',
      '<b>Second subfield - integer</b>',
      'Hidden: no',
      'Number format: 1234',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->openSettingsForm();

    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->selectFieldOption("Thousand marker", 'Space');
    $this->submitSettingsForm();

    $expected_summary = [
      'Display as inline element',
      '<b>First subfield - text (long)</b>',
      'Hidden: no',
      '<b>Second subfield - integer</b>',
      'Hidden: no',
      'Number format: 1 234',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->submitNode(['<b>Foo</b>', 123456]);
    $prefix = '//div[contains(@class, "double-field-unformatted-list")]';
    $this->assertXpath($prefix . '/div[@class = "double-field-first" and normalize-space() = "<b>Foo</b>"]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second" and normalize-space() = "123 456"]');
  }

  /**
   * Test callback.
   */
  public function testFloatAndNumeric(): void {

    $settings = [];
    $settings['storage']['first']['type'] = 'float';
    $settings['storage']['second']['type'] = 'numeric';
    $settings['formatter']['type'] = 'double_field_unformatted_list';
    $this->createField($settings);

    $this->drupalGet($this->displayAdminPath);

    $expected_summary = [
      'Display as inline element',
      '<b>First subfield - float</b>',
      'Hidden: no',
      'Number format: 1234.12',
      '<b>Second subfield - decimal</b>',
      'Hidden: no',
      'Number format: 1234.12',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->openSettingsForm();

    $page = $this->getSession()->getPage();
    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->selectFieldOption("Thousand marker", 'Space');
    $details_1->selectFieldOption("Decimal marker", 'Comma');
    $details_1->fillField('Scale', 1);
    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->selectFieldOption("Thousand marker", 'Space');
    $details_2->selectFieldOption("Decimal marker", 'Comma');
    $details_2->fillField('Scale', 3);

    $this->submitSettingsForm();

    $expected_summary = [
      'Display as inline element',
      '<b>First subfield - float</b>',
      'Hidden: no',
      'Number format: 1 234,1',
      '<b>Second subfield - decimal</b>',
      'Hidden: no',
      'Number format: 1 234,123',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->submitNode([3456789.3, 612345]);

    $prefix = '//div[contains(@class, "double-field-unformatted-list")]';
    $this->assertXpath($prefix . '/div[@class = "double-field-first" and normalize-space(text()) = "3 456 789,3"]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second" and normalize-space(text()) = "612 345,000"]');
  }

  /**
   * Test callback.
   */
  public function testEmailAndTelephone(): void {
    $page = $this->getSession()->getPage();

    $settings = [];
    $settings['storage']['first']['type'] = 'email';
    $settings['storage']['second']['type'] = 'telephone';
    $settings['formatter']['type'] = 'double_field_unformatted_list';
    $this->createField($settings);

    $this->drupalGet($this->displayAdminPath);

    $expected_summary = [
      'Display as inline element',
      '<b>First subfield - email</b>',
      'Link: no',
      'Hidden: no',
      '<b>Second subfield - telephone</b>',
      'Link: no',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    // Make sure the values are not rendered as links.
    $this->submitNode(['admin@localhost', '+71234567889']);

    $prefix = '//div[contains(@class, "double-field-unformatted-list")]';
    $this->assertXpath($prefix . '//div[@class = "double-field-first" and normalize-space(text()) = "admin@localhost"]');
    $this->assertXpath($prefix . '//div[@class = "double-field-second" and normalize-space(text()) = "+71234567889"]');

    $this->drupalGet($this->displayAdminPath);
    $this->openSettingsForm();

    $page = $this->getSession()->getPage();
    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->checkField('Display as link');
    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->checkField('Display as link');
    $this->submitSettingsForm();

    $expected_summary = [
      'Display as inline element',
      '<b>First subfield - email</b>',
      'Link: yes',
      'Hidden: no',
      '<b>Second subfield - telephone</b>',
      'Link: yes',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->drupalGet('node/1');

    $prefix = '//div[contains(@class, "double-field-unformatted-list")]';
    $this->assertXpath($prefix . '/div[@class = "double-field-first"]/a[@href = "mailto:admin@localhost" and text() = "admin@localhost"]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second"]/a[@href = "tel:%2B71234567889" and text() = "+71234567889"]');
  }

  /**
   * Test callback.
   */
  public function testDateAndUri(): void {
    $page = $this->getSession()->getPage();

    $settings = [];
    $settings['storage']['first']['type'] = 'datetime_iso8601';
    $settings['storage']['second']['type'] = 'uri';
    $settings['formatter']['type'] = 'double_field_unformatted_list';
    $this->createField($settings);

    $this->drupalGet($this->displayAdminPath);

    $expected_summary = [
      'Display as inline element',
      '<b>First subfield - date</b>',
      'Date format: medium',
      'Hidden: no',
      '<b>Second subfield - url</b>',
      'Link: no',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    // Make sure the values are not rendered as links.
    $this->drupalGet($this->nodeAddPath);
    $page->fillField('title[0][value]', 'Example');
    $page->fillField($this->fieldName . '[0][first][date]', '12/11/2019');
    $page->fillField($this->fieldName . '[0][first][time]', '034700PM');
    $page->fillField($this->fieldName . '[0][second]', 'https://example.com');
    $page->pressButton('Save');

    $prefix = '//div[contains(@class, "double-field-unformatted-list")]';
    $this->assertXpath($prefix . '/div[@class = "double-field-first"]/time[@datetime = "2019-12-11T15:47:00Z" and normalize-space() = "Wed, 12/11/2019 - 15:47"]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second" and normalize-space(text()) = "https://example.com"]');

    $this->drupalGet($this->displayAdminPath);
    $this->openSettingsForm();

    $page = $this->getSession()->getPage();
    $details_1 = $page->find('xpath', '//details[1]');
    $details_1->click();
    $details_1->selectFieldOption('Date format', 'short');
    $details_2 = $page->find('xpath', '//details[2]');
    $details_2->click();
    $details_2->checkField('Display as link');
    $this->submitSettingsForm();

    $expected_summary = [
      'Display as inline element',
      '<b>First subfield - date</b>',
      'Date format: short',
      'Hidden: no',
      '<b>Second subfield - url</b>',
      'Link: yes',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->drupalGet('node/1');

    $prefix = '//div[contains(@class, "double-field-unformatted-list")]';
    $this->assertXpath($prefix . '/div[@class = "double-field-first"]/time[@datetime = "2019-12-11T15:47:00Z" and normalize-space() = "12/11/2019 - 15:47"]');
    $this->assertXpath($prefix . '/div[@class = "double-field-second"]/a[@href = "https://example.com" and normalize-space() = "https://example.com"]');
  }

  /**
   * Test callback.
   */
  public function testTabsFormatter(): void {
    $page = $this->getSession()->getPage();

    $settings = [];
    $settings['storage']['cardinality'] = 3;
    $settings['storage']['first']['type'] = 'string';
    $settings['storage']['second']['type'] = 'string';
    $settings['formatter']['type'] = 'double_field_tabs';
    $this->createField($settings);

    $this->submitNode(['Foo 1', 'Foo 2'], ['Bar 1', 'Bar 2'], ['Qux 1', 'Qux 2']);
    $this->drupalGet('node/1');

    $wrapper = $page->find('xpath', '//div[contains(@class, "field--type-double-field")]/div/div[contains(@class, "double-field-tabs")]');
    self::assertNotNull($wrapper);
    $links = $wrapper->findAll('xpath', '/ul[@role = "tablist"]/li[@role ="tab"]/a');
    self::assertCount(3, $links);
    self::assertEquals('Foo 1', $links[0]->getText());
    self::assertEquals('Bar 1', $links[1]->getText());
    self::assertEquals('Qux 1', $links[2]->getText());
    $panels = $wrapper->findAll('xpath', '/div[@role = "tabpanel"]');
    self::assertCount(3, $panels);

    self::assertEquals('Foo 2', $panels[0]->getText());
    self::assertTrue($panels[0]->isVisible());
    self::assertFalse($panels[1]->isVisible());
    self::assertFalse($panels[2]->isVisible());

    $links[1]->click();
    self::assertEquals('Bar 2', $panels[1]->getText());
    self::assertFalse($panels[0]->isVisible());
    self::assertTrue($panels[1]->isVisible());
    self::assertFalse($panels[2]->isVisible());

    $links[2]->click();
    self::assertEquals('Qux 2', $panels[2]->getText());
    self::assertFalse($panels[0]->isVisible());
    self::assertFalse($panels[1]->isVisible());
    self::assertTrue($panels[2]->isVisible());
  }

  /**
   * Test callback.
   */
  public function testAccordionFormatter(): void {
    $page = $this->getSession()->getPage();

    $settings = [];
    $settings['storage']['cardinality'] = 3;
    $settings['storage']['first']['type'] = 'string';
    $settings['storage']['second']['type'] = 'string';
    $settings['formatter']['type'] = 'double_field_accordion';
    $this->createField($settings);

    $this->submitNode(['Foo 1', 'Foo 2'], ['Bar 1', 'Bar 2'], ['Qux 1', 'Qux 2']);
    $this->drupalGet('node/1');

    $wrapper = $page->find('xpath', '//div[contains(@class, "field--type-double-field")]/div/div[contains(@class, "double-field-accordion")]');
    self::assertNotNull($wrapper);
    $links = $wrapper->findAll('xpath', '/h3[@role = "tab"]/a');
    self::assertCount(3, $links);
    self::assertEquals('Foo 1', $links[0]->getText());
    self::assertEquals('Bar 1', $links[1]->getText());
    self::assertEquals('Qux 1', $links[2]->getText());

    $panels = $wrapper->findAll('xpath', '/div[@role = "tabpanel" and preceding-sibling::h3]');
    self::assertCount(3, $links);

    $links[0]->click();
    self::assertEquals('Foo 2', $panels[0]->getText());
    self::assertTrue($panels[0]->isVisible());
    self::assertFalse($panels[1]->isVisible());
    self::assertFalse($panels[2]->isVisible());

    $links[1]->click();
    self::assertEquals('Bar 2', $panels[1]->getText());
    self::assertFalse($panels[0]->isVisible());
    self::assertTrue($panels[1]->isVisible());
    self::assertFalse($panels[2]->isVisible());

    $links[2]->click();
    self::assertEquals('Qux 2', $panels[2]->getText());
    self::assertFalse($panels[0]->isVisible());
    self::assertFalse($panels[1]->isVisible());
    self::assertTrue($panels[2]->isVisible());
  }

  /**
   * Test callback.
   */
  public function testDetailsFormatter(): void {
    $page = $this->getSession()->getPage();

    $settings = [];
    $settings['storage']['first']['type'] = 'string';
    $settings['storage']['second']['type'] = 'string';
    $settings['formatter']['type'] = 'double_field_details';
    $this->createField($settings);

    $this->submitNode(['Foo 1', 'Foo 2']);
    $this->drupalGet('node/1');

    $xpath = '//div[contains(@class, "field--type-double-field")]';
    $xpath .= '//details[contains(@class, "double-field-detail") and @open]';
    $xpath .= '/summary[text() = "Foo 1"][1]';
    $xpath .= '/following-sibling::div[@class = "details-wrapper" and normalize-space(text()) = "Foo 2"]';
    $this->assertXpath($xpath);

    $this->drupalGet($this->displayAdminPath);

    $expected_summary = [
      'Open: yes',
      '<b>First subfield - text</b>',
      'Hidden: no',
      '<b>Second subfield - text</b>',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->openSettingsForm();
    $this->assertXpath('//input[@type = "checkbox" and @name = "fields[{field_name}][settings_edit_form][settings][open]" and @checked]');
    $page->uncheckField("Open");
    $this->submitSettingsForm();

    $expected_summary = [
      'Open: no',
      '<b>First subfield - text</b>',
      'Hidden: no',
      '<b>Second subfield - text</b>',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->drupalGet('node/1');
    $xpath = '//div[contains(@class, "field--type-double-field")]';
    $xpath .= '/details[contains(@class, "double-field-detail") and not(@open)]';
    $xpath .= '/summary[text() = "Foo 1"][1]';
    $xpath .= '/following-sibling::div[@class = "details-wrapper" and normalize-space(text()) = "Foo 2"]';
    $this->assertXpath($xpath);
  }

  /**
   * Test callback.
   */
  public function testListFormatter(): void {
    $page = $this->getSession()->getPage();

    $settings = [];
    $settings['storage']['cardinality'] = 3;
    $settings['storage']['first']['type'] = 'string';
    $settings['storage']['second']['type'] = 'string';
    $settings['formatter']['type'] = 'double_field_html_list';
    $this->createField($settings);

    $this->submitNode(['Foo 1', 'Foo 2'], ['Bar 1', 'Bar 2'], ['Qux 1', 'Qux 2']);
    $this->drupalGet('node/1');

    $prefix = '//div[contains(@class, "field--type-double-field")]';
    $prefix .= '//div[@class = "item-list"]';
    $prefix .= '//ul[@class = "double-field-list"]';
    $item_xpath = $prefix . '/li[@class = "container-inline"][1]';
    $item_xpath .= '/div[@class = "double-field-first" and normalize-space(text()) = "Foo 1"][1]';
    $item_xpath .= '/following-sibling::div[@class = "double-field-second" and normalize-space(text()) = "Foo 2"]';
    $this->assertXpath($item_xpath);
    $item_xpath = $prefix . '/li[@class = "container-inline"][2]';
    $item_xpath .= '/div[@class = "double-field-first" and normalize-space(text()) = "Bar 1"][1]';
    $item_xpath .= '/following-sibling::div[@class = "double-field-second" and normalize-space(text()) = "Bar 2"]';
    $this->assertXpath($item_xpath);
    $item_xpath = $prefix . '/li[@class = "container-inline"][3]';
    $item_xpath .= '/div[@class = "double-field-first" and normalize-space(text()) = "Qux 1"][1]';
    $item_xpath .= '/following-sibling::div[@class = "double-field-second" and normalize-space(text()) = "Qux 2"]';
    $this->assertXpath($item_xpath);

    $this->drupalGet($this->displayAdminPath);
    $expected_summary = [
      'List type: ul',
      'Display as inline element',
      '<b>First subfield - text</b>',
      'Hidden: no',
      '<b>Second subfield - text</b>',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->openSettingsForm();
    $page->selectFieldOption($this->fieldPrefix . '[list_type]', 'ol');
    $this->submitSettingsForm();
    $expected_summary = [
      'List type: ol',
      'Display as inline element',
      '<b>First subfield - text</b>',
      'Hidden: no',
      '<b>Second subfield - text</b>',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->drupalGet('node/1');
    $prefix = '//div[contains(@class, "field--type-double-field")]';
    $prefix .= '//div[@class = "item-list"]';
    $prefix .= '//ol[@class = "double-field-list"]';
    $item_xpath = $prefix . '/li[@class = "container-inline"][1]';
    $item_xpath .= '/div[@class = "double-field-first" and normalize-space(text()) = "Foo 1"]';
    $item_xpath .= '/following-sibling::div[@class = "double-field-second" and normalize-space(text()) = "Foo 2"]';
    $this->assertXpath($item_xpath);
    $item_xpath = $prefix . '/li[@class = "container-inline"][2]';
    $item_xpath .= '/div[@class = "double-field-first" and normalize-space(text()) = "Bar 1"]';
    $item_xpath .= '/following-sibling::div[@class = "double-field-second" and normalize-space(text()) = "Bar 2"]';
    $this->assertXpath($item_xpath);
    $item_xpath = $prefix . '/li[@class = "container-inline"][3]';
    $item_xpath .= '/div[@class = "double-field-first" and normalize-space(text()) = "Qux 1"]';
    $item_xpath .= '/following-sibling::div[@class = "double-field-second" and normalize-space(text()) = "Qux 2"]';
    $this->assertXpath($item_xpath);

    $this->drupalGet($this->displayAdminPath);
    $this->openSettingsForm();
    $inline_input = $page->find('xpath', '//input[@name = "fields[' . $this->fieldName . '][settings_edit_form][settings][inline]"]');
    $this->assertTrue($inline_input->isVisible());
    $page->selectFieldOption($this->fieldPrefix . '[list_type]', 'dl');
    $this->assertFalse($inline_input->isVisible());
    $this->submitSettingsForm();
    $expected_summary = [
      'List type: dl',
      '<b>First subfield - text</b>',
      'Hidden: no',
      '<b>Second subfield - text</b>',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);
    $this->drupalGet('node/1');

    $prefix = '//div[contains(@class, "field--type-double-field")]';
    $prefix .= '//dl[@class = "double-field-definition-list"]';
    $item_xpath = $prefix . '/dt[normalize-space(text()) = "Foo 1"]/following-sibling::dd[normalize-space(text()) = "Foo 2"]';
    $this->assertXpath($item_xpath);
    $item_xpath = $prefix . '/dt[normalize-space(text()) = "Bar 1"]/following-sibling::dd[normalize-space(text()) = "Bar 2"]';
    $this->assertXpath($item_xpath);
    $item_xpath = $prefix . '/dt[normalize-space(text()) = "Qux 1"]/following-sibling::dd[normalize-space(text()) = "Qux 2"]';
    $this->assertXpath($item_xpath);
  }

  /**
   * Test callback.
   */
  public function testTableFormatter(): void {
    $page = $this->getSession()->getPage();

    $settings = [];
    $settings['storage']['cardinality'] = 3;
    $settings['storage']['first']['type'] = 'string';
    $settings['storage']['second']['type'] = 'string';
    $settings['formatter']['type'] = 'double_field_table';
    $this->createField($settings);

    $this->submitNode(['Foo 1', 'Foo 2'], ['Bar 1', 'Bar 2'], ['Qux 1', 'Qux 2']);
    $this->drupalGet('node/1');

    $prefix = '//div[contains(@class, "field--type-double-field")]';
    $prefix .= '//table[@class = "double-field-table"]/tbody';
    $item_xpath = $prefix . '/tr[1]/td[normalize-space(text()) = "Foo 1"]/following-sibling::td[normalize-space(text()) = "Foo 2"]';
    $this->assertXpath($item_xpath);
    $item_xpath = $prefix . '/tr[2]/td[normalize-space(text()) = "Bar 1"]/following-sibling::td[normalize-space(text()) = "Bar 2"]';
    $this->assertXpath($item_xpath);
    $item_xpath = $prefix . '/tr[3]/td[normalize-space(text()) = "Qux 1"]/following-sibling::td[normalize-space(text()) = "Qux 2"]';
    $this->assertXpath($item_xpath);

    $this->drupalGet($this->displayAdminPath);
    $expected_summary = [
      'Enable row number column: no',
      '<b>First subfield - text</b>',
      'Hidden: no',
      '<b>Second subfield - text</b>',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->openSettingsForm();
    $page->checkField('Enable row number column');
    $page->fillField('First column label', 'First');
    $page->fillField('Second column label', 'Second');
    $this->submitSettingsForm();

    $this->drupalGet($this->displayAdminPath);
    $expected_summary = [
      'Enable row number column: yes',
      'Number column label: №',
      'First column label: First',
      'Second column label: Second',
      '<b>First subfield - text</b>',
      'Hidden: no',
      '<b>Second subfield - text</b>',
      'Hidden: no',
    ];
    $this->assertSettingsSummary($expected_summary);

    $this->drupalGet('node/1');
    $prefix = '//div[contains(@class, "field--type-double-field")]';
    $prefix .= '//table[contains(@class, "double-field-table")]';
    $header_xpath = $prefix . '/thead/tr/th[text() = "№"]/following-sibling::th[text() = "First"]/following-sibling::th[text() = "Second"]';
    $this->assertXpath($header_xpath);

    $item_xpath = $prefix . '//tr[1]/td[normalize-space(text()) = "Foo 1"]/following-sibling::td[normalize-space(text()) = "Foo 2"]';
    $this->assertXpath($item_xpath);
    $item_xpath = $prefix . '//tr[2]/td[normalize-space(text()) = "Bar 1"]/following-sibling::td[normalize-space(text()) = "Bar 2"]';
    $this->assertXpath($item_xpath);
    $item_xpath = $prefix . '//tr[3]/td[normalize-space(text()) = "Qux 1"]/following-sibling::td[normalize-space(text()) = "Qux 2"]';
    $this->assertXpath($item_xpath);
  }

  /**
   * Submits node form.
   */
  private function submitNode(): void {
    $field_values = func_get_args();
    $this->drupalGet($this->nodeAddPath);
    $page = $this->getSession()->getPage();
    $page->fillField('title[0][value]', 'Example');
    foreach ($field_values as $delta => $values) {
      if ($values[0] !== NULL) {
        $page->fillField("{$this->fieldName}[$delta][first]", $values[0]);
      }
      if ($values[1] !== NULL) {
        $page->fillField("{$this->fieldName}[$delta][second]", $values[1]);
      }
    }
    $page->pressButton('Save');
  }

}
