<?php

namespace Drupal\double_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementations for 'double_field' formatter.
 *
 * @FieldFormatter(
 *   id = "double_field_unformatted_list",
 *   label = @Translation("Unformatted list"),
 *   field_types = {"double_field"}
 * )
 */
class UnformattedList extends ListBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    $element['#attributes']['class'][] = 'double-field-unformatted-list';

    $settings = $this->getSettings();
    foreach ($items as $delta => $item) {
      if ($settings['inline']) {
        if (!isset($item->_attributes)) {
          $item->_attributes = [];
        }
        $item->_attributes += ['class' => ['container-inline']];
      }
      $element[$delta] = [
        '#settings' => $settings,
        '#item' => $item,
        '#theme' => 'double_field_item',
      ];
    }

    return $element;
  }

}
