<?php

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Core\Form\FormStateInterface;

/**
 * Protects user's password.
 *
 * @UserProtection(
 *   id = "user_pass",
 *   label = @Translation("Password"),
 *   weight = -8
 * )
 */
class Password extends UserProtectionBase {

  /**
   * {@inheritdoc}
   */
  public function applyAccountFormProtection(array &$form, FormStateInterface $form_state) {
    if (isset($form['account']['current_pass'])) {
      $form['account']['current_pass']['#access'] = FALSE;
    }

    // Since current_pass gets hidden, any constraints regarding the current
    // password needs to be removed or bypassed as well.
    // \Drupal\user\AccountForm::validate() uses the constraint
    // \Drupal\user\Plugin\Validation\Constraint\ProtectedUserFieldConstraintValidator
    // to determine if filling the current pass is required.
    // This constraint can be bypassed by setting
    // "_skipProtectedUserFieldConstraint" on the account to true. Since
    // \Drupal\user\AccountForm::validate() sets that value to the form state's
    // "user_pass_reset" and there seems to be no easy way to set
    // "_skipProtectedUserFieldConstraint" at a later time, "user_pass_reset" is
    // set to true here, even though the user might not have logged in via a
    // one-time link.
    $form_state->set('user_pass_reset', TRUE);

    if (isset($form['account']['pass'])) {
      $form['account']['pass']['#access'] = FALSE;
      return TRUE;
    }

    return FALSE;
  }

}
