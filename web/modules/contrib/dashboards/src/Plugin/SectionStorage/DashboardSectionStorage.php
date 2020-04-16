<?php

namespace Drupal\dashboards\Plugin\SectionStorage;

use Drupal\Core\Url;
use Drupal\Core\Access\AccessResult;
use Drupal\dashboards\Entity\Dashboard;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Plugin\Context\EntityContext;
use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Config\Entity\ThirdPartySettingsInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\layout_builder\Entity\SampleEntityGeneratorInterface;
use Drupal\layout_builder\Plugin\SectionStorage\SectionStorageBase;

/**
 * Class DashboardSectionStorage.
 *
 * @SectionStorage(
 *   id = "dashboards",
 *   weight = 10,
 *   context_definitions = {
 *     "entity" = @ContextDefinition("entity:dashboard")
 *   },
 *   handles_permission_check = TRUE,
 * )
 *
 * @package Drupal\dashboards\Plugin\SectionStorage
 */
class DashboardSectionStorage extends SectionStorageBase implements ContainerFactoryPluginInterface, ThirdPartySettingsInterface {

  /**
   * EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * EntityTypeBundleInfo definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityBundleInfo;

  /**
   * SampleEntityGenerator definition.
   *
   * @var \Drupal\layout_builder\Entity\SampleEntityGeneratorInterface
   */
  protected $sampleEntityGenerator;

  /**
   * EntityTypeManager definition.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

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
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityTypeBundleInfoInterface $entity_bundle_info, SampleEntityGeneratorInterface $sample_entity_generator, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->entityBundleInfo = $entity_bundle_info;
    $this->sampleEntityGenerator = $sample_entity_generator;
    $this->account = $current_user;
  }

  /**
   * Get the dashboard entity.
   *
   * @return \Drupal\dashboards\Entity\Dashboard
   *   Dashboard entity.
   */
  protected function getDashboard() {
    return $this->getContextValue(Dashboard::CONTEXT_TYPE);
  }

  /**
   * Gets the section list.
   *
   * @return \Drupal\layout_builder\SectionListInterface
   *   The section list.
   */
  protected function getSectionList() {
    return $this->getDashboard();
  }

  /**
   * Returns an identifier for this storage.
   *
   * @return string
   *   The unique identifier for this storage.
   */
  public function getStorageId() {
    return $this->getDashboard()->id();
  }

  /**
   * Derives the section list from the storage ID.
   *
   * @param string $id
   *   The storage ID, see ::getStorageId().
   *
   * @return \Drupal\layout_builder\SectionListInterface
   *   The section list.
   *
   * @throws \InvalidArgumentException
   *   Thrown if the ID is invalid.
   *
   * @internal
   *   This should only be called during section storage instantiation.
   *
   * @deprecated in drupal:8.7.0 and is removed from drupal:9.0.0.
   *   Do not use anymore.
   * @see https://www.drupal.org/node/3016262
   */
  public function getSectionListFromId($id) {
    @trigger_error('\Drupal\layout_builder\SectionStorageInterface::getSectionListFromId() is deprecated in drupal:8.7.0 and is removed from drupal:9.0.0. The section list should be derived from context. See https://www.drupal.org/node/3016262', E_USER_DEPRECATED);
    return $this->entityTypeManager->getStorage('dashboard')->load($id);
  }

  /**
   * Provides the routes needed for Layout Builder UI.
   *
   * Allows the plugin to add or alter routes during the route building process.
   * \Drupal\layout_builder\Routing\LayoutBuilderRoutesTrait is provided for the
   * typical use case of building a standard Layout Builder UI.
   *
   * @param \Symfony\Component\Routing\RouteCollection $collection
   *   The route collection.
   *
   * @see \Drupal\Core\Routing\RoutingEvents::ALTER
   */
  public function buildRoutes(RouteCollection $collection) {
    $requirements = [];
    $this->buildLayoutRoutes(
      $collection,
      $this->getPluginDefinition(),
      'dashboards/{dashboard}/layout',
      [
        'parameters' => [
          'dashboard' => [
            'type' => 'entity:dashboard',
          ],
        ],
      ],
      $requirements,
      // This can't be an admin route because seven decides to ditch all
      // contextual links on blocks. See issue
      // https://www.drupal.org/project/drupal/issues/2487025
      ['_admin_route' => FALSE],
      '',
      'dashboard'
    );
  }

  /**
   * Gets the URL used when redirecting away from the Layout Builder UI.
   *
   * @return \Drupal\Core\Url
   *   The URL object.
   */
  public function getRedirectUrl() {
    return Url::fromRoute('entity.dashboard.canonical', [
      'dashboard' => $this->getDashboard()->id(),
    ]);
  }

  /**
   * Gets the URL used to display the Layout Builder UI.
   *
   * @param string $rel
   *   (optional) The link relationship type, for example: 'view' or 'disable'.
   *   Defaults to 'view'.
   *
   * @return \Drupal\Core\Url
   *   The URL object.
   */
  public function getLayoutBuilderUrl($rel = 'view') {
    return Url::fromRoute("layout_builder.{$this->getStorageType()}.{$rel}", [
      'dashboard' => $this->getDashboard()->id(),
    ]);
  }

  /**
   * Configures the plugin based on route values.
   *
   * @param mixed $value
   *   The raw value.
   * @param mixed $definition
   *   The parameter definition provided in the route options.
   * @param string $name
   *   The name of the parameter.
   * @param array $defaults
   *   The route defaults array.
   *
   * @internal
   *   This should only be called during section storage instantiation.
   *
   * @deprecated in drupal:8.7.0 and is removed from drupal:9.0.0.
   *   \Drupal\layout_builder\SectionStorageInterface::deriveContextsFromRoute()
   *   should be used instead.
   * @see https://www.drupal.org/node/3016262
   */
  public function extractIdFromRoute($value, $definition, $name, array $defaults) {
    throw new \Exception(new TranslatableMarkup('This method is deprecated in 8.7.0'));
  }

  /**
   * Derives the available plugin contexts from route values.
   *
   * This should only be called during section storage instantiation,
   * specifically for use by the routing system. For all non-routing usages, use
   * \Drupal\Component\Plugin\ContextAwarePluginInterface::getContextValue().
   *
   * @param mixed $value
   *   The raw value.
   * @param mixed $definition
   *   The parameter definition provided in the route options.
   * @param string $name
   *   The name of the parameter.
   * @param array $defaults
   *   The route defaults array.
   *
   * @return \Drupal\Core\Plugin\Context\ContextInterface[]
   *   The available plugin contexts.
   *
   * @see \Drupal\Core\ParamConverter\ParamConverterInterface::convert()
   */
  public function deriveContextsFromRoute($value, $definition, $name, array $defaults) {
    $contexts = [];

    $id = !empty($value) ? $value : (!empty($defaults['dashboard']) ? $defaults['dashboard'] : NULL);
    if ($id && ($entity = $this->entityTypeManager->getStorage('dashboard')->load($id))) {
      $contexts[Dashboard::CONTEXT_TYPE] = EntityContext::fromEntity($entity);
    }
    return $contexts;
  }

  /**
   * Gets the label for the object using the sections.
   *
   * @return string
   *   The label, or NULL if there is no label defined.
   */
  public function label() {
    return $this->getDashboard()->label();
  }

  /**
   * Saves the sections.
   *
   * @return int
   *   SAVED_NEW or SAVED_UPDATED is returned depending on the operation
   *   performed.
   */
  public function save() {
    return $this->getDashboard()->save();
  }

  /**
   * Determines if this section storage is applicable for the current contexts.
   *
   * @param \Drupal\Core\Cache\RefinableCacheableDependencyInterface $cacheability
   *   Refinable cacheability object, typically provided by the section storage
   *   manager. When implementing this method, populate $cacheability with any
   *   information that affects whether this storage is applicable.
   *
   * @return bool
   *   TRUE if this section storage is applicable, FALSE otherwise.
   *
   * @internal
   *   This method is intended to be called by
   *   \Drupal\layout_builder\SectionStorage\SectionStorageManagerInterface::findByContext().
   *
   * @see \Drupal\Core\Cache\RefinableCacheableDependencyInterface
   */
  public function isApplicable(RefinableCacheableDependencyInterface $cacheability) {
    $entity = $this->getContextValue(Dashboard::CONTEXT_TYPE);
    return !$entity->isOverriden();
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
    $result = AccessResult::allowedIfHasPermission($account, 'administer dashboards');
    if ($return_as_object) {
      return $result;
    }
    return $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  public function setThirdPartySetting($module, $key, $value) {
    $this->getDashboard()->setThirdPartySetting($module, $key, $value);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getThirdPartySetting($module, $key, $default = NULL) {
    return $this->getDashboard()->getThirdPartySetting($module, $key, $default);
  }

  /**
   * {@inheritdoc}
   */
  public function getThirdPartySettings($module) {
    return $this->getDashboard()->getThirdPartySettings($module);
  }

  /**
   * {@inheritdoc}
   */
  public function unsetThirdPartySetting($module, $key) {
    $this->getDashboard()->unsetThirdPartySetting($module, $key);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getThirdPartyProviders() {
    return $this->getDashboard()->getThirdPartyProviders();
  }

}
