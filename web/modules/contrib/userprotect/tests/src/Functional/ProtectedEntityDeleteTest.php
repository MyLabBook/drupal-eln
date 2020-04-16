<?php

namespace Drupal\Tests\userprotect\Functional;

/**
 * Tests if protection rules are cleaned up upon entity deletion.
 *
 * @group userprotect
 */
class ProtectedEntityDeleteTest extends UserProtectBrowserTestBase {

  /**
   * Tests UI for non-existent protected users.
   *
   * Tests if there are no PHP errors in the UI when a protection rule for a
   * non-existent user still exists.
   */
  public function testNonExistentProtectedUser() {
    // Create a protection rule for a non-existent user.
    $fake_uid = 10;
    $protection_rule = $this->createProtectionRule($fake_uid, [], 'user');
    $protection_rule->save();

    // Check user interface.
    $account = $this->drupalCreateUser(['userprotect.administer']);
    $this->drupalLogin($account);
    $this->drupalGet('admin/config/people/userprotect');
    $this->assertSession()->pageTextContains('Missing');
  }

  /**
   * Tests UI for non-existent protected roles.
   *
   * Tests if there are no PHP errors in the UI when a protection rule for a
   * non-existent role still exists.
   */
  public function testNonExistentProtectedRole() {
    // Create a protection rule for a non-existent user.
    $protection_rule = $this->createProtectionRule('non-existent role', [], 'user_role');
    $protection_rule->save();

    // Check user interface.
    $account = $this->drupalCreateUser(['userprotect.administer']);
    $this->drupalLogin($account);
    $this->drupalGet('admin/config/people/userprotect');
    $this->assertSession()->pageTextContains('Missing');
  }

}
