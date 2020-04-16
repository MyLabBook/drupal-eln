<?php

namespace Drupal\userprotect\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;

/**
 * Provides an interface defining a userprotect_rule entity.
 */
interface ProtectionRuleInterface extends ConfigEntityInterface {

  /**
   * Gets the protected entity type id.
   *
   * @return string
   *   ID of the entity type that is protected.
   */
  public function getProtectedEntityTypeId();

  /**
   * Sets the protected entity type id.
   *
   * @param string $entity_type_id
   *   ID of the entity type that should be protected.
   *
   * @return \Drupal\userprotect\Entity\ProtectionRuleInterface
   *   The class instance this method is called on.
   *
   * @throws \InvalidArgumentException
   *   Thrown if $entity_type_id is invalid.
   */
  public function setProtectedEntityTypeId($entity_type_id);

  /**
   * Gets the protected entity.
   *
   * @return EntityInterface
   *   The loaded entity, if found.
   *   NULL otherwise.
   */
  public function getProtectedEntity();

  /**
   * Gets the protected entity id.
   *
   * @return string|int
   *   ID of the entity that is protected.
   */
  public function getProtectedEntityId();

  /**
   * Gets the protected entity id.
   *
   * @param string|int $entity_id
   *   ID of the entity that should be protected.
   *
   * @return \Drupal\userprotect\Entity\ProtectionRuleInterface
   *   The class instance this method is called on.
   */
  public function setProtectedEntityId($entity_id);

  /**
   * Returns a specific user protection.
   *
   * @param string $protection
   *   The user protection plugin ID.
   *
   * @return \Drupal\userprotect\Plugin\UserProtection\UserProtectionInterface
   *   The user protection object.
   */
  public function getProtection($protection);

  /**
   * Returns the user protections for this protection rule.
   *
   * @return \Drupal\userprotect\Plugin\UserProtection\UserProtectionPluginCollection|\Drupal\userprotect\Plugin\UserProtection\UserProtectionInterface[]
   *   The user protection plugin collection.
   */
  public function getProtections();

  /**
   * Sets the configuration for a user protection plugin instance.
   *
   * @param string $instance_id
   *   The ID of a user protection plugin to set the configuration for.
   * @param array $configuration
   *   The user protection plugin configuration to set.
   *
   * @return \Drupal\userprotect\Entity\ProtectionRuleInterface
   *   The called protection rule entity.
   */
  public function setPluginConfig($instance_id, array $configuration);

  /**
   * Enables a certain protection.
   *
   * @param string $instance_id
   *   The ID of a user protection plugin to enable.
   *
   * @return \Drupal\userprotect\Entity\ProtectionRuleInterface
   *   The called protection rule entity.
   */
  public function enableProtection($instance_id);

  /**
   * Disables a certain protection.
   *
   * @param string $instance_id
   *   The ID of a user protection plugin to disable.
   *
   * @return \Drupal\userprotect\Entity\ProtectionRuleInterface
   *   The called protection rule entity.
   */
  public function disableProtection($instance_id);

  /**
   * Returns a list of roles that may bypass this protection rule.
   *
   * @return array
   *   A list of role names.
   */
  public function getBypassRoles();

  /**
   * Sets the list of roles that may bypass this protection rule.
   *
   * @param array $roles
   *   The roles that may bypass this protection rule.
   *
   * @return \Drupal\userprotect\Entity\ProtectionRuleInterface
   *   The called protection rule entity.
   */
  public function setBypassRoles(array $roles);

  /**
   * Returns the name of the permission to bypass the protection rule.
   *
   * @return string
   *   The permission name.
   */
  public function getPermissionName();

  /**
   * Returns if this rule applies to the given user.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user to check.
   *
   * @return bool
   *   TRUE if this rule applies to the given user.
   *   FALSE otherwise.
   */
  public function appliesTo(UserInterface $user);

  /**
   * Returns if the given protection is enabled on this rule.
   *
   * @param string $protection
   *   The protection to check.
   *
   * @return bool
   *   TRUE if the protection is enabled.
   *   FALSE otherwise.
   */
  public function hasProtection($protection);

  /**
   * Checks if a given operation on an user should be protected.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user object to check access for.
   * @param string $op
   *   The operation that is to be performed on $user.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account trying to access the entity.
   *
   * @return bool
   *   TRUE if the operation should be protected.
   *   FALSE if the operation is not protected by this rule.
   */
  public function isProtected(UserInterface $user, $op, AccountInterface $account);

}
