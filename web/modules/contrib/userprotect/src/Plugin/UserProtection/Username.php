<?php

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Core\Form\FormStateInterface;

/**
 * Protects user's name.
 *
 * @UserProtection(
 *   id = "user_name",
 *   label = @Translation("Username"),
 *   weight = -10
 * )
 */
class Username extends UserProtectionBase {

  /**
   * {@inheritdoc}
   */
  public function applyAccountFormProtection(array &$form, FormStateInterface $form_state) {
    $build_info = $form_state->getBuildInfo();
    $account = $build_info['callback_object']->getEntity();
    // If for some reason the account has no username, then don't protect it.
    if ($account->getAccountName() && isset($form['account']['name'])) {
      $form['account']['name']['#disabled'] = TRUE;
      $form['account']['name']['#value'] = $account->getAccountName();
      return TRUE;
    }
    return FALSE;
  }

}
