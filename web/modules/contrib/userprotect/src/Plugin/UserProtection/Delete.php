<?php

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;

/**
 * Protects the user from being deleted.
 *
 * @UserProtection(
 *   id = "user_delete",
 *   label = @Translation("Cancel operation"),
 *   weight = 2
 * )
 */
class Delete extends UserProtectionBase {

  /**
   * {@inheritdoc}
   */
  public function isProtected(UserInterface $user, $op, AccountInterface $account) {
    if ($op == 'delete') {
      return TRUE;
    }
    return parent::isProtected($user, $op, $account);
  }

}
