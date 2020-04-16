<?php

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Core\Form\FormStateInterface;

/**
 * Protects user's roles.
 *
 * @UserProtection(
 *   id = "user_roles",
 *   label = @Translation("Roles"),
 *   weight = -6
 * )
 */
class Roles extends UserProtectionBase {

  /**
   * {@inheritdoc}
   */
  public function applyAccountFormProtection(array &$form, FormStateInterface $form_state) {
    // Make sure this module also works when role_delegation is enabled.
    $applied = FALSE;
    if (isset($form['role_change']['widget'])) {
      $form['role_change']['widget']['#disabled'] = TRUE;
      $applied = TRUE;
    }

    if (isset($form['account']['roles'])) {
      $form['account']['roles']['#disabled'] = TRUE;
      $applied = TRUE;
    }

    return $applied;
  }

}
