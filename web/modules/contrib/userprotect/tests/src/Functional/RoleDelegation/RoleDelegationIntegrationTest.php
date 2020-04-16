<?php

namespace Drupal\Tests\userprotect\Functional\RoleDelegation;

use Drupal\Tests\BrowserTestBase;
use Drupal\userprotect\Entity\ProtectionRule;

/**
 * Functional tests for integration with role_delegation.
 *
 * @group userprotect
 */
class RoleDelegationIntegrationTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['userprotect', 'user', 'role_delegation'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Role ID.
   *
   * @var string
   */
  protected $rid1;

  /**
   * Role ID.
   *
   * @var string
   */
  protected $rid2;

  /**
   * An admin user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * User with 'assign [role] role' permission'.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $roleDelegatedAdminUser;

  /**
   * User with 'administer permissions' permission.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $regularRolesAdminUser;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $admin_role = $this->createAdminRole();
    $this->adminUser = $this->createUser();
    $this->adminUser->addRole($admin_role);
    $this->adminUser->save();

    $this->rid1 = $this->drupalCreateRole([]);
    $this->rid2 = $this->drupalCreateRole([]);

    $this->roleDelegatedAdminUser = $this->drupalCreateUser([
      'administer users',
      sprintf('assign %s role', $this->rid1),
      sprintf('assign %s role', $this->rid2),
    ]);

    $this->regularRolesAdminUser = $this->drupalCreateUser([
      'administer users',
      'administer permissions',
    ]);

    // Create a protection rule to protect users with the admin role.
    ProtectionRule::create([
      'name' => 'protect_admin_role',
      'label' => 'Protect admin role',
      'protections' => [
        'user_roles' => [
          'status' => TRUE,
        ],
      ],
      'protectedEntityTypeId' => 'user_role',
      'protectedEntityId' => $admin_role,
    ])->save();
  }

  /**
   * Ensure the roles element is only accessible for the right users.
   */
  public function testUserEditPage() {
    // Login as the delegated admin user. This user has permission to assign
    // roles 1 and 2 to users.
    $this->drupalLogin($this->roleDelegatedAdminUser);

    // Check if the delegated admin user can edit roles 1 and 2 on its own
    // account page. Also check if the roles are presented by Role Delegation,
    // not by Drupal core.
    $this->drupalGet(sprintf('/user/%s/edit', $this->roleDelegatedAdminUser->id()));
    $this->assertSession()->fieldNotExists(sprintf('roles[%s]', $this->rid1));
    $this->assertSession()->fieldExists(sprintf('role_change[%s]', $this->rid1));
    $this->assertSession()->fieldNotExists(sprintf('roles[%s]', $this->rid2));
    $this->assertSession()->fieldExists(sprintf('role_change[%s]', $this->rid2));

    // The admin user, having the admin role, has role protection. Ensure that
    // the delegated admin user cannot edit its roles.
    $this->drupalGet(sprintf('/user/%s/edit', $this->adminUser->id()));
    $this->assertSession()->fieldNotExists(sprintf('roles[%s]', $this->rid1));
    $this->assertSession()->fieldNotExists(sprintf('role_change[%s]', $this->rid1));
    $this->assertSession()->fieldNotExists(sprintf('roles[%s]', $this->rid2));
    $this->assertSession()->fieldNotExists(sprintf('role_change[%s]', $this->rid2));

    // Login as roles admin user. This user has permission to assign all roles.
    $this->drupalLogin($this->regularRolesAdminUser);

    // The delegated admin user does not have a protected role. Check if the
    // roles admin user may edit its roles. Since the roles admin user has the
    // specific Drupal core permission for assigning roles - 'administer
    // permissions' - the roles should be presented by Drupal Core, not Role
    // Delegation.
    $this->drupalGet(sprintf('/user/%s/edit', $this->roleDelegatedAdminUser->id()));
    $this->assertSession()->fieldExists(sprintf('roles[%s]', $this->rid1));
    $this->assertSession()->fieldNotExists(sprintf('role_change[%s]', $this->rid1));
    $this->assertSession()->fieldExists(sprintf('roles[%s]', $this->rid2));
    $this->assertSession()->fieldNotExists(sprintf('role_change[%s]', $this->rid2));

    // Since the admin user has role protection, ensure that the roles admin
    // user cannot edit its roles.
    $this->drupalGet(sprintf('/user/%s/edit', $this->adminUser->id()));
    $this->assertSession()->fieldNotExists(sprintf('role_change[%s]', $this->rid1));
    $this->assertSession()->elementAttributeContains('css', sprintf('[name="roles[%s]"]', $this->rid1), 'disabled', 'disabled');
    $this->assertSession()->fieldNotExists(sprintf('role_change[%s]', $this->rid2));
    $this->assertSession()->elementAttributeContains('css', sprintf('[name="roles[%s]"]', $this->rid2), 'disabled', 'disabled');

    // Login as an user with the admin role. This user has all privileges.
    $this->drupalLogin($this->adminUser);

    // Ensure the admin user can edit the roles of the delegated admin user.
    $this->drupalGet(sprintf('/user/%s/edit', $this->roleDelegatedAdminUser->id()));
    $this->assertSession()->fieldExists(sprintf('roles[%s]', $this->rid1));
    $this->assertSession()->fieldNotExists(sprintf('role_change[%s]', $this->rid1));
    $this->assertSession()->fieldExists(sprintf('roles[%s]', $this->rid2));
    $this->assertSession()->fieldNotExists(sprintf('role_change[%s]', $this->rid2));

    // Ensure the admin user can edit its own roles.
    $this->drupalGet(sprintf('/user/%s/edit', $this->adminUser->id()));
    $this->assertSession()->fieldExists(sprintf('roles[%s]', $this->rid1));
    $this->assertSession()->fieldNotExists(sprintf('role_change[%s]', $this->rid1));
    $this->assertSession()->fieldExists(sprintf('roles[%s]', $this->rid2));
    $this->assertSession()->fieldNotExists(sprintf('role_change[%s]', $this->rid2));
  }

  /**
   * Test that user protect rules are also enabled on /user/%user/roles.
   */
  public function testRolesPage() {
    // Ensure that an anonymous user cannot access teh user protect settings
    // page.
    $this->drupalGet('admin/config/people/userprotect/manage/protect_admin_role');

    // Login as the delegated admin user. This user has permission to assign
    // roles 1 and 2 to users.
    $this->drupalLogin($this->roleDelegatedAdminUser);

    // Ensure that the delegated admin user can access its own roles edit page.
    $this->drupalGet(sprintf('/user/%s/roles', $this->roleDelegatedAdminUser->id()));
    $this->assertSession()->statusCodeEquals(200);

    // Ensure that the delegated admin user cannot access the roles edit page of
    // the admin user, since that user has a protected role.
    $this->drupalGet(sprintf('/user/%s/roles', $this->adminUser->id()));
    $this->assertSession()->statusCodeEquals(403);

    // Login as roles admin user. This user has permission to assign all roles.
    $this->drupalLogin($this->regularRolesAdminUser);

    // Ensure that the roles admin user cannot access any roles edit pages, as
    // that requires specific Roles Delegation permissions.
    $this->drupalGet(sprintf('/user/%s/roles', $this->roleDelegatedAdminUser->id()));
    $this->assertSession()->statusCodeEquals(403);

    $this->drupalGet(sprintf('/user/%s/roles', $this->adminUser->id()));
    $this->assertSession()->statusCodeEquals(403);

    // Login as an user with the admin role. This user has all privileges.
    $this->drupalLogin($this->adminUser);

    // Ensure the admin user can access the roles edit page of all users.
    $this->drupalGet(sprintf('/user/%s/roles', $this->roleDelegatedAdminUser->id()));
    $this->assertSession()->statusCodeEquals(200);

    $this->drupalGet(sprintf('/user/%s/roles', $this->adminUser->id()));
    $this->assertSession()->statusCodeEquals(200);
  }

}
