<?php

namespace Drupal\Tests\userprotect\Traits;

use Drupal\userprotect\Entity\ProtectionRule;
use Drupal\userprotect\Entity\ProtectionRuleInterface;

/**
 * Provides methods to create protection rules.
 *
 * This trait is meant to be used only by test classes.
 */
trait UserProtectCreationTrait {

  /**
   * Creates a protected role.
   *
   * @param array $protections
   *   (optional) The active protections.
   *   Defaults to an empty array.
   *
   * @return string
   *   The ID of the created role.
   */
  protected function createProtectedRole(array $protections = []) {
    // Create a role.
    $rid = $this->drupalCreateRole([]);

    // Protect this role.
    $protection_rule = $this->createProtectionRule($rid, $protections);
    $protection_rule->save();
    // Reset available permissions.
    drupal_static_reset('checkPermissions');

    return $rid;
  }

  /**
   * Creates a protected user.
   *
   * @param array $protections
   *   (optional) The active protections.
   *   Defaults to an empty array.
   *
   * @return object
   *   The created user.
   */
  protected function createProtectedUser(array $protections = []) {
    // Create a user.
    $account = $this->drupalCreateUser();

    // Protect this user.
    $protection_rule = $this->createProtectionRule($account->id(), $protections, 'user');
    $protection_rule->save();
    // Reset available permissions.
    drupal_static_reset('checkPermissions');

    return $account;
  }

  /**
   * Creates an user with a protected role.
   *
   * @param array $protections
   *   (optional) The active protections.
   *   Defaults to an empty array.
   *
   * @return object
   *   The created user.
   */
  protected function createUserWithProtectedRole(array $protections = []) {
    // Create a protected role.
    $rid = $this->createProtectedRole($protections);

    // Create an account with this protected role.
    $protected_account = $this->drupalCreateUser();
    $protected_account->addRole($rid);
    $protected_account->save();

    return $protected_account;
  }

  /**
   * Creates protection rule.
   *
   * @param int|string $entity_id
   *   The id of the entity to protect.
   * @param array $protections
   *   (optional) The active protections.
   *   Defaults to an empty array.
   * @param string $entity_type
   *   (optional) The protected entity type.
   *   Defaults to "user_role".
   * @param array $values
   *   (optional) Extra values of the protection rule.
   *
   * @return \Drupal\userprotect\Entity\ProtectionRuleInterface
   *   An instance of ProtectionRuleInterface.
   */
  protected function createProtectionRule($entity_id, array $protections = [], $entity_type = 'user_role', array $values = []) {
    // Setup default values.
    $values += [
      'name' => 'dummy',
      'label' => 'Dummy',
      'protections' => [],
      'protectedEntityTypeId' => $entity_type,
      'protectedEntityId' => $entity_id,
    ];
    // Define protections.
    foreach ($protections as $key) {
      $values['protections'][$key] = [
        'status' => TRUE,
      ];
    }

    // Create protection rule.
    $protection_rule = ProtectionRule::create($values);
    $this->assertInstanceOf(ProtectionRuleInterface::class, $protection_rule);
    return $protection_rule;
  }

}
