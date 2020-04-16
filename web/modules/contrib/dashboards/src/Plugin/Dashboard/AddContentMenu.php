<?php

namespace Drupal\dashboards\Plugin\Dashboard;

use Drupal\Core\Url;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\dashboards\Plugin\DashboardBase;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Show account info.
 *
 * @Dashboard(
 *   id = "add_content_menu",
 *   label = @Translation("Add content"),
 *   category = @Translation("Navigation")
 * )
 */
class AddContentMenu extends DashboardBase {

  /**
   * AccountInterface definition.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * Entity bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $bundleInfo;

  /**
   * EntityTypeManagerInterface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CacheBackendInterface $cache, AccountInterface $account, EntityTypeManagerInterface $entity_type_manager, EntityTypeBundleInfoInterface $bundle_info) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $cache);
    $this->account = $account;
    $this->entityTypeManager = $entity_type_manager;
    $this->bundleInfo = $bundle_info;
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
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('entity_type.bundle.info')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildRenderArray($configuration): array {
    $bundleInfo = $this->bundleInfo->getBundleInfo('node');
    $items = [];
    foreach ($configuration['items'] as $bundle => $info) {
      if (AccessResult::allowedIfHasPermission($this->account, 'create ' . $bundle . ' content')->isAllowed()) {
        $items[] = [
          '#type' => 'link',
          '#url' => Url::fromRoute('node.add', ['node_type' => $bundle]),
          '#title' => $bundleInfo[$bundle]['label'],
        ];
      }
    }
    return [
      'menu' => [
        '#theme' => 'item_list',
        '#items' => $items,
        '#attributes' => ['class' => ['dashboard-create-content-links']],
      ],
      '#cache' => [
        'contexts'  => ['user.permissions'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildSettingsForm(array $form, FormStateInterface $form_state, array $configuration): array {
    $group_class = 'group-order-weight';
    $info = $this->bundleInfo->getBundleInfo('node');

    $form['items'] = [
      '#type' => 'table',
      '#caption' => $this->t('Menü items'),
      '#header' => [
        $this->t('Label'),
        $this->t('Weight'),
      ],
      '#empty' => $this->t('No items.'),
      '#tableselect' => FALSE,
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => $group_class,
        ],
      ],
    ];

    foreach ($info as $key => $value) {
      $form['items'][$key]['#attributes']['class'][] = 'draggable';
      $form['items'][$key]['#weight'] = (!isset($value['weight'])) ? 0 : $value['weight'];

      // Label col.
      $form['items'][$key]['label'] = [
        '#plain_text' => $value['label'],
      ];

      // Weight col.
      $form['items'][$key]['weight'] = [
        '#type' => 'weight',
        '#title' => $this->t('Weight for @title', ['@title' => $value['label']]),
        '#title_display' => 'invisible',
        '#default_value' => (!isset($value['weight'])) ? 0 : $value['weight'],
        '#attributes' => ['class' => [$group_class]],
      ];
    }

    return $form;
  }

}
