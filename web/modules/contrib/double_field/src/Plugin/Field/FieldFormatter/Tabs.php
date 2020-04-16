<?php

namespace Drupal\double_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementations for 'tabs' formatter.
 *
 * @FieldFormatter(
 *   id = "double_field_tabs",
 *   label = @Translation("Tabs"),
 *   field_types = {"double_field"}
 * )
 *
 * @deprecated in double_field:8.x-3.4 and is removed from double_field:8.x-4.0.
 * @see https://www.drupal.org/node/3067969
 */
class Tabs extends Base {

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    @trigger_error('Tabs formatter is deprecated in double_field:8.x-3.4 and will be removed in double_field:8.x-4.0. See https://www.drupal.org/node/3067969', E_USER_DEPRECATED);
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $element[0] = [
      '#theme' => 'double_field_tabs',
      '#items' => $items,
      '#settings' => $this->getSettings(),
      '#attached' => ['library' => ['double_field/tabs']],
    ];

    return $element;
  }

}
