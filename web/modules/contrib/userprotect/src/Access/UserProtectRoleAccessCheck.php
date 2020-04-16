<?php

namespace Drupal\userprotect\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;

/**
 * Class UserProtectRoleAccessCheck.
 *
 * @package Drupal\userprotect\Access
 */
class UserProtectRoleAccessCheck implements AccessInterface {

  /**
   * Custom access check for the /user/%/roles.
   *
   * This check will only occur when role_delegation is enabled.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   * @param \Drupal\user\UserInterface $user
   *   The user we are editing.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account, UserInterface $user) {
    $access_result = $user->access('user_roles', $account) ? AccessResult::allowed() : AccessResult::forbidden();
    return $access_result->cachePerUser()->addCacheableDependency($user);
  }

}
