<?php

namespace Drupal\Tests\userprotect\Functional;

use Drupal\userprotect\Entity\ProtectionRule;
use Drupal\userprotect\Entity\ProtectionRuleInterface;

/**
 * Tests creating, editing and deleting protection rules through the UI.
 *
 * @group userprotect
 */
class ProtectionRuleCrudTest extends UserProtectBrowserTestBase {

  /**
   * The operating account.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $account;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->account = $this->drupalCreateUser(['userprotect.administer']);
    $this->drupalLogin($this->account);
  }

  /**
   * Tests if role based protection rules can be created through the UI.
   */
  public function testCrudRoleProtectionRule() {
    $rid = $this->drupalCreateRole([]);
    $rule_id = strtolower($this->randomMachineName());
    $label = $this->randomMachineName();

    // Create rule.
    $edit = [
      'label' => $label,
      'name' => $rule_id,
      'entity_id' => $rid,
      'protection[user_mail]' => TRUE,
    ];
    $this->drupalPostForm('admin/config/people/userprotect/add', $edit, t('Save'));

    // Assert that the rule was created.
    $protection_rule = ProtectionRule::load($rule_id);
    $this->assertInstanceOf(ProtectionRuleInterface::class, $protection_rule, 'A protection rule was created through the UI.');

    // Assert that the rule has the expected values.
    $this->assertEquals($rule_id, $protection_rule->id());
    $this->assertEquals($label, $protection_rule->label);
    $this->assertEquals('user_role', $protection_rule->getProtectedEntityTypeId());
    $this->assertEquals($rid, $protection_rule->getProtectedEntityId());
    $enabled_plugins = $protection_rule->getProtections()->getEnabledPlugins();
    $this->assertCount(1, $enabled_plugins, 'One plugin was enabled.');
    $plugin = reset($enabled_plugins);
    $this->assertEquals('user_mail', $plugin->getPluginId());

    // Edit rule.
    $edit = [
      'protection[user_name]' => TRUE,
      'protection[user_mail]' => FALSE,
    ];
    $this->drupalPostForm('admin/config/people/userprotect/manage/' . $rule_id, $edit, t('Save'));

    // Assert that the rule was updated with the expected values.
    $protection_rule = ProtectionRule::load($rule_id);
    $this->assertEquals($rule_id, $protection_rule->id());
    $this->assertEquals($label, $protection_rule->label);
    $this->assertEquals('user_role', $protection_rule->getProtectedEntityTypeId());
    $this->assertEquals($rid, $protection_rule->getProtectedEntityId());
    $enabled_plugins = $protection_rule->getProtections()->getEnabledPlugins();
    $this->assertCount(1, $enabled_plugins, 'One plugin was enabled.');
    $plugin = reset($enabled_plugins);
    $this->assertEquals('user_name', $plugin->getPluginId());

    // Attempt to create a rule with the same name.
    $edit = [
      'label' => $label,
      'name' => $rule_id,
      'entity_id' => $rid,
      'protection[user_mail]' => TRUE,
    ];
    $this->drupalPostForm('admin/config/people/userprotect/add', $edit, t('Save'));
    $this->assertSession()->pageTextContains('The machine-readable name is already in use. It must be unique.');

    // Assert only one protection rule exists.
    $entities = ProtectionRule::loadMultiple(NULL);
    $this->assertCount(1, $entities, 'Only one protection rule exists.');

    // Delete rule.
    $this->drupalPostForm('admin/config/people/userprotect/manage/' . $rule_id . '/delete', [], t('Delete'));
    // Assert the rule no longer exists.
    $protection_rule = ProtectionRule::load($rule_id);
    $this->assertEmpty($protection_rule, 'The protection rule was deleted.');
  }

  /**
   * Tests if user based protection rules can be created through the UI.
   */
  public function testCrudUserProtectionRule() {
    $account = $this->drupalCreateUser();
    $rule_id = strtolower($this->randomMachineName());
    $label = $this->randomMachineName();

    // Create rule.
    $edit = [
      'label' => $label,
      'name' => $rule_id,
      'entity_id' => $account->getAccountName(),
      'protection[user_mail]' => TRUE,
    ];
    $this->drupalPostForm('admin/config/people/userprotect/add/user', $edit, t('Save'));

    // Assert that the rule was created.
    $protection_rule = ProtectionRule::load($rule_id);
    $this->assertInstanceOf(ProtectionRuleInterface::class, $protection_rule, 'A protection rule was created through the UI.');

    // Assert that the rule has the expected values.
    $this->assertEquals($rule_id, $protection_rule->id());
    $this->assertEquals($label, $protection_rule->label);
    $this->assertEquals('user', $protection_rule->getProtectedEntityTypeId());
    $this->assertEquals($account->id(), $protection_rule->getProtectedEntityId());
    $enabled_plugins = $protection_rule->getProtections()->getEnabledPlugins();
    $this->assertCount(1, $enabled_plugins, 'One plugin was enabled.');
    $plugin = reset($enabled_plugins);
    $this->assertEquals('user_mail', $plugin->getPluginId());

    // Edit rule.
    $edit = [
      'protection[user_name]' => TRUE,
      'protection[user_mail]' => FALSE,
    ];
    $this->drupalPostForm('admin/config/people/userprotect/manage/' . $rule_id, $edit, t('Save'));

    // Assert that the rule was updated with the expected values.
    $protection_rule = ProtectionRule::load($rule_id);
    $this->assertEquals($rule_id, $protection_rule->id());
    $this->assertEquals($label, $protection_rule->label);
    $this->assertEquals('user', $protection_rule->getProtectedEntityTypeId());
    $this->assertEquals($account->id(), $protection_rule->getProtectedEntityId());
    $enabled_plugins = $protection_rule->getProtections()->getEnabledPlugins();
    $this->assertCount(1, $enabled_plugins, 'One plugin was enabled.');
    $plugin = reset($enabled_plugins);
    $this->assertEquals('user_name', $plugin->getPluginId());

    // Attempt to create a rule with the same name.
    $edit = [
      'label' => $label,
      'name' => $rule_id,
      'entity_id' => $account->getAccountName(),
      'protection[user_mail]' => TRUE,
    ];
    $this->drupalPostForm('admin/config/people/userprotect/add/user', $edit, t('Save'));
    $this->assertSession()->pageTextContains('The machine-readable name is already in use. It must be unique.');

    // Assert only one protection rule exists.
    $entities = ProtectionRule::loadMultiple(NULL);
    $this->assertCount(1, $entities, 'Only one protection rule exists.');

    // Delete rule.
    $this->drupalPostForm('admin/config/people/userprotect/manage/' . $rule_id . '/delete', [], t('Delete'));
    // Assert the rule no longer exists.
    $protection_rule = ProtectionRule::load($rule_id);
    $this->assertEmpty($protection_rule, 'The protection rule was deleted.');
  }

}
