<?php

namespace Drupal\double_field\Plugin\Field\FieldFormatter;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Datetime\Entity\DateFormat;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\double_field\Plugin\Field\FieldType\DoubleField as DoubleFieldItem;

/**
 * Base class for Double field formatters.
 */
abstract class Base extends FormatterBase {

  /**
   * Subfield types that can be rendered as a link.
   *
   * @var array
   */
  protected static $linkTypes = ['email', 'telephone', 'uri'];

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = [];
    foreach (['first', 'second'] as $subfield) {
      $settings[$subfield] = [
        // Hidden option are useful to display data with Views module.
        'hidden' => FALSE,
        'prefix' => '',
        'suffix' => '',
        'link' => FALSE,
        'format_type' => 'medium',
        // @todo Create tests for this options.
        'thousand_separator' => '',
        'decimal_separator' => '.',
        'scale' => 2,
      ];
    }
    return $settings + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();
    $field_settings = $this->getFieldSettings();
    $types = DoubleFieldItem::subfieldTypes();
    $element = [];

    // General settings.
    foreach (['first', 'second'] as $subfield) {
      $type = $field_settings['storage'][$subfield]['type'];

      $title = $subfield == 'first' ? $this->t('First subfield') : $this->t('Second subfield');
      $title .= ' - ' . $types[$type];

      $element[$subfield] = [
        '#title' => $title,
        '#type' => 'details',
      ];

      $element[$subfield]['link'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Display as link'),
        '#default_value' => $settings[$subfield]['link'],
        '#weight' => -10,
        '#access' => in_array($type, static::$linkTypes),
      ];

      if ($type == 'datetime_iso8601') {
        $format_types = DateFormat::loadMultiple();
        $date_formatter = self::getDateFormatter();
        $time = new DrupalDateTime();
        $options = [];
        foreach ($format_types as $type => $type_info) {
          $format = $date_formatter->format($time->getTimestamp(), $type);
          $options[$type] = $type_info->label() . ' (' . $format . ')';
        }
        $element[$subfield]['format_type'] = [
          '#type' => 'select',
          '#title' => $this->t('Date format'),
          '#description' => $this->t('Choose a format for displaying the date.'),
          '#options' => $options,
          '#default_value' => $settings[$subfield]['format_type'],
        ];
      }
      else {
        $element[$subfield]['format_type'] = [
          '#type' => 'value',
          '#default_value' => $settings[$subfield]['format_type'],
        ];
      }

      $element[$subfield]['hidden'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Hidden'),
        '#default_value' => $settings[$subfield]['hidden'],
      ];
      $element[$subfield]['prefix'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Prefix (deprecated)'),
        '#size' => 30,
        '#default_value' => $settings[$subfield]['prefix'],
      ];
      $element[$subfield]['suffix'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Suffix (deprecated)'),
        '#size' => 30,
        '#default_value' => $settings[$subfield]['suffix'],
      ];

      // BC Layer. The settings below may not be set if site was updated from
      // version below 3.3.
      // @todo Remove this in 4.0.
      $default_settings = self::defaultSettings()[$subfield];
      $settings[$subfield]['scale'] = $settings[$subfield]['scale'] ?? $default_settings['scale'];
      $settings[$subfield]['decimal_separator'] = $settings[$subfield]['decimal_separator'] ?? $default_settings['decimal_separator'];
      $settings[$subfield]['thousand_separator'] = $settings[$subfield]['thousand_separator'] ?? $default_settings['thousand_separator'];

      if ($type == 'numeric' || $type == 'float' || $type == 'integer') {
        $options = [
          '' => $this->t('- None -'),
          '.' => $this->t('Decimal point'),
          ',' => $this->t('Comma'),
          ' ' => $this->t('Space'),
          chr(8201) => $this->t('Thin space'),
          "'" => $this->t('Apostrophe'),
        ];
        $element[$subfield]['thousand_separator'] = [
          '#type' => 'select',
          '#title' => $this->t('Thousand marker'),
          '#options' => $options,
          '#default_value' => $settings[$subfield]['thousand_separator'],
        ];
      }
      else {
        $element[$subfield]['thousand_separator'] = [
          '#type' => 'value',
          '#default_value' => $settings[$subfield]['thousand_separator'],
        ];
      }

      if ($type == 'numeric' || $type == 'float') {
        $element[$subfield]['decimal_separator'] = [
          '#type' => 'select',
          '#title' => $this->t('Decimal marker'),
          '#options' => ['.' => $this->t('Decimal point'), ',' => $this->t('Comma')],
          '#default_value' => $settings[$subfield]['decimal_separator'],
        ];
        $element[$subfield]['scale'] = [
          '#type' => 'number',
          '#title' => $this->t('Scale', [], ['context' => 'decimal places']),
          '#min' => 0,
          '#max' => 10,
          '#default_value' => $settings[$subfield]['scale'],
          '#description' => $this->t('The number of digits to the right of the decimal.'),
        ];
      }
      else {
        $element[$subfield]['decimal_separator'] = [
          '#type' => 'value',
          '#default_value' => $settings[$subfield]['decimal_separator'],
        ];
        $element[$subfield]['scale'] = [
          '#type' => 'value',
          '#default_value' => $settings[$subfield]['scale'],
        ];
      }

    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $settings = $this->getSettings();
    $field_settings = $this->getFieldSettings();

    $subfield_types = DoubleFieldItem::subfieldTypes();

    $summary = [];
    foreach (['first', 'second'] as $subfield) {
      $subfield_type = $field_settings['storage'][$subfield]['type'];
      $summary[] = new FormattableMarkup(
        '<b>@subfield - @subfield_type</b>',
        [
          '@subfield' => ($subfield == 'first' ? $this->t('First subfield') : $this->t('Second subfield')),
          '@subfield_type' => strtolower($subfield_types[$subfield_type]),
        ]
      );
      if ($subfield_type == 'datetime_iso8601') {
        $summary[] = $this->t('Date format: @format', ['@format' => $settings[$subfield]['format_type']]);
      }
      if (in_array($subfield_type, static::$linkTypes)) {
        $summary[] = $this->t('Link: @value', ['@value' => $settings[$subfield]['link'] ? $this->t('yes') : $this->t('no')]);
      }
      $summary[] = $this->t('Hidden: @value', ['@value' => $settings[$subfield]['hidden'] ? $this->t('yes') : $this->t('no')]);
      if ($settings[$subfield]['prefix'] != '') {
        $summary[] = $this->t('Prefix (deprecated): @prefix', ['@prefix' => $settings[$subfield]['prefix']]);
      }
      if ($settings[$subfield]['suffix'] != '') {
        $summary[] = $this->t('Suffix (deprecated): @suffix', ['@suffix' => $settings[$subfield]['suffix']]);
      }
      if ($subfield_type == 'numeric' || $subfield_type == 'float' || $subfield_type == 'integer') {
        $summary[] = $this->t('Number format: @format', ['@format' => $this->numberFormat($subfield, 1234.1234567890)]);
      }
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function view(FieldItemListInterface $items, $langcode = NULL) {
    $elements = [];
    if (count($items) > 0) {
      // A field may appear multiple times in a single view. Since items are
      // passed by reference we need to ensure they are processed only once.
      $items = clone $items;
      $this->prepareItems($items);
      $elements = parent::view($items, $langcode);
    }
    return $elements;
  }

  /**
   * Prepare field items.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *   List of field items.
   */
  protected function prepareItems(FieldItemListInterface $items) {

    $field_settings = $this->getFieldSettings();
    $settings = $this->getSettings();

    // @todo Remove this in 4.0.
    foreach (['first', 'second'] as $subfield) {
      if ($settings[$subfield]['prefix']) {
        @trigger_error('Prefix formatter setting is deprecated in double_field:8.x-3.4 and will be removed in double_field:8.x-4.0.', E_USER_DEPRECATED);
      }
      if ($settings[$subfield]['suffix']) {
        @trigger_error('Suffix formatter setting is deprecated in double_field:8.x-3.4 and will be removed in double_field:8.x-4.0.', E_USER_DEPRECATED);
      }
    }

    foreach ($items as $delta => $item) {
      foreach (['first', 'second'] as $subfield) {

        if ($settings[$subfield]['hidden']) {
          $item->{$subfield} = FALSE;
        }
        else {

          $type = $field_settings['storage'][$subfield]['type'];

          if ($type == 'boolean') {
            $item->{$subfield} = $field_settings[$subfield][$item->{$subfield} ? 'on_label' : 'off_label'];
          }

          if ($type == 'numeric' || $type == 'float' || $type == 'integer') {
            $item->{$subfield} = $this->numberFormat($subfield, $item->{$subfield});
          }

          if ($type == 'datetime_iso8601' && $item->{$subfield} && !$field_settings[$subfield]['list']) {
            // We follow the same principles as Drupal Core.
            // In the case of a datetime subfield, the date must be parsed using
            // the storage time zone and converted to the user's time zone while
            // a date-only field should have no timezone conversion performed.
            $timezone = $field_settings['storage'][$subfield]['datetime_type'] === 'datetime' ?
              date_default_timezone_get() : DoubleFieldItem::DATETIME_STORAGE_TIMEZONE;
            $timestamp = $items[$delta]->createDate($subfield)->getTimestamp();
            $date_formatter = self::getDateFormatter();
            $item->{$subfield} = [
              '#theme' => 'time',
              '#text' => $date_formatter->format($timestamp, $settings[$subfield]['format_type'], '', $timezone),
              '#html' => FALSE,
              '#attributes' => [
                'datetime' => $date_formatter->format($timestamp, 'custom', 'Y-m-d\TH:i:s') . 'Z',
              ],
              '#cache' => [
                'contexts' => [
                  'timezone',
                ],
              ],
            ];
          }

          // Replace the value with its label if possible.
          $original_value[$subfield] = $item->{$subfield};
          if ($field_settings[$subfield]['list']) {
            if (isset($field_settings[$subfield]['allowed_values'][$item->{$subfield}])) {
              $item->{$subfield} = $field_settings[$subfield]['allowed_values'][$item->{$subfield}];
            }
            else {
              $item->{$subfield} = FALSE;
            }
          }

          if (!empty($settings[$subfield]['link'])) {
            $value = isset($original_value) ? $original_value[$subfield] : $item->{$subfield};
            switch ($type) {
              case 'email':
                $item->{$subfield} = [
                  '#type' => 'link',
                  '#title' => $item->{$subfield},
                  '#url' => Url::fromUri('mailto:' . $value),
                ];
                break;

              case 'telephone':
                $item->{$subfield} = [
                  '#type' => 'link',
                  '#title' => $item->{$subfield},
                  '#url' => Url::fromUri('tel:' . rawurlencode(preg_replace('/\s+/', '', $value))),
                  '#options' => ['external' => TRUE],
                ];
                break;

              case 'uri':
                $item->{$subfield} = [
                  '#type' => 'link',
                  '#title' => $item->{$subfield},
                  '#url' => Url::fromUri($value),
                  '#options' => ['external' => TRUE],
                ];
                break;

            }
          }

        }

      }
      $items[$delta] = $item;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function numberFormat($subfield, $number) {
    $settings = $this->getSetting($subfield);
    if ($this->getFieldSetting('storage')[$subfield]['type'] == 'integer') {
      $settings['scale'] = 0;
    }

    // BC Layer. The settings below may not be set if site was updated from
    // version below 3.3.
    // @todo Remove this in 4.0.
    $default_settings = self::defaultSettings()[$subfield];
    $settings['scale'] = $settings['scale'] ?? $default_settings['scale'];
    $settings['decimal_separator'] = $settings['decimal_separator'] ?? $default_settings['decimal_separator'];
    $settings['thousand_separator'] = $settings['thousand_separator'] ?? $default_settings['thousand_separator'];

    return number_format($number, $settings['scale'], $settings['decimal_separator'], $settings['thousand_separator']);
  }

  /**
   * Returns date formatter.
   */
  protected static function getDateFormatter() {
    return \Drupal::service('date.formatter');
  }

}
