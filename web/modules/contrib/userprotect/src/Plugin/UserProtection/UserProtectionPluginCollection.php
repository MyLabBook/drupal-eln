<?php

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Plugin\DefaultLazyPluginCollection;

/**
 * A collection of protection rules.
 */
class UserProtectionPluginCollection extends DefaultLazyPluginCollection {

  /**
   * All possible user protection plugin IDs.
   *
   * @var array
   */
  protected $definitions;

  /**
   * Retrieves all user protection plugin instances.
   *
   * @return array
   *   An array of user protection plugin instances.
   */
  public function getAll() {
    // Retrieve all available user protection plugin definitions.
    if (!$this->definitions) {
      $this->definitions = $this->manager->getDefinitions();
    }

    // Ensure that there is an instance of all available plugins.
    foreach ($this->definitions as $plugin_id => $definition) {
      if (!isset($this->pluginInstances[$plugin_id])) {
        $this->initializePlugin($plugin_id);
      }
    }

    // Sort plugins.
    uasort($this->pluginInstances, [$this, 'pluginInstancesSort']);

    return $this->pluginInstances;
  }

  /**
   * Retrieves enabled user protection plugin instances.
   *
   * @return array
   *   An array of active user protection plugin instances.
   */
  public function getEnabledPlugins() {
    $instances = $this->getAll();
    $enabled = [];
    foreach ($this->configurations as $instance_id => $configuration) {
      if ($configuration['status']) {
        $enabled[] = $instances[$instance_id];
      }
    }

    // Sort plugins.
    uasort($enabled, [$this, 'pluginInstancesSort']);

    return $enabled;
  }

  /**
   * {@inheritdoc}
   */
  protected function initializePlugin($instance_id) {
    $configuration = $this->manager->getDefinition($instance_id);
    // Merge the actual configuration into the default configuration.
    if (isset($this->configurations[$instance_id])) {
      $configuration = NestedArray::mergeDeep($configuration, $this->configurations[$instance_id]);
    }
    $this->configurations[$instance_id] = $configuration;
    parent::initializePlugin($instance_id);
  }

  /**
   * Sorts plugin instances based on weight, label, provider or id.
   *
   * @param \Drupal\userprotect\Plugin\UserProtection\UserProtectionInterface $a
   *   The first plugin in the comparison.
   * @param \Drupal\userprotect\Plugin\UserProtection\UserProtectionInterface $b
   *   The second plugin in the comparison.
   *
   * @return int
   *   -1 if $a should go first.
   *   1 if $b should go first.
   *   0 if it's unknown which should go first.
   */
  public function pluginInstancesSort(UserProtectionInterface $a, UserProtectionInterface $b) {
    if ($a->getWeight() != $b->getWeight()) {
      return $a->getWeight() < $b->getWeight() ? -1 : 1;
    }
    if ($a->label() != $b->label()) {
      return strnatcasecmp($a->label(), $b->label());
    }
    if ($a->provider != $b->provider) {
      return strnatcasecmp($a->provider, $b->provider);
    }
    return strnatcasecmp($a->getPluginId(), $b->getPluginId());
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    $configuration = parent::getConfiguration();
    // Remove disabled protections.
    foreach ($configuration as $instance_id => $instance_config) {
      if (empty($instance_config['status'])) {
        unset($configuration[$instance_id]);
      }
    }
    return $configuration;
  }

}
