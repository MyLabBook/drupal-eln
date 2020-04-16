<?php

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Core\Form\FormStateInterface;

/**
 * Protects user's status.
 *
 * @UserProtection(
 *   id = "user_status",
 *   label = @Translation("Status"),
 *   weight = -7
 * )
 */
class Status extends UserProtectionBase {

  /**
   * {@inheritdoc}
   */
  public function applyAccountFormProtection(array &$form, FormStateInterface $form_state) {
    $build_info = $form_state->getBuildInfo();
    $account = $build_info['callback_object']->getEntity();
    if (isset($form['account']['status'])) {
      $form['account']['status']['#disabled'] = TRUE;
      $form['account']['status']['#value'] = $account->isActive();
      return TRUE;
    }
    return FALSE;
  }

}
