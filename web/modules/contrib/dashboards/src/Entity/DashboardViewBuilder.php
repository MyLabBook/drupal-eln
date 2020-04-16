<?php

namespace Drupal\dashboards\Entity;

use Drupal\Component\Plugin\Exception\ContextException;
use Drupal\Core\Theme\Registry;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Plugin\Context\EntityContext;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\dashboards\Plugin\SectionStorage\UserDashboardSectionStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\layout_builder\SectionStorage\SectionStorageManagerInterface;

/**
 * Dashboard Entity ViewBuilder.
 */
class DashboardViewBuilder extends EntityViewBuilder {

  /**
   * Dashboard settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Section storage handler.
   *
   * @var \Drupal\layout_builder\SectionStorage\SectionStorageManager
   */
  protected $sectionStorageManager;

  /**
   * Module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a new EntityViewBuilder.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Theme\Registry $theme_registry
   *   The theme registry.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository.
   * @param \Drupal\layout_builder\SectionStorage\SectionStorageManagerInterface $section_storage_manager
   *   Section storage handler.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler interface.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Module config factory interface.
   */
  public function __construct(
    EntityTypeInterface $entity_type,
    EntityRepositoryInterface $entity_repository,
    LanguageManagerInterface $language_manager,
    Registry $theme_registry = NULL,
    EntityDisplayRepositoryInterface $entity_display_repository = NULL,
    SectionStorageManagerInterface $section_storage_manager,
    ModuleHandlerInterface $module_handler,
    ConfigFactoryInterface $config_factory
    ) {
    parent::__construct(
      $entity_type,
      $entity_repository,
      $language_manager,
      $theme_registry,
      $entity_display_repository
    );
    $this->sectionStorageManager = $section_storage_manager;
    $this->moduleHandler = $module_handler;
    $this->config = $config_factory->get('dashboards.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.repository'),
      $container->get('language_manager'),
      $container->get('theme.registry'),
      $container->get('entity_display.repository'),
      $container->get('plugin.manager.layout_builder.section_storage'),
      $container->get('module_handler'),
      $container->get('config.factory')
    );
  }

  /**
   * View entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Entity to display.
   * @param string $view_mode
   *   View mode.
   * @param string $langcode
   *   Language code.
   */
  public function view(EntityInterface $entity, $view_mode = 'full', $langcode = NULL) {
    /**
     * @var \Drupal\dashboards\Entity\Dashboard $entity
     */
    $build_list = $this->viewMultiple([$entity], $view_mode, $langcode);

    $build = $build_list[0];
    $build['#pre_render'][] = [$this, 'build'];

    $contexts[Dashboard::CONTEXT_TYPE] = $contexts['layout_builder.entity'] = EntityContext::fromEntity($entity);

    $cacheability = new CacheableMetadata();
    $storage = $this->sectionStorageManager->findByContext($contexts, $cacheability);
    if (is_a($storage, UserDashboardSectionStorage::class)) {
      $entity->loadOverrides();
      $contexts[Dashboard::CONTEXT_TYPE] = $contexts['layout_builder.entity'] = EntityContext::fromEntity($entity);
    }
    $this->moduleHandler->alter('layout_builder_view_context', $contexts, $storage);

    $build = [];

    foreach ($storage->getSections() as $delta => $section) {
      try {
        $build['sections'][$delta] = $section->toRenderArray($contexts);
      }
      catch (ContextException $ex) {

      }
    }

    return [
      '#attached' => [
        'library' => ['dashboards/core'],
        'drupalSettings' => [
          'dashboards' => [
            'colormap' => ($this->config->get('colormap')) ? $this->config->get('colormap') : 'summer',
            'alpha' => ($this->config->get('alpha')) ? ($this->config->get('alpha') / 100) : 80,
            'shades' => ($this->config->get('shades')) ? $this->config->get('shades') : 15,
          ],
        ],
      ],
      '#type' => 'container',
      '#attributes' => [
        'class' => [
          'dashboards-container',
        ],
      ],
      'content' => $build,
    ];
  }

}
