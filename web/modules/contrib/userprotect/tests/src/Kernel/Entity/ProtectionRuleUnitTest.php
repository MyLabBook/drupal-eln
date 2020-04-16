<?php

namespace Drupal\Tests\userprotect\Kernel\Entity;

use Drupal\user\Entity\User;
use Drupal\Core\Session\UserSession;
use Drupal\userprotect\Entity\ProtectionRule;
use Drupal\userprotect\Entity\ProtectionRuleInterface;
use Drupal\userprotect\Plugin\UserProtection\UserProtectionInterface;
use Drupal\userprotect\UserProtect;
use Drupal\userprotect\Plugin\UserProtection\UserProtectionPluginCollection;
use Drupal\KernelTests\KernelTestBase;

/**
 * Various unit tests for protection rules.
 *
 * @group userprotect
 */
class ProtectionRuleUnitTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['user', 'userprotect', 'field'];

  /**
   * The user protection plugin manager.
   *
   * @var \Drupal\userprotect\Plugin\UserProtection\UserProtectionManager
   */
  protected $manager;

  /**
   * The protection rule to test on.
   *
   * @var \Drupal\userprotect\Entity\ProtectionRuleInterface
   */
  protected $protectionRule;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->manager = UserProtect::pluginManager();
    $this->protectionRule = ProtectionRule::create([
      'name' => 'dummy',
      'label' => 'Dummy',
      'protections' => [
        'user_mail' => [
          'status' => TRUE,
        ],
      ],
      'protectedEntityTypeId' => 'user_role',
      'protectedEntityId' => 'administrator',
    ]);
  }

  /**
   * Tests id().
   */
  public function testId() {
    $this->assertIdentical('dummy', $this->protectionRule->id());
  }

  /**
   * Tests setProtectedEntityTypeId() and getProtectedEntityTypeId().
   */
  public function testProtectedEntityTypeId() {
    $this->assertIdentical('user_role', $this->protectionRule->getProtectedEntityTypeId());
    $entity_type = 'user';
    $this->assertInstanceOf(ProtectionRuleInterface::class, $this->protectionRule->setProtectedEntityTypeId($entity_type));
    $this->assertIdentical($entity_type, $this->protectionRule->getProtectedEntityTypeId());
  }

  /**
   * Tests setProtectedEntityId() and getProtectedEntityId().
   */
  public function testProtectedEntityId() {
    $this->assertIdentical('administrator', $this->protectionRule->getProtectedEntityId());
    $entity_id = 'authenticated';
    $this->assertInstanceOf(ProtectionRuleInterface::class, $this->protectionRule->setProtectedEntityId($entity_id));
    $this->assertIdentical($entity_id, $this->protectionRule->getProtectedEntityId());
  }

  /**
   * Tests setBypassRoles() and getBypassRoles().
   */
  public function testBypassRoles() {
    $this->assertIdentical([], $this->protectionRule->getBypassRoles());
    $roles = ['administrator'];
    $this->assertInstanceOf(ProtectionRuleInterface::class, $this->protectionRule->setBypassRoles($roles));
    $this->assertIdentical($roles, $this->protectionRule->getBypassRoles());
  }

  /**
   * Tests getProtection().
   */
  public function testGetProtection() {
    $this->assertInstanceOf(UserProtectionInterface::class, $this->protectionRule->getProtection('user_mail'));
  }

  /**
   * Tests getProtections().
   */
  public function testGetProtections() {
    $this->assertInstanceOf(UserProtectionPluginCollection::class, $this->protectionRule->getProtections());
  }

  /**
   * Tests enableProtection().
   */
  public function testEnableProtection() {
    $this->assertInstanceOf(ProtectionRuleInterface::class, $this->protectionRule->enableProtection('user_name'));
    $this->assertTrue($this->protectionRule->hasProtection('user_name'));
  }

  /**
   * Tests disableProtection().
   */
  public function testDisableProtection() {
    $this->assertInstanceOf(ProtectionRuleInterface::class, $this->protectionRule->disableProtection('user_mail'));
    $this->assertFalse($this->protectionRule->hasProtection('user_mail'));
  }

  /**
   * Tests toArray().
   */
  public function testToArray() {
    $array = $this->protectionRule->toArray();
    $this->assertIdentical('dummy', $array['name']);
    $this->assertIdentical('Dummy', $array['label']);
    $expected_protections = [
      'user_mail' => [
        'status' => TRUE,
      ],
    ];
    $this->assertIdentical($expected_protections, $array['protections']);
    $this->assertIdentical('user_role', $array['protectedEntityTypeId']);
    $this->assertIdentical('administrator', $array['protectedEntityId']);
  }

  /**
   * Tests getPermissionName().
   */
  public function testGetPermissionName() {
    $this->assertIdentical('userprotect.dummy.bypass', $this->protectionRule->getPermissionName());
  }

  /**
   * Tests appliesTo().
   */
  public function testAppliesTo() {
    // Create an user with administrator role.
    $values = [
      'uid' => 3,
      'name' => 'lorem',
      'roles' => [
        'administrator',
      ],
    ];
    $lorem = User::create($values);

    // Create an authenticated user.
    $values = [
      'uid' => 4,
      'name' => 'ipsum',
    ];
    $ipsum = User::create($values);

    // Assert that the protection rule applies to the user with the
    // administrator role and not to the authenticated user.
    $this->assertTrue($this->protectionRule->appliesTo($lorem));
    $this->assertFalse($this->protectionRule->appliesTo($ipsum));

    // Create an user based protection rule.
    $user_protection_rule = ProtectionRule::create([
      'name' => 'dummy',
      'label' => 'Dummy',
      'protections' => [
        'user_mail' => [
          'status' => TRUE,
        ],
      ],
      'protectedEntityTypeId' => 'user',
      'protectedEntityId' => 4,
    ]);

    // Assert that the protection rule applies to "ipsum", but no to "lorem".
    $this->assertFalse($user_protection_rule->appliesTo($lorem));
    $this->assertTrue($user_protection_rule->appliesTo($ipsum));
  }

  /**
   * Tests hasProtection().
   */
  public function testHasProtection() {
    // The protection rule was created with only the protection "user_mail"
    // enabled.
    $this->assertTrue($this->protectionRule->hasProtection('user_mail'));
    $this->assertFalse($this->protectionRule->hasProtection('user_name'));
    $this->assertFalse($this->protectionRule->hasProtection('non_existing_plugin_id'));
  }

  /**
   * Tests isProtected().
   */
  public function testIsProtected() {
    // Create an user with administrator role.
    $values = [
      'uid' => 3,
      'name' => 'lorem',
      'roles' => [
        'administrator',
      ],
    ];
    $lorem = User::create($values);

    // Create an authenticated user.
    $values = [
      'uid' => 4,
      'name' => 'ipsum',
    ];
    $ipsum = User::create($values);

    // Create an operating account.
    $account = new UserSession();

    // Assert that the operation is protected on the user with the administrator
    // role and not on the authenticated user.
    $this->assertTrue($this->protectionRule->isProtected($lorem, 'user_mail', $account));
    $this->assertFalse($this->protectionRule->isProtected($ipsum, 'user_mail', $account));
  }

}
