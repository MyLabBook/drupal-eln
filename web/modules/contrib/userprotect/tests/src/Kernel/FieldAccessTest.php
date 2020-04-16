<?php

namespace Drupal\Tests\userprotect\Kernel;

/**
 * Tests field access for each UserProtection plugin that protects a field.
 *
 * @group userprotect
 */
class FieldAccessTest extends UserProtectKernelTestBase {

  /**
   * The user admin.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $userAdmin;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create an user who administer users. Explicitly set user ID to '2'
    // because user 1 would have all permissions.
    $perm = ['administer users', 'administer permissions'];
    $this->userAdmin = $this->drupalCreateUser($perm, NULL, FALSE, [
      'uid' => 2,
    ]);
  }

  /**
   * Tests field access for the user's name.
   */
  public function testNameAccess() {
    // Create an account with no protection. The logged in user should have the
    // privileges to edit this account's name.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->name->access('edit', $this->userAdmin), 'User admin can edit account name of non-protected user.');

    // Create a protected account. The logged in user should NOT have the
    // privileges to edit this account's name.
    $protected_account = $this->createProtectedUser(['user_name']);
    $this->assertFalse($protected_account->name->access('edit', $this->userAdmin), 'User admin cannot edit account name of protected user.');
  }

  /**
   * Tests field access for the user's mail address.
   */
  public function testMailAccess() {
    // Create an account with no protection. The logged in user should have the
    // privileges to edit this account's mail address.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->mail->access('edit', $this->userAdmin), 'User admin can edit mail address of non-protected user.');

    // Create a protected account. The logged in user should NOT have the
    // privileges to edit this account's mail address.
    $protected_account = $this->createProtectedUser(['user_mail']);
    $this->assertFalse($protected_account->mail->access('edit', $this->userAdmin), 'User admin cannot edit mail address of protected user.');
  }

  /**
   * Tests field access for the user's password.
   */
  public function testPassAccess() {
    // Create an account with no protection. The logged in user should have the
    // privileges to edit this account's password.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->pass->access('edit', $this->userAdmin), 'User admin can edit password of non-protected user.');

    // Create a protected account. The logged in user should NOT have the
    // privileges to edit this account's password.
    $protected_account = $this->createProtectedUser(['user_pass']);
    $this->assertFalse($protected_account->pass->access('edit', $this->userAdmin), 'User admin cannot edit password of protected user.');
  }

  /**
   * Tests field access for the user's status.
   */
  public function testStatusAccess() {
    // Create an account with no protection. The logged in user should have the
    // privileges to edit this account's status.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->status->access('edit', $this->userAdmin), 'User admin can edit status of non-protected user.');

    // Create a protected account. The logged in user should NOT have the
    // privileges to edit this account's status.
    $protected_account = $this->createProtectedUser(['user_status']);
    $this->assertFalse($protected_account->status->access('edit', $this->userAdmin), 'User admin cannot edit status of protected user.');
  }

  /**
   * Tests field access for the user's roles.
   */
  public function testRolesAccess() {
    // Create an account with no protection. The logged in user should have the
    // privileges to edit this account's roles.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->roles->access('edit', $this->userAdmin), 'User admin can edit roles of non-protected user.');

    // Create a protected account. The logged in user should NOT have the
    // privileges to edit this account's roles.
    $protected_account = $this->createProtectedUser(['user_roles']);
    $this->assertFalse($protected_account->roles->access('edit', $this->userAdmin), 'User admin cannot edit roles of protected user.');
  }

}
