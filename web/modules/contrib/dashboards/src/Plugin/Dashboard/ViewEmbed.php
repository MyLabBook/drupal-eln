<?php

namespace Drupal\dashboards\Plugin\Dashboard;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\views\Views;
use Drupal\views\Entity\View;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\dashboards\Plugin\DashboardBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Show account info.
 *
 * @Dashboard(
 *   id = "view_embed",
 *   label = @Translation("Embed a view"),
 *   category = @Translation("Views")
 * )
 */
class ViewEmbed extends DashboardBase {

  /**
   * Entity query.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CacheBackendInterface $cache_backend, QueryFactory $query_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $cache_backend);
    $this->entityQuery = $query_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('dashboards.cache'),
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildRenderArray($configuration): array {
    $viewId = explode(':', $configuration['view']);
    $view = Views::getView($viewId[0]);
    if (!$view) {
      return [
        '#markup' => 'View do not exist anymore',
      ];
    }
    return views_embed_view($viewId[0], $viewId[1]);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array $form, FormStateInterface $form_state, array $configuration): void {
    if (!$form_state->getValue('view')) {
      $form_state->setErrorByName('view', $this->t('Please provide a view.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildSettingsForm(array $form, FormStateInterface $form_state, array $configuration): array {

    $views = $this->entityQuery
      ->get('view')
      ->condition('status', TRUE)
      ->condition("display.*.display_plugin", ['embed'], 'IN')
      ->execute();

    $views = View::loadMultiple($views);

    $options = [];

    foreach ($views as $view) {
      foreach ($view->get('display') as $display) {
        if ($display['display_plugin'] != 'embed') {
          continue;
        }
        $options[$view->id() . ':' . $display['id']] = $view->label() . ': ' . $display['display_title'];
      }
    }

    if (empty($options)) {
      $form['view'] = [
        '#markup' => $this->t('Please add a embed view to use this plugin.'),
      ];
      return $form;
    }

    $form['view'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#options' => $options,
      '#default_value' => (isset($configuration['view'])) ? $configuration['view'] : FALSE,
    ];
    return $form;
  }

}
