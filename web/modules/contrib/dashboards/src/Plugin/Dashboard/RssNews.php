<?php

namespace Drupal\dashboards\Plugin\Dashboard;

use Drupal\Core\Url;
use Zend\Feed\Reader\Reader;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dashboards\Plugin\DashboardBase;
use Drupal\dashboards\Plugin\DashboardLazyBuildBase;

/**
 * Show account info.
 *
 * @Dashboard(
 *   id = "rss_news",
 *   label = @Translation("Show rss news"),
 *   category = @Translation("Informations")
 * )
 */
class RssNews extends DashboardLazyBuildBase {

  /**
   * Default cache time.
   *
   * @var int
   */
  const CACHE_TIME = 1800;

  /**
   * Fetch rss items.
   *
   * @param string $plugin_id
   *   Plugin id for cache.
   * @param string $uri
   *   URI to fetch.
   *
   * @return array
   *   Feed items.
   */
  public static function readSource($plugin_id, $uri): array {
    $cache = \Drupal::service('dashboards.cache');
    $cid = $plugin_id . ':' . md5($uri);
    if (!($data = $cache->get($cid))) {
      Reader::setExtensionManager(\Drupal::service('feed.bridge.reader'));
      $client = \Drupal::httpClient();
      $response = $client->request('GET', $uri);
      $channel = Reader::importString($response->getBody()->getContents());
      $items = [];
      foreach ($channel as $item) {
        $items[] = [
          'title' => $item->getTitle(),
          'link' => $item->getLink(),
          'description' => $item->getDescription(),
          'date' => $item->getDateModified()->format(\DateTime::ISO8601),
        ];
      }
      $cache->set($cid, $items, time() + static::CACHE_TIME);
      return $items;
    }
    return $data->data;
  }

  /**
   * {@inheritdoc}
   */
  public function buildSettingsForm(array $form, FormStateInterface $form_state, array $configuration): array {
    $form['uri'] = [
      '#type' => 'url',
      '#title' => $this->t('Feed URL or website url'),
      '#default_value' => (isset($configuration['uri'])) ? $configuration['uri'] : '',
    ];
    $form['max_items'] = [
      '#type' => 'number',
      '#title' => $this->t('How many items to display'),
      '#default_value' => (isset($configuration['max_items'])) ? $configuration['max_items'] : 5,
    ];
    $form['show_description'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show description'),
      '#default_value' => (isset($configuration['show_description'])) ? $configuration['show_description'] : 5,
    ];
    return $form;
  }

  /**
   * Lazy builder callback.
   *
   * @param \Drupal\dashboards\Plugin\DashboardBase $plugin
   *   Plugin id.
   * @param array $configuration
   *   Plugin configuration.
   *
   * @return array
   *   Rendering array.
   */
  public static function lazyBuild(DashboardBase $plugin, array $configuration): array {
    $lastAccess = \Drupal::currentUser()->getLastAccessedTime();
    $url = $configuration['uri'];
    $max_items = $configuration['max_items'];
    $show_description = $configuration['show_description'];
    try {
      $items = static::readSource($plugin->getPluginId(), $url);
      if (count($items) > $max_items) {
        $items = array_slice($items, 0, $max_items);
      }
      $links = [];
      foreach ($items as $item) {
        $date = new DrupalDateTime($item['date'], 'UTC');
        $newIndicator = '';
        if ($date->getTimestamp() > $lastAccess) {
          $newIndicator = ' | ' . $this->t('New');
        }
        $date = \Drupal::service('date.formatter')->format($date->getTimestamp(), 'short');
        if ($show_description) {
          $links[] = [
            'title' => [
              '#type' => 'inline_template',
              '#template' => '<h6>{{ content }}</h6>',
              '#context' => [
                'date' => [
                  '#markup' => $item['date'],
                ],
                'content' => [
                  '#type' => 'link',
                  '#url' => Url::fromUri($item['link']),
                  '#title' => $item['title'],
                  '#attributes' => [
                    'target' => '_blank',
                  ],
                ],
              ],
            ],
            'date' => [
              '#type' => 'inline_template',
              '#template' => '<p><em>{{ date }} {{ new }}</em></p>',
              '#context' => [
                'date' => [
                  '#markup' => $date,
                ],
                'new' => [
                  '#markup' => $newIndicator,
                ],
              ],
            ],
            'description' => [
              '#type' => 'inline_template',
              '#template' => '{{ content|raw }}',
              '#context' => [
                'content' => strip_tags($item['description'], '<img> <a> <ul> <li> <p>'),
              ],
            ],
          ];
        }
        else {
          $links[] = [
            '#type' => 'inline_template',
            '#template' => '<h6>{{ content }}</h6> <em>{{ date }} {{ new }}</em>',
            '#context' => [
              'date' => [
                '#markup' => $date,
              ],
              'new' => [
                '#markup' => $newIndicator,
              ],
              'content' => [
                '#type' => 'link',
                '#url' => Url::fromUri($item['link']),
                '#title' => $item['title'],
                '#attributes' => [
                  'target' => '_blank',
                ],
              ],
            ],
          ];
        }
      }
      return [
        '#theme' => 'item_list',
        '#items' => $links,
        '#cache' => [
          'max-age' => static::CACHE_TIME,
        ],
      ];
    }
    catch (\Exception $ex) {
      return ['#markup' => $this->t('Could not read @url', ['@url' => $url])];
    }
    return ['#markup' => 'here'];
  }

}
