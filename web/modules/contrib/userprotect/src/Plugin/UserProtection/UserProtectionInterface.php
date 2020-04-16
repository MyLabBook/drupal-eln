<?php

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\DependentPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;

/**
 * Defines the interface for user protection plugins.
 */
interface UserProtectionInterface extends PluginInspectionInterface, ConfigurableInterface, DependentPluginInterface {

  /**
   * Returns the user protection label.
   *
   * @return string
   *   The user protection label.
   */
  public function label();

  /**
   * Returns the description of the protection.
   *
   * @return string
   *   The user protection description.
   */
  public function description();

  /**
   * Returns the weight of the user protection.
   *
   * @return int
   *   The protections' weight.
   */
  public function getWeight();

  /**
   * Returns if plugin is enabled.
   *
   * @return bool
   *   TRUE if the plugin is enabled.
   *   FALSE otherwise.
   */
  public function isEnabled();

  /**
   * Checks if a given operation on an user should be protected.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user object to check access for.
   * @param string $op
   *   The operation that is to be performed on $user.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account trying to access the entity.
   *
   * @return bool
   *   TRUE if the operation should be protected.
   *   FALSE if the operation is not protected by this plugin.
   */
  public function isProtected(UserInterface $user, $op, AccountInterface $account);

  /**
   * Applies protections to user account form.
   *
   * @param array $form
   *   Nested array of form elements that comprise the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return bool
   *   TRUE if the protection was applied.
   *   FALSE otherwise.
   */
  public function applyAccountFormProtection(array &$form, FormStateInterface $form_state);

}
