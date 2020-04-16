<?php

namespace Drupal\Tests\userprotect\Kernel;

/**
 * Tests if protection rules are cleaned up upon entity deletion.
 *
 * @group userprotect
 */
class ProtectedEntityDeleteTest extends UserProtectKernelTestBase {

  /**
   * Tests reaction upon user deletion.
   *
   * Tests if an user based protection rule is cleaned up when the protected
   * user is deleted.
   */
  public function testUserDelete() {
    // Create a user.
    $account = $this->drupalCreateUser();

    // Protect this user.
    $protection_rule = $this->createProtectionRule($account->id(), [], 'user');
    $protection_rule->save();

    // Assert that the rule was saved.
    $protection_rule = $this->reloadEntity($protection_rule);
    $this->assertNotNull($protection_rule, 'The protection rule was saved.');

    // Now delete the account.
    $account->delete();

    // Assert that the rule no longer exists.
    $protection_rule = $this->reloadEntity($protection_rule);
    $this->assertNull($protection_rule, 'The protection rule was deleted.');
  }

  /**
   * Tests reaction upon role deletion.
   *
   * Tests if a role based protection rule is cleaned up when the protected
   * role is deleted.
   */
  public function testRoleDelete() {
    // Create a role.
    $rid = $this->drupalCreateRole([]);

    // Protect this role.
    $protection_rule = $this->createProtectionRule($rid, [], 'user_role');
    $protection_rule->save();

    // Assert that the rule was saved.
    $protection_rule = $this->reloadEntity($protection_rule);
    $this->assertNotNull($protection_rule, 'The protection rule was saved.');

    // Now delete the role.
    $role = \Drupal::entityTypeManager()->getStorage('user_role')->load($rid);
    $role->delete();

    // Assert that the rule no longer exists.
    $protection_rule = $this->reloadEntity($protection_rule);
    $this->assertNull($protection_rule, 'The protection rule was deleted.');
  }

}
