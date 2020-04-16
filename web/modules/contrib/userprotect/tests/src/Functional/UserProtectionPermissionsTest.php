<?php

namespace Drupal\Tests\userprotect\Functional;

use Drupal\Core\Session\AccountInterface;

/**
 * Tests if "change own" User Protect permissions are respected.
 *
 * The test includes coverage for the following permissions:
 * - Change own e-mail (userprotect.mail.edit);
 * - Change own password (userprotect.pass.edit);
 * - Change own account (userprotect.account.edit).
 *
 * @group userprotect
 */
class UserProtectionPermissionsTest extends UserProtectBrowserTestBase {

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

    // Revoke default permissions on the authenticated user role that are
    // installed by the userprotect module.
    // @see userprotect_install().
    $role = \Drupal::entityTypeManager()->getStorage('user_role')->load(AccountInterface::AUTHENTICATED_ROLE);
    $role->revokePermission('userprotect.mail.edit');
    $role->revokePermission('userprotect.pass.edit');
    $role->revokePermission('userprotect.account.edit');
    $role->save();
  }

  /**
   * Tests edit mail with permission "userprotect.mail.edit".
   *
   * Tests if an user with the permission "userprotect.mail.edit" can edit its
   * own mail.
   */
  public function testEditOwnMail() {
    // Create account that may edit its own mail address.
    $account = $this->drupalCreateUser(['userprotect.mail.edit', 'userprotect.account.edit']);
    $this->drupalLogin($account);

    $edit = [
      'mail' => $this->randomMachineName() . '@example.com',
    ];
    $this->drupalPostForm('user/' . $account->id() . '/edit', $edit, t('Save'));

    // Assert the mail address changed.
    $account = $this->reloadEntity($account);
    $this->assertEquals($edit['mail'], $account->getEmail(), "The user has changed its own mail address.");
  }

  /**
   * Tests edit mail without permission "userprotect.mail.edit".
   *
   * Tests if an user without the permission "userprotect.mail.edit" cannot
   * edit its own mail address.
   */
  public function testNoEditOwnMail() {
    // Create account that may NOT edit its own mail address.
    $account = $this->drupalCreateUser(['userprotect.account.edit']);
    $expected_mail = $account->getEmail();
    $this->drupalLogin($account);

    $this->drupalGet('user/' . $account->id() . '/edit');
    $this->assertSession()->fieldDisabled('mail');
  }

  /**
   * Tests edit password with permission "userprotect.pass.edit".
   *
   * Tests if an user with the permission "userprotect.pass.edit" can edit its
   * own password.
   */
  public function testEditOwnPass() {
    // Create account that may edit its own password.
    $account = $this->drupalCreateUser(['userprotect.pass.edit', 'userprotect.account.edit']);
    $this->drupalLogin($account);

    $new_pass = $this->randomMachineName();
    $edit = [
      'current_pass' => $account->pass_raw,
      'pass[pass1]' => $new_pass,
      'pass[pass2]' => $new_pass,
    ];
    $this->drupalPostForm('user/' . $account->id() . '/edit', $edit, t('Save'));

    // Assert the password changed.
    $account = $this->reloadEntity($account);
    $account->passRaw = $new_pass;
    $this->drupalLogout();
    $this->drupalLogin($account);
  }

  /**
   * Tests edit password without permission "userprotect.pass.edit".
   *
   * Tests if an user without the permission "userprotect.pass.edit" cannot
   * edit its own password.
   */
  public function testNoEditOwnPass() {
    // Create account that may NOT edit its own password.
    $account = $this->drupalCreateUser(['userprotect.account.edit']);
    $expected_pass = $account->pass_raw;
    $this->drupalLogin($account);

    $this->drupalGet('user/' . $account->id() . '/edit');
    $this->assertSession()->fieldNotExists('pass[pass1]');
    $this->assertSession()->fieldNotExists('pass[pass2]');
  }

  /**
   * Tests edit account with permission "userprotect.account.edit".
   *
   * Tests if an user with the permission "userprotect.account.edit" can edit
   * its own account.
   */
  public function testEditOwnAccount() {
    // Create an account that may edit its own account.
    $account = $this->drupalCreateUser(['userprotect.account.edit']);
    $this->drupalLogin($account);

    // Assert the user can edit its own account.
    $this->drupalGet('user/' . $account->id() . '/edit');
    $this->assertResponse(200, "The user may edit its own account.");
  }

  /**
   * Tests edit account without permission "userprotect.account.edit".
   *
   * Tests if an user without the permission "userprotect.account.edit" can
   * not edit its own account.
   */
  public function testNoEditOwnAccount() {
    // Create an account that may NOT edit its own account.
    $account = $this->drupalCreateUser();
    $this->drupalLogin($account);

    // Assert the user can edit its own account.
    $this->drupalGet('user/' . $account->id() . '/edit');
    $this->assertResponse(403, "The user may NOT edit its own account.");
  }

}
