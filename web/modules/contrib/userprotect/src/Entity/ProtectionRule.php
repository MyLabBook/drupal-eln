<?php

namespace Drupal\userprotect\Entity;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;
use Drupal\userprotect\UserProtect;
use Drupal\userprotect\Plugin\UserProtection\UserProtectionPluginCollection;

/**
 * Defines the Protection rule entity.
 *
 * @ConfigEntityType(
 *   id = "userprotect_rule",
 *   label = @Translation("Protection rule"),
 *   handlers = {
 *     "storage" = "Drupal\Core\Config\Entity\ConfigEntityStorage",
 *     "list_builder" = "Drupal\userprotect\Controller\ProtectionRuleListBuilder",
 *     "form" = {
 *       "add" = "Drupal\userprotect\Form\ProtectionRuleAddForm",
 *       "edit" = "Drupal\userprotect\Form\ProtectionRuleEditForm",
 *       "delete" = "Drupal\userprotect\Form\ProtectionRuleDeleteForm"
 *     },
 *   },
 *   admin_permission = "userprotect.administer",
 *   config_prefix = "rule",
 *   entity_keys = {
 *     "id" = "name",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "name",
 *     "label",
 *     "uuid",
 *     "protectedEntityTypeId",
 *     "protectedEntityId",
 *     "protections"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/people/userprotect/manage/{userprotect_rule}",
 *     "delete-form" = "/admin/config/people/userprotect/manage/{userprotect_rule}/delete"
 *   }
 * )
 */
class ProtectionRule extends ConfigEntityBase implements ProtectionRuleInterface, EntityWithPluginCollectionInterface {
  /**
   * The name of the protection rule.
   *
   * @var string
   */
  public $name;

  /**
   * The protection rule label.
   *
   * @var string
   */
  public $label;

  /**
   * The protection rule UUID.
   *
   * @var string
   */
  public $uuid;

  /**
   * The entity type the protection applies for.
   *
   * Can be "user" or "user_role".
   *
   * @var string
   */
  protected $protectedEntityTypeId = 'user_role';

  /**
   * The entity ID the protection applies for.
   *
   * @var string|int
   *   The identifier of the protected entity.
   */
  protected $protectedEntityId;

  /**
   * The elements that are protected by this rule.
   *
   * @var array
   */
  protected $protections = [];

  /**
   * Holds the collection of protections that are used by this protection rule.
   *
   * @var \Drupal\userprotect\Plugin\UserProtection\UserProtectionPluginCollection
   */
  protected $protectionsCollection;

  /**
   * {@inheritdoc}
   */
  protected $pluginConfigKey = 'protections';

  /**
   * List of user role IDs to that may bypass this protection rule.
   *
   * This property is saved as user permissions.
   *
   * @var array
   */
  protected $bypassRoles = [];

  /**
   * Overrides Drupal\Core\Entity\Entity::id().
   */
  public function id() {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getProtectedEntityTypeId() {
    return $this->protectedEntityTypeId;
  }

  /**
   * {@inheritdoc}
   */
  public function setProtectedEntityTypeId($entity_type_id) {
    // Check if given entity type exists. An InvalidArgumentException will be
    // thrown if not.
    \Drupal::entityTypeManager()->getDefinition($entity_type_id, TRUE);

    $this->protectedEntityTypeId = $entity_type_id;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProtectedEntity() {
    if ($this->getProtectedEntityId()) {
      return \Drupal::entityTypeManager()->getStorage($this->getProtectedEntityTypeId())->load($this->getProtectedEntityId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getProtectedEntityId() {
    return $this->protectedEntityId;
  }

  /**
   * {@inheritdoc}
   */
  public function setProtectedEntityId($entity_id) {
    $this->protectedEntityId = $entity_id;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProtection($protection) {
    return $this->getProtections()->get($protection);
  }

  /**
   * {@inheritdoc}
   */
  public function getProtections() {
    if (!isset($this->protectionsCollection)) {
      $this->protectionsCollection = new UserProtectionPluginCollection(UserProtect::pluginManager(), $this->protections);
    }
    return $this->protectionsCollection;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return ['protections' => $this->getProtections()];
  }

  /**
   * {@inheritdoc}
   */
  public function setPluginConfig($instance_id, array $configuration) {
    $this->protections[$instance_id] = $configuration;
    if (isset($this->protectionsCollection)) {
      $this->protectionsCollection->setInstanceConfiguration($instance_id, $configuration);
      $this->protectionsCollection->sort();
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function enableProtection($instance_id) {
    $this->setPluginConfig($instance_id, ['status' => TRUE]);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function disableProtection($instance_id) {
    $this->setPluginConfig($instance_id, ['status' => FALSE]);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    $properties = parent::toArray();
    $names = [
      'protections',
      'protectedEntityTypeId',
      'protectedEntityId',
    ];
    foreach ($names as $name) {
      $properties[$name] = $this->get($name);
    }
    return $properties;
  }

  /**
   * Overrides ConfigEntityBase::calculateDependencies().
   */
  public function calculateDependencies() {
    parent::calculateDependencies();

    // Always add a dependency on the user module.
    $this->addDependency('module', 'user');

    // Add a dependency on an user role in case this protection rule protects
    // an user role.
    $protected_entity = $this->getProtectedEntity();
    if ($protected_entity instanceof ConfigEntityInterface) {
      $this->addDependency('config', $protected_entity->getConfigDependencyName());
    }

    return $this->dependencies;
  }

  /**
   * {@inheritdoc}
   */
  protected function calculatePluginDependencies(PluginInspectionInterface $instance) {
    // Only add dependencies for plugins that are enabled.
    // @see \Drupal\userprotect\Plugin\UserProtection\UserProtectionPluginCollection::getConfiguration()
    if (isset($this->protections[$instance->getPluginId()])) {
      parent::calculatePluginDependencies($instance);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getBypassRoles() {
    return $this->bypassRoles;
  }

  /**
   * {@inheritdoc}
   */
  public function setBypassRoles(array $roles) {
    $this->bypassRoles = $roles;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function postLoad(EntityStorageInterface $storage_controller, array &$entities) {
    parent::postLoad($storage_controller, $entities);
    foreach ($entities as $entity) {
      $permission = $entity->getPermissionName();
      if ($permission) {
        $roles = array_keys(user_role_names(FALSE, $permission));
        $entity->setBypassRoles($roles);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage_controller, $update = TRUE) {
    parent::postSave($storage_controller, $update);

    // Set bypass permissions.
    $roles = $this->getBypassRoles();
    $permission = $this->getPermissionName();
    if ($roles && $permission) {
      foreach (user_roles() as $rid => $name) {
        $enabled = in_array($rid, $roles, TRUE);
        user_role_change_permissions($rid, [$permission => $enabled]);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getPermissionName() {
    return 'userprotect.' . $this->id() . '.bypass';
  }

  /**
   * {@inheritdoc}
   */
  public function appliesTo(UserInterface $user) {
    switch ($this->protectedEntityTypeId) {
      case 'user':
        return ($this->protectedEntityId == $user->id());

      case 'user_role':
        return $user->hasRole($this->protectedEntityId);
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function hasProtection($protection) {
    if ($this->getProtections()->has($protection)) {
      return $this->getProtection($protection)->isEnabled();
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isProtected(UserInterface $user, $op, AccountInterface $account) {
    // First check if this protection rule is applyable to the given user.
    if (!$this->appliesTo($user)) {
      // Not applyable. The operation is not protected by this rule.
      return FALSE;
    }

    // Check if the asked operation is equal to a protection plugin name
    // and if so, check if that protection plugin is enabled for this
    // rule.
    if ($this->hasProtection($op)) {
      // Protection enabled. The operation is protected by this rule.
      return TRUE;
    }

    foreach ($this->getProtections() as $protection) {
      if ($protection->isEnabled()) {
        if ($protection->isProtected($user, $op, $account)) {
          // The plugin says the operation is not permitted.
          return TRUE;
        }
      }
    }

    // In all other cases, the operation is not protected by this rule.
    return FALSE;
  }

}
