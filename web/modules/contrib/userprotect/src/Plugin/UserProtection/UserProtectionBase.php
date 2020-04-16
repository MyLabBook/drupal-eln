<?php

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;

/**
 * Provides a base class for UserProtection plugins.
 */
abstract class UserProtectionBase extends PluginBase implements UserProtectionInterface {

  /**
   * The name of the module that owns this plugin.
   *
   * @var string
   */
  public $provider;

  /**
   * A boolean indicating whether this plugin is enabled.
   *
   * @var bool
   */
  public $status = FALSE;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->setConfiguration($configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function description() {
    return $this->pluginDefinition['description'];
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->pluginDefinition['weight'];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return [
      'id' => $this->getPluginId(),
      'provider' => $this->pluginDefinition['provider'],
      'status' => $this->status,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    if (isset($configuration['status'])) {
      $this->status = (bool) $configuration['status'];
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return [];
  }

  /**
   * Implements UserProtectionInterface::isEnabled().
   */
  public function isEnabled() {
    return (bool) $this->status;
  }

  /**
   * {@inheritdoc}
   */
  public function isProtected(UserInterface $user, $op, AccountInterface $account) {
    if ($op == $this->getPluginId()) {
      return TRUE;
    }
  }

  /**
   * Implements applyAccountFormProtection::isEnabled().
   *
   * By default, no protection is applied.
   */
  public function applyAccountFormProtection(array &$form, FormStateInterface $form_state) {
    return FALSE;
  }

}
