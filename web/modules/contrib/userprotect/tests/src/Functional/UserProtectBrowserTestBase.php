<?php

namespace Drupal\Tests\userprotect\Functional;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\userprotect\Traits\UserProtectCreationTrait;

/**
 * Provides a base class for User Protect functional tests.
 */
abstract class UserProtectBrowserTestBase extends BrowserTestBase {

  use UserProtectCreationTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = ['userprotect', 'userprotect_test'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Reloads an entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to reload.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The reloaded entity.
   */
  protected function reloadEntity(EntityInterface $entity) {
    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = \Drupal::entityTypeManager()->getStorage($entity->getEntityTypeId());
    $storage->resetCache([$entity->id()]);
    return $storage->load($entity->id());
  }

}
