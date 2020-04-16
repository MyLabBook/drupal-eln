<?php

namespace Drupal\double_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementations for 'details' formatter.
 *
 * @FieldFormatter(
 *   id = "double_field_details",
 *   label = @Translation("Details"),
 *   field_types = {"double_field"}
 * )
 */
class Details extends Base {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'open' => TRUE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();

    $element['open'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open'),
      '#default_value' => $settings['open'],
    ];

    $element += parent::settingsForm($form, $form_state);

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $open = $this->getSetting('open');
    $summary[] = $this->t('Open: @open', ['@open' => $open ? $this->t('yes') : $this->t('no')]);
    return array_merge($summary, parent::settingsSummary());
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    $settings = $this->getSettings();

    foreach ($items as $delta => $item) {

      $values = [];
      foreach (['first', 'second'] as $subfield) {
        $values[$subfield] = $item->{$subfield};
        // Copy the property to a variable because of its magic nature.
        $value = $item->{$subfield};
        // The value can be a render array if link option is enabled.
        if (is_array($value)) {
          $values[$subfield]['#prefix'] = $settings[$subfield]['prefix'];
          $values[$subfield]['#suffix'] = $settings[$subfield]['suffix'];
        }
        else {
          $values[$subfield] = $settings[$subfield]['prefix'] . $value . $settings[$subfield]['suffix'];
        }
      }

      $element[$delta] = [
        '#title' => $values['first'],
        '#value' => $values['second'],
        '#type' => 'details',
        '#open' => $settings['open'],
        '#attributes' => ['class' => ['double-field-details']],
      ];
    }

    return $element;
  }

}
