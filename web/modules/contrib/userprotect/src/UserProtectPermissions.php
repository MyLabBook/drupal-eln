<?php

namespace Drupal\userprotect;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides dynamic permissions for bypassing user protect rules.
 */
class UserProtectPermissions implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new UserProtectPermissions instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity_type.manager'));
  }

  /**
   * Returns an array of userprotect permissions.
   *
   * @return array
   *   An array of permissions to bypass protection rules.
   */
  public function permissions() {
    $permissions = [];
    // For each protection rule, create a permission to bypass the rule.
    /** @var \Drupal\userprotect\Entity\ProtectionRuleInterface[] $rules */
    $rules = $this->entityTypeManager->getStorage('userprotect_rule')->loadMultiple();
    uasort($rules, 'Drupal\Core\Config\Entity\ConfigEntityBase::sort');
    foreach ($rules as $rule) {
      $vars = [
        '%label' => $rule->label(),
      ];
      $permissions += [
        $rule->getPermissionName() => [
          'title' => $this->t('Bypass user protection for %label', $vars),
          'description' => $this->t('The user protection rule %label is ignored for users with this permission.', $vars),
        ],
      ];
    }
    return $permissions;
  }

}
