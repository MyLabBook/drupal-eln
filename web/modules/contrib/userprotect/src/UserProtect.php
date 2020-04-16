<?php

namespace Drupal\userprotect;

/**
 * Provides wrappers for services.
 */
class UserProtect {

  /**
   * Returns the user protection plugin manager.
   *
   * @return \Drupal\userprotect\Plugin\UserProtection\UserProtectionManager
   *   An instance of UserProtectionManager.
   */
  public static function pluginManager() {
    return \Drupal::service('plugin.manager.userprotect.user_protection');
  }

}
