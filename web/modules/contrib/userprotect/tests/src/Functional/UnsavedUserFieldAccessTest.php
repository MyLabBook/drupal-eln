<?php

namespace Drupal\Tests\userprotect\Functional;

use Drupal\user\Entity\User;

/**
 * Tests field access for an unsaved user.
 *
 * Field access checks could for example be performed on new users when created
 * via REST POST.
 *
 * @group userprotect
 */
class UnsavedUserFieldAccessTest extends UserProtectBrowserTestBase {

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

    $this->account = $this->drupalCreateUser(['administer users', 'administer permissions']);
    $this->drupalLogin($this->account);
  }

  /**
   * Tests if userprotect doesn't interfere with creating users.
   */
  public function testUserCreate() {
    // Create an account using the user interface.
    $name = $this->randomMachineName();
    $edit = [
      'name' => $name,
      'mail' => $this->randomMachineName() . '@example.com',
      'pass[pass1]' => $pass = $this->randomString(),
      'pass[pass2]' => $pass,
      'notify' => FALSE,
    ];
    $this->drupalPostForm('admin/people/create', $edit, t('Create new account'));
    $this->assertSession()->pageTextContains(t('Created a new user account for @name. No email has been sent.', ['@name' => $edit['name']]), 'User created');

    // Try to create an user with the same name and assert that it doesn't
    // result into a fatal error.
    $edit = [
      'name' => $name,
      'mail' => $this->randomMachineName() . '@example.com',
      'pass[pass1]' => $pass = $this->randomString(),
      'pass[pass2]' => $pass,
      'notify' => FALSE,
    ];
    $this->drupalPostForm('admin/people/create', $edit, t('Create new account'));
    $this->assertSession()->pageTextContains(t('The username @name is already taken.', ['@name' => $edit['name']]));
  }

  /**
   * Tests field access for an unsaved user's name.
   */
  public function testNameAccessForUnsavedUser() {
    $module_handler = $this->container->get('module_handler');
    $module_installer = $this->container->get('module_installer');

    // Create an unsaved user entity.
    $unsavedUserEntity = User::create([]);

    // The logged in user should have the privileges to edit the unsaved user's
    // name.
    $this->assertTrue($unsavedUserEntity->isAnonymous(), 'Unsaved user is considered anonymous when userprotect is installed.');
    $this->assertTrue($unsavedUserEntity->get('name')->access('edit'), 'Logged in user is allowed to edit name field when userprotect is installed.');

    // Uninstall userprotect and verify that logged in user has privileges to
    // edit the unsaved user's name.
    $module_installer->uninstall(['userprotect_test', 'userprotect']);

    // Workaround https://www.drupal.org/node/2021959
    // See \Drupal\Core\Test\FunctionalTestSetupTrait::rebuildContainer.
    $this->rebuildContainer();
    $module_handler = $this->container->get('module_handler');

    $this->assertFalse($module_handler->moduleExists('userprotect'), 'Userprotect uninstalled successfully.');
    $this->assertTrue($unsavedUserEntity->isAnonymous(), 'Unsaved user is considered anonymous when userprotect is uninstalled.');
    $this->assertTrue($unsavedUserEntity->get('name')->access('edit'), 'Logged in user is allowed to edit name field when userprotect is uninstalled.');
  }

}
