<?php

namespace Drupal\dashboards\Plugin\SectionStorage;

use Drupal\user\UserDataInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\dashboards\Entity\Dashboard;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Plugin\Context\EntityContext;
use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\layout_builder\TempStoreIdentifierInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\layout_builder\Entity\SampleEntityGeneratorInterface;

/**
 * Class DashboardSectionStorage.
 *
 * @SectionStorage(
 *   id = "dashboards_override",
 *   weight = 20,
 *   context_definitions = {
 *     "entity" = @ContextDefinition("entity:dashboard")
 *   },
 *   handles_permission_check = TRUE,
 * )
 *
 * @package Drupal\dashboards\Plugin\SectionStorage
 */
class UserDashboardSectionStorage extends DashboardSectionStorage implements TempStoreIdentifierInterface {

  /**
   * UserDataInterface definition.
   *
   * @var \Drupal\user\UserDataInterface
   */
  protected $userData;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity_type.bundle.info'),
      $container->get('layout_builder.sample_entity_generator'),
      $container->get('current_user'),
      $container->get('user.data')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityTypeBundleInfoInterface $entity_bundle_info, SampleEntityGeneratorInterface $sample_entity_generator, AccountInterface $account, UserDataInterface $user_data) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $entity_bundle_info, $sample_entity_generator, $account);
    $this->userData = $user_data;
  }

  /**
   * {@inheritdoc}
   */
  public function isApplicable(RefinableCacheableDependencyInterface $cacheability) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function buildRoutes(RouteCollection $collection) {
    $requirements = [];
    $this->buildLayoutRoutes(
      $collection,
      $this->getPluginDefinition(),
      'dashboard/{dashboard}/override',
      [
        'parameters' => [
          'dashboard' => [
            'type' => 'entity:dashboard',
          ],
        ],
        'view_mode' => 'user',
      ],
      $requirements,
      ['_admin_route' => FALSE],
      '',
      'dashboard'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function deriveContextsFromRoute($value, $definition, $name, array $defaults) {
    $contexts = [];

    $id = !empty($value) ? $value : (!empty($defaults['dashboard']) ? $defaults['dashboard'] : NULL);
    /**
     * @var \Drupal\dashboards\Entity\Dashboard $entity
     */
    if ($id && ($entity = $this->entityTypeManager->getStorage('dashboard')->load($id))) {
      if ($entity->isOverriden()) {
        $entity->loadOverrides();
      }
      $contexts[Dashboard::CONTEXT_TYPE] = EntityContext::fromEntity($entity);
    }
    return $contexts;
  }

  /**
   * Saves the sections.
   *
   * @return int
   *   SAVED_NEW or SAVED_UPDATED is returned depending on the operation
   *   performed.
   */
  public function save() {
    $sections = [];

    foreach ($this->getDashboard()->get('sections') as $delta => $section) {
      $sections[$delta] = $section->toArray();
    }
    $this->userData->set('dashboards', $this->account->id(), $this->getDashboard()->id(), serialize($sections));
    return SAVED_UPDATED;
  }

  /**
   * Overrides \Drupal\Core\Access\AccessibleInterface::access().
   *
   * @ingroup layout_builder_access
   */
  public function access($operation, AccountInterface $account = NULL, $return_as_object = FALSE) {
    if (!$account) {
      $account = $this->account;
    }
    $result = AccessResult::allowedIfHasPermission($account, 'administer dashboards')
      ->orIf(AccessResult::allowedIfHasPermission($account, 'can override ' . $this->getDashboard()->id() . ' dashboard'));
    if ($return_as_object) {
      return $result;
    }
    return $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  public function getTempstoreKey() {
    return $this->getDashboard()->id() . '_' . $this->account->id();
  }

}
