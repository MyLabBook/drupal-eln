<?php

namespace Drupal\Tests\userprotect\Functional;

/**
 * Tests each UserProtection plugin in action.
 *
 * @group userprotect
 * @todo Assert protection messages.
 */
class UserProtectionTest extends UserProtectBrowserTestBase {

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
   * Tests if the user's name field has the expected protection.
   */
  public function testNameProtection() {
    $protected_account = $this->createProtectedUser(['user_name']);

    $this->drupalGet('user/' . $protected_account->id() . '/edit');
    $this->assertSession()->fieldNotExists('name');
  }

  /**
   * Tests if the user's mail address field has the expected protection.
   */
  public function testMailProtection() {
    $protected_account = $this->createProtectedUser(['user_mail']);

    $this->drupalGet('user/' . $protected_account->id() . '/edit');
    $this->assertSession()->fieldDisabled('mail');
  }

  /**
   * Tests if the user's password field has the expected protection.
   */
  public function testPassProtection() {
    $protected_account = $this->createProtectedUser(['user_pass']);

    $this->drupalGet('user/' . $protected_account->id() . '/edit');
    $this->assertSession()->fieldNotExists('pass[pass1]');
    $this->assertSession()->fieldNotExists('pass[pass2]');
  }

  /**
   * Tests if the user's status field has the expected protection.
   */
  public function testStatusProtection() {
    $protected_account = $this->createProtectedUser(['user_status']);

    $this->drupalGet('user/' . $protected_account->id() . '/edit');
    $this->assertSession()->fieldNotExists('status');
  }

  /**
   * Tests if the user's roles field has the expected protection.
   */
  public function testRolesProtection() {
    $protected_account = $this->createProtectedUser(['user_roles']);

    // Add a role to the protected account.
    $rid1 = $this->drupalCreateRole([]);
    $protected_account->addRole($rid1);
    $protected_account->save();

    // Add another role. We try to add this role to the user form later.
    $rid2 = $this->drupalCreateRole([]);

    // Reload the user and check its roles.
    $protected_account = $this->reloadEntity($protected_account);
    // Assert the protected account's roles.
    $this->assertTrue($protected_account->hasRole($rid1));
    $this->assertFalse($protected_account->hasRole($rid2));

    // Ensure a checkbox for the second role is not available.
    $this->assertSession()->fieldNotExists(sprintf('roles[%s]', $rid2));
  }

}
