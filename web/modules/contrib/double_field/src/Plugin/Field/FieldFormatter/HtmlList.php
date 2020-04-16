<?php

namespace Drupal\double_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementations for 'html_list' formatter.
 *
 * @FieldFormatter(
 *   id = "double_field_html_list",
 *   label = @Translation("Html list"),
 *   field_types = {"double_field"}
 * )
 */
class HtmlList extends ListBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return ['list_type' => 'ul'] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['list_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('List type'),
      '#options' => [
        'ul' => $this->t('Unordered list'),
        'ol' => $this->t('Ordered list'),
        'dl' => $this->t('Definition list'),
      ],
      '#default_value' => $this->getSetting('list_type'),
    ];

    $element += parent::settingsForm($form, $form_state);
    $field_name = $this->fieldDefinition->getName();

    $element['inline']['#states']['invisible'] = [":input[name='fields[$field_name][settings_edit_form][settings][list_type]']" => ['value' => 'dl']];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $parent_summary = parent::settingsSummary();

    // Definition list does not support 'inline' option.
    $list_type = $this->getSetting('list_type');
    if ($list_type == 'dl') {
      if (($key = array_search($this->t('Display as inline element'), $parent_summary)) !== FALSE) {
        unset($parent_summary[$key]);
      }
    }

    $summary[] = $this->t('List type: @list_type', ['@list_type' => $this->getSetting('list_type')]);
    return array_merge($summary, $parent_summary);
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $settings = $this->getSettings();

    if ($settings['list_type'] == 'dl') {
      $element[0] = [
        '#theme' => 'double_field_definition_list',
        '#items' => $items,
        '#settings' => $settings,
      ];
    }
    else {
      $list_items = [];
      foreach ($items as $delta => $item) {

        $list_items[$delta] = [
          '#settings' => $settings,
          '#item' => $item,
          '#theme' => 'double_field_item',
        ];
        if ($settings['inline']) {
          $list_items[$delta]['#wrapper_attributes'] = [];
          if (!isset($item->_attributes)) {
            $item->_attributes = [];
          }
          $list_items[$delta]['#wrapper_attributes'] += $item->_attributes;
          $list_items[$delta]['#wrapper_attributes']['class'][] = 'container-inline';
        }
      }
      $element[0] = [
        '#theme' => 'item_list',
        '#list_type' => $settings['list_type'],
        '#items' => $list_items,
      ];
    }

    $element[0]['#attributes']['class'][] = 'double-field-list';

    return $element;
  }

}
