<?php

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Core\Form\FormStateInterface;

/**
 * Protects user's mail address.
 *
 * @UserProtection(
 *   id = "user_mail",
 *   label = @Translation("E-mail address"),
 *   weight = -9
 * )
 */
class Mail extends UserProtectionBase {

  /**
   * {@inheritdoc}
   */
  public function applyAccountFormProtection(array &$form, FormStateInterface $form_state) {
    $build_info = $form_state->getBuildInfo();
    $account = $build_info['callback_object']->getEntity();
    // If for some reason the account has no mail, then don't protect it.
    if ($account->getEmail() && isset($form['account']['mail'])) {
      $form['account']['mail']['#disabled'] = TRUE;
      $form['account']['mail']['#value'] = $account->getEmail();
      return TRUE;
    }
    return FALSE;
  }

}
