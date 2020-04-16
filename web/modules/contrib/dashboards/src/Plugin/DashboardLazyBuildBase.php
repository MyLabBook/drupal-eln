<?php

namespace Drupal\dashboards\Plugin;

use Drupal\Component\Serialization\Json;

/**
 * Abstract class helper for lazy builds.
 */
abstract class DashboardLazyBuildBase extends DashboardBase implements DashboardLazyBuildInterface {

  /**
   * {@inheritdoc}
   */
  public static function lazyBuildPreRender(string $pluginId, string $configuration): array {
    $configuration = Json::decode($configuration);
    $plugin = \Drupal::service('plugin.manager.dashboard')->createInstance($pluginId, $configuration);
    return static::lazyBuild($plugin, $configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function buildRenderArray($configuration): array {
    return [
      '#lazy_builder' => [
        static::class . '::lazyBuildPreRender',
        [
          $this->getPluginId(),
          Json::encode($configuration),
        ],
      ],
      '#create_placeholder' => TRUE,
    ];
  }

}
