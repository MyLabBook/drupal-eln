<?php

namespace Drupal\Tests\userprotect\Kernel;

/**
 * Tests bypassing protection rules.
 *
 * @group userprotect
 * @todo add bypass test for 'all' protection rules.
 */
class ProtectionRuleBypassTest extends UserProtectKernelTestBase {

  /**
   * The user access controller.
   *
   * @var \Drupal\Core\Entity\EntityAccessControllerInterface
   */
  protected $accessController;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->accessController = \Drupal::entityTypeManager()->getAccessControlHandler('user');
  }

  /**
   * Tests if bypassing role name edit protection is respected.
   */
  public function testRoleNameEditProtectionBypass() {
    $this->doRoleProtectionBypassTest('user_name', 'user_name');
  }

  /**
   * Tests if bypassing role mail edit protection is respected.
   */
  public function testRoleMailEditProtectionBypass() {
    $this->doRoleProtectionBypassTest('user_mail', 'user_mail');
  }

  /**
   * Tests if bypassing role password edit protection is respected.
   */
  public function testRolePassEditProtectionBypass() {
    $this->doRoleProtectionBypassTest('user_pass', 'user_pass');
  }

  /**
   * Tests if bypassing role edit protection is respected.
   */
  public function testRoleEditProtectionBypass() {
    $this->doRoleProtectionBypassTest('user_edit', 'update');
  }

  /**
   * Tests if bypassing role delete protection is respected.
   */
  public function testRoleDeleteProtectionBypass() {
    $this->doRoleProtectionBypassTest('user_delete', 'delete');
  }

  /**
   * Tests if bypassing a certain role protection is respected.
   *
   * @param string $plugin
   *   The name of the UserProtection plugin.
   * @param string $operation
   *   The access operation to check.
   */
  protected function doRoleProtectionBypassTest($plugin, $operation) {
    // Create a protected role.
    $rid = $this->createProtectedRole([$plugin]);

    // Create an account with this protected role.
    $protected_account = $this->drupalCreateUser();
    $protected_account->addRole($rid);

    // Create operating account.
    $account = $this->drupalCreateUser(['administer users', 'userprotect.dummy.bypass']);

    // Test if account has the expected access.
    $this->assertTrue($this->accessController->access($protected_account, $operation, $account));
  }

  /**
   * Tests if bypassing user name edit protection is respected.
   */
  public function testUserNameEditProtectionBypass() {
    $this->doUserProtectionBypassTest('user_name', 'user_name');
  }

  /**
   * Tests if bypassing user mail edit protection is respected.
   */
  public function testUserMailEditProtectionBypass() {
    $this->doUserProtectionBypassTest('user_mail', 'user_mail');
  }

  /**
   * Tests if bypassing user password edit protection is respected.
   */
  public function testUserPassEditProtectionBypass() {
    $this->doUserProtectionBypassTest('user_pass', 'user_pass');
  }

  /**
   * Tests if bypassing user edit protection is respected.
   */
  public function testUserEditProtectionBypass() {
    $this->doUserProtectionBypassTest('user_edit', 'update');
  }

  /**
   * Tests if bypassing user delete protection is respected.
   */
  public function testUserDeleteProtectionBypass() {
    $this->doUserProtectionBypassTest('user_delete', 'delete');
  }

  /**
   * Tests if bypassing a certain user protection is respected.
   *
   * @param string $plugin
   *   The name of the UserProtection plugin.
   * @param string $operation
   *   The access operation to check.
   */
  protected function doUserProtectionBypassTest($plugin, $operation) {
    // Create a protected user.
    $protected_account = $this->createProtectedUser([$plugin]);

    // Create operating account.
    $account = $this->drupalCreateUser(['administer users', 'userprotect.dummy.bypass']);

    // Test if account has the expected access.
    $this->assertTrue($this->accessController->access($protected_account, $operation, $account));
  }

}
