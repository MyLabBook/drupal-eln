<?php

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;

/**
 * Protects the user from being edited.
 *
 * @UserProtection(
 *   id = "user_edit",
 *   label = @Translation("Edit operation"),
 *   weight = 1
 * )
 */
class Edit extends UserProtectionBase {

  /**
   * {@inheritdoc}
   */
  public function isProtected(UserInterface $user, $op, AccountInterface $account) {
    if ($op == 'update') {
      return TRUE;
    }
    return parent::isProtected($user, $op, $account);
  }

}
