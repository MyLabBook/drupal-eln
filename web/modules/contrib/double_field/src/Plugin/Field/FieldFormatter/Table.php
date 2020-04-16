<?php

namespace Drupal\double_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementations for 'table' formatter.
 *
 * @FieldFormatter(
 *   id = "double_field_table",
 *   label = @Translation("Table"),
 *   field_types = {"double_field"}
 * )
 */
class Table extends Base {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'number_column' => FALSE,
      'number_column_label' => '№',
      'first_column_label' => '',
      'second_column_label' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();

    $element['number_column'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable row number column'),
      '#default_value' => $settings['number_column'],
      '#attributes' => ['class' => ['js-double-field-number-column']],
    ];
    $element['number_column_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Number column label'),
      '#size' => 30,
      '#default_value' => $settings['number_column_label'],
      '#states' => [
        'visible' => ['.js-double-field-number_column' => ['checked' => TRUE]],
      ],
    ];
    foreach (['first', 'second'] as $subfield) {
      $element[$subfield . '_column_label'] = [
        '#type' => 'textfield',
        '#title' => $subfield == 'first' ? $this->t('First column label') : $this->t('Second column label'),
        '#size' => 30,
        '#default_value' => $settings[$subfield . '_column_label'],
      ];
    }

    return $element + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $settings = $this->getSettings();

    $summary[] = $this->t('Enable row number column: @number_column', ['@number_column' => $settings['number_column'] ? $this->t('yes') : $this->t('no')]);
    if ($settings['number_column']) {
      $summary[] = $this->t('Number column label: @number_column_label', ['@number_column_label' => $settings['number_column_label']]);
    }
    if ($settings['first_column_label'] != '') {
      $summary[] = $this->t('First column label: @first_column_label', ['@first_column_label' => $settings['first_column_label']]);
    }
    if ($settings['second_column_label'] != '') {
      $summary[] = $this->t('Second column label: @second_column_label', ['@second_column_label' => $settings['second_column_label']]);
    }

    return array_merge($summary, parent::settingsSummary());
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $settings = $this->getSettings();

    $table = ['#type' => 'table'];
    $table['#attributes']['class'][] = 'double-field-table';

    if ($settings['first_column_label'] || $settings['second_column_label']) {
      if ($settings['number_column']) {
        $header[] = $settings['number_column_label'];
      }
      $header[] = $settings['first_column_label'];
      $header[] = $settings['second_column_label'];
      $table['#header'] = $header;
    }

    foreach ($items as $delta => $item) {
      $row = [];
      if ($settings['number_column']) {
        $row[]['#markup'] = $delta + 1;
      }

      foreach (['first', 'second'] as $subfield) {

        if ($settings[$subfield]['hidden']) {
          $row[]['#markup'] = '';
        }
        else {
          $row[] = [
            '#theme' => 'double_field_subfield',
            '#settings' => $settings,
            '#subfield' => $item->{$subfield},
            '#index' => $subfield,
          ];
        }
      }

      $table[$delta] = $row;
    }

    $element[0] = $table;

    return $element;
  }

}
