<?php

namespace Drupal\double_field\Plugin\Field\FieldType;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Email;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Component\Utility\Random;

/**
 * Plugin implementation of the 'double_field' field type.
 *
 * @FieldType(
 *   id = "double_field",
 *   label = @Translation("Double Field"),
 *   description = @Translation("Double Field."),
 *   default_widget = "double_field",
 *   default_formatter = "double_field_unformatted_list"
 * )
 */
class DoubleField extends FieldItemBase {

  const DATETIME_STORAGE_TIMEZONE = 'UTC';
  const DATETIME_DATETIME_STORAGE_FORMAT = 'Y-m-d\TH:i:s';
  const DATETIME_DATE_STORAGE_FORMAT = 'Y-m-d';

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {

    $settings = [];
    foreach (['first', 'second'] as $subfield) {
      $settings['storage'][$subfield] = [
        'type' => 'string',
        'maxlength' => 255,
        'precision' => 10,
        'scale' => 2,
        'datetime_type' => 'datetime',
      ];
    }

    return $settings + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {

    $settings = $this->getSettings();

    $element = [];
    foreach (['first', 'second'] as $subfield) {
      $element['storage'][$subfield] = [
        '#type' => 'details',
        '#title' => $subfield == 'first' ? $this->t('First subfield') : $this->t('Second subfield'),
        '#open' => TRUE,
      ];

      $element['storage'][$subfield]['type'] = [
        '#type' => 'select',
        '#title' => $this->t('Field type'),
        '#default_value' => $settings['storage'][$subfield]['type'],
        '#disabled' => $has_data,
        '#required' => TRUE,
        '#options' => $this->subfieldTypes(),
      ];

      $element['storage'][$subfield]['maxlength'] = [
        '#type' => 'number',
        '#title' => $this->t('Maximum length'),
        '#description' => $this->t('The maximum length of the subfield in characters.'),
        '#default_value' => $settings['storage'][$subfield]['maxlength'],
        '#disabled' => $has_data,
        '#required' => TRUE,
        '#min' => 1,
        '#states' => [
          'visible' => [":input[name='settings[storage][$subfield][type]']" => ['value' => 'string']],
        ],
      ];

      $element['storage'][$subfield]['precision'] = [
        '#type' => 'number',
        '#title' => $this->t('Precision'),
        '#description' => $this->t('The total number of digits to store in the database, including those to the right of the decimal.'),
        '#default_value' => $settings['storage'][$subfield]['precision'],
        '#disabled' => $has_data,
        '#required' => TRUE,
        '#min' => 10,
        '#max' => 32,
        '#states' => [
          'visible' => [":input[name='settings[storage][$subfield][type]']" => ['value' => 'numeric']],
        ],
      ];

      $element['storage'][$subfield]['scale'] = [
        '#type' => 'number',
        '#title' => $this->t('Scale'),
        '#description' => $this->t('The number of digits to the right of the decimal.'),
        '#default_value' => $settings['storage'][$subfield]['scale'],
        '#disabled' => $has_data,
        '#required' => TRUE,
        '#min' => 0,
        '#max' => 10,
        '#states' => [
          'visible' => [":input[name='settings[storage][$subfield][type]']" => ['value' => 'numeric']],
        ],
      ];

      $element['storage'][$subfield]['datetime_type'] = [
        '#type' => 'radios',
        '#title' => $this->t('Date type'),
        '#description' => $this->t('Choose the type of date to create.'),
        '#default_value' => $settings['storage'][$subfield]['datetime_type'],
        '#disabled' => $has_data,
        '#options' => [
          'datetime' => $this->t('Date and time'),
          'date' => $this->t('Date only'),
        ],
        '#states' => [
          'visible' => [":input[name='settings[storage][$subfield][type]']" => ['value' => 'datetime_iso8601']],
        ],
      ];

    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {

    $settings = [];
    foreach (['first', 'second'] as $subfield) {
      $settings[$subfield] = [
        'label' => '',
        'min' => '',
        'max' => '',
        'list' => FALSE,
        'allowed_values' => [],
        'required' => TRUE,
        'on_label' => t('On'),
        'off_label' => t('Off'),
      ];
    }

    return $settings + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {

    $settings = $this->getSettings();

    $types = self::subfieldTypes();
    $element = [];
    foreach (['first', 'second'] as $subfield) {

      $type = $settings['storage'][$subfield]['type'];

      $title = $subfield == 'first' ? $this->t('First subfield') : $this->t('Second subfield');
      $title .= ' - ' . $types[$type];

      $element[$subfield] = [
        '#type' => 'details',
        '#title' => $title,
        '#open' => FALSE,
        '#tree' => TRUE,
      ];

      $element[$subfield]['label'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Label'),
        '#default_value' => $settings[$subfield]['label'],
      ];

      $element[$subfield]['required'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Required'),
        '#default_value' => $settings[$subfield]['required'],
      ];

      if (static::isListAllowed($type)) {
        $element[$subfield]['list'] = [
          '#type' => 'checkbox',
          '#title' => $this->t('Limit allowed values'),
          '#default_value' => $settings[$subfield]['list'],
        ];

        $description[] = $this->t('The possible values this field can contain. Enter one value per line, in the format key|label.');
        $description[] = $this->t('The label will be used in displayed values and edit forms.');
        $description[] = $this->t('The label is optional: if a line contains a single item, it will be used as key and label.');

        $element[$subfield]['allowed_values'] = [
          '#type' => 'textarea',
          '#title' => $this->t('Allowed values list'),
          '#description' => implode('<br/>', $description),
          '#default_value' => $this->allowedValuesString($settings[$subfield]['allowed_values']),
          '#rows' => 10,
          '#element_validate' => [[get_class($this), 'validateAllowedValues']],
          // @see: DoubleField::validateAllowedValues()
          '#storage_type' => $type,
          '#storage_maxlength' => $settings['storage'][$subfield]['maxlength'],
          '#field_name' => $this->getFieldDefinition()->getName(),
          '#entity_type' => $this->getEntity()->getEntityTypeId(),
          '#allowed_values' => $settings[$subfield]['allowed_values'],
          '#states' => [
            'invisible' => [":input[name='settings[$subfield][list]']" => ['checked' => FALSE]],
          ],
        ];
      }
      else {
        $element[$subfield]['list'] = [
          '#type' => 'value',
          '#default_value' => FALSE,
        ];
        $element[$subfield]['allowed_values'] = [
          '#type' => 'value',
          '#default_value' => [],
        ];
      }

      if (in_array($type, ['integer', 'float', 'numeric'])) {
        $element[$subfield]['min'] = [
          '#type' => 'number',
          '#title' => $this->t('Minimum'),
          '#description' => $this->t('The minimum value that should be allowed in this field. Leave blank for no minimum.'),
          '#default_value' => isset($settings[$subfield]['min']) ? $settings[$subfield]['min'] : NULL,
          '#states' => [
            'visible' => [":input[name='settings[$subfield][list]']" => ['checked' => FALSE]],
          ],
        ];
        $element[$subfield]['max'] = [
          '#type' => 'number',
          '#title' => $this->t('Maximum'),
          '#description' => $this->t('The maximum value that should be allowed in this field. Leave blank for no maximum.'),
          '#default_value' => isset($settings[$subfield]['max']) ? $settings[$subfield]['max'] : NULL,
          '#states' => [
            'visible' => [":input[name='settings[$subfield][list]']" => ['checked' => FALSE]],
          ],
        ];
      }
      else {
        $element[$subfield]['min'] = $element[$subfield]['max'] = [
          '#type' => 'value',
          '#default_value' => '',
        ];
      }

      if ($type == 'boolean') {
        $element[$subfield]['on_label'] = [
          '#type' => 'textfield',
          '#title' => $this->t('"On" label'),
          '#default_value' => $settings[$subfield]['on_label'],
        ];
        $element[$subfield]['off_label'] = [
          '#type' => 'textfield',
          '#title' => $this->t('"Off" label'),
          '#default_value' => $settings[$subfield]['off_label'],
        ];
      }
      else {
        $element[$subfield]['on_label'] = [
          '#type' => 'value',
          '#default_value' => $settings[$subfield]['on_label'],
        ];
        $element[$subfield]['off_label'] = [
          '#type' => 'value',
          '#default_value' => $settings[$subfield]['off_label'],
        ];
      }

    }

    return $element;
  }

  /**
   * {@inheritdoc}
   *
   * @TODO: Find a way to disable constraints for default field values.
   */
  public function getConstraints() {

    $constraint_manager = \Drupal::typedDataManager()
      ->getValidationConstraintManager();
    $constraints = parent::getConstraints();

    $settings = $this->getSettings();

    $subconstrains = [];
    foreach (['first', 'second'] as $subfield) {

      $subfield_type = $settings['storage'][$subfield]['type'];

      $is_list = $settings[$subfield]['list'] && static::isListAllowed($subfield_type);
      if ($is_list && $settings[$subfield]['allowed_values']) {
        $allowed_values = array_keys($settings[$subfield]['allowed_values']);
        $subconstrains[$subfield]['AllowedValues'] = $allowed_values;
      }

      if ($subfield_type == 'string' || $subfield_type == 'telephone') {
        $subconstrains[$subfield]['Length']['max'] = $settings['storage'][$subfield]['maxlength'];
      }

      // Allowed values take precedence over the range constraints.
      $numeric_types = ['integer', 'float', 'numeric'];
      if (!$settings[$subfield]['list'] && in_array($subfield_type, $numeric_types)) {
        if (is_numeric($settings[$subfield]['min'])) {
          $subconstrains[$subfield]['Range']['min'] = $settings[$subfield]['min'];
        }
        if (is_numeric($settings[$subfield]['max'])) {
          $subconstrains[$subfield]['Range']['max'] = $settings[$subfield]['max'];
        }
      }

      if ($subfield_type == 'email') {
        $subconstrains[$subfield]['Length']['max'] = Email::EMAIL_MAX_LENGTH;
      }

      if ($settings[$subfield]['required']) {
        // NotBlank validator is not suitable for booleans because it does not
        // recognize '0' as an empty value.
        if ($subfield_type == 'boolean') {
          $subconstrains[$subfield]['NotEqualTo']['value'] = 0;
          $subconstrains[$subfield]['NotEqualTo']['message'] = $this->t('This value should not be blank.');
        }
        else {
          $subconstrains[$subfield]['NotBlank'] = [];
        }
      }

    }

    $constraints[] = $constraint_manager->create('ComplexData', $subconstrains);

    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $settings = $field_definition->getSettings();

    $columns = [];
    foreach (['first', 'second'] as $subfield) {

      $type = $settings['storage'][$subfield]['type'];

      $columns[$subfield] = [
        'not null' => FALSE,
        'description' => ucfirst($subfield) . ' subfield value.',
      ];

      switch ($type) {
        case 'string':
        case 'telephone':
          $columns[$subfield]['type'] = 'varchar';
          $columns[$subfield]['length'] = $settings['storage'][$subfield]['maxlength'];
          break;

        case 'text':
          $columns[$subfield]['type'] = 'text';
          $columns[$subfield]['size'] = 'big';
          break;

        case 'integer':
          $columns[$subfield]['type'] = 'int';
          $columns[$subfield]['size'] = 'normal';
          break;

        case 'float':
          $columns[$subfield]['type'] = 'float';
          // Big makes the float behaviour less surprising.
          $columns[$subfield]['size'] = 'big';
          break;

        case 'boolean':
          $columns[$subfield]['type'] = 'int';
          $columns[$subfield]['size'] = 'tiny';
          break;

        case 'numeric':
          $columns[$subfield]['type'] = 'numeric';
          $columns[$subfield]['precision'] = $settings['storage'][$subfield]['precision'];
          $columns[$subfield]['scale'] = $settings['storage'][$subfield]['scale'];
          break;

        case 'email':
          $columns[$subfield]['type'] = 'varchar';
          $columns[$subfield]['length'] = Email::EMAIL_MAX_LENGTH;
          break;

        case 'uri':
          $columns[$subfield]['type'] = 'varchar';
          $columns[$subfield]['length'] = 2048;
          break;

        case 'datetime_iso8601':
          $columns[$subfield]['type'] = 'varchar';
          $columns[$subfield]['length'] = 20;
          break;
      }
    }

    return ['columns' => $columns];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $subfield_types = self::subfieldTypes();

    $settings = $field_definition->getSettings();
    $properties = [];
    foreach (['first', 'second'] as $subfield) {

      $subfield_type = $settings['storage'][$subfield]['type'];
      // Typed data are slightly different from schema the definition.
      if ($subfield_type == 'text' || $subfield_type == 'telephone') {
        $subfield_type = 'string';
      }
      elseif ($subfield_type == 'numeric') {
        $subfield_type = 'float';
      }

      $properties[$subfield] = DataDefinition::create($subfield_type)
        ->setLabel($subfield_types[$subfield_type]);
    }

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $settings = $this->getSettings();
    foreach (['first', 'second'] as $subfield) {
      if ($settings['storage'][$subfield]['type'] == 'boolean') {
        // Booleans can be 1 or 0.
        if ($this->{$subfield} == 1) {
          return FALSE;
        }
      }
      elseif ($this->{$subfield} !== NULL && $this->{$subfield} !== '') {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Element validate callback for subfield allowed values.
   *
   * @param array $element
   *   An associative array containing the properties and children of the
   *   generic form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form for the form this element belongs to.
   *
   * @see \Drupal\Core\Render\Element\FormElement::processPattern()
   */
  public static function validateAllowedValues(array $element, FormStateInterface $form_state) {
    $values = static::extractAllowedValues($element['#value']);

    // Check if keys are valid for the field type.
    foreach ($values as $key => $value) {
      switch ($element['#storage_type']) {

        case 'string':
          // @see \Drupal\options\Plugin\Field\FieldType\ListStringItem::validateAllowedValue()
          if (mb_strlen($key) > $element['#storage_maxlength']) {
            $error_message = t(
              'Allowed values list: each key must be a string at most @maxlength characters long.',
              ['@maxlength' => $element['#storage_maxlength']]
            );
            $form_state->setError($element, $error_message);
          }
          break;

        case 'integer':
          // @see \Drupal\options\Plugin\Field\FieldType\ListIntegerItem::validateAllowedValue()
          if (!preg_match('/^-?\d+$/', $key)) {
            $form_state->setError($element, ('Allowed values list: keys must be integers.'));
          }
          break;

        case 'float':
        case 'numeric':
          // @see \Drupal\options\Plugin\Field\FieldType\ListFloatItem::validateAllowedValue()
          if (!is_numeric($key)) {
            $form_state->setError($element, ('Allowed values list: each key must be a valid integer or decimal.'));
          }
          break;

      }
    }

    $form_state->setValueForElement($element, $values);
  }

  /**
   * Extracts the allowed values array from the allowed_values element.
   *
   * @param string $string
   *   The raw string to extract values from.
   *
   * @return array
   *   The array of extracted key/value pairs.
   *
   * @see \Drupal\options\Plugin\Field\FieldType\ListTextItem::extractAllowedValues()
   */
  protected static function extractAllowedValues($string) {

    $values = [];

    $list = explode("\n", $string);
    $list = array_map('trim', $list);
    $list = array_filter($list, 'strlen');

    foreach ($list as $text) {
      // Check for an explicit key.
      if (preg_match('/(.*)\|(.*)/', $text, $matches)) {
        // Trim key and value to avoid unwanted spaces issues.
        $key = trim($matches[1]);
        $value = trim($matches[2]);
      }
      else {
        $key = $value = $text;
      }
      $values[$key] = $value;
    }

    return $values;
  }

  /**
   * Generates a string representation of an array of 'allowed values'.
   *
   * This string format is suitable for edition in a textarea.
   *
   * @param array $values
   *   An array of values, where array keys are values and array values are
   *   labels.
   *
   * @return string
   *   The string representation of the $values array:
   *    - Values are separated by a carriage return.
   *    - Each value is in the format "value|label" or "value".
   */
  protected function allowedValuesString(array $values) {
    $lines = [];
    foreach ($values as $key => $value) {
      $lines[] = "$key|$value";
    }
    return implode("\n", $lines);
  }

  /**
   * Returns available subfield storage types.
   */
  public static function subfieldTypes() {
    $type_options = [
      'boolean' => t('Boolean'),
      'string' => t('Text'),
      'text' => t('Text (long)'),
      'integer' => t('Integer'),
      'float' => t('Float'),
      'numeric' => t('Decimal'),
      'email' => t('Email'),
      'telephone' => t('Telephone'),
      'datetime_iso8601' => t('Date'),
      // We only allow external links. So this should be URL from the user side.
      'uri' => t('Url'),
    ];
    return $type_options;
  }

  /**
   * {@inheritdoc}
   */
  public static function fieldSettingsToConfigData(array $settings) {
    foreach (['first', 'second'] as $subfield) {
      $structured_values = [];
      foreach ($settings[$subfield]['allowed_values'] as $value => $label) {
        $structured_values[] = [
          'value' => $value,
          'label' => $label,
        ];
      }
      $settings[$subfield]['allowed_values'] = $structured_values;
    }
    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public static function fieldSettingsFromConfigData(array $settings) {
    foreach (['first', 'second'] as $subfield) {
      $structured_values = [];
      foreach ($settings[$subfield]['allowed_values'] as $item) {
        $structured_values[$item['value']] = $item['label'];
      }
      $settings[$subfield]['allowed_values'] = $structured_values;

    }
    return $settings;
  }

  /**
   * Checks if list option is allowed for a given sub-field type.
   */
  public static function isListAllowed($subfield_type) {
    $list_types = [
      'string',
      'integer',
      'float',
      'numeric',
      'email',
      'telephone',
      'uri',
      'datetime_iso8601',
    ];
    return in_array($subfield_type, $list_types);
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $settings = $field_definition->getSettings();

    $data = [];
    foreach (['first', 'second'] as $subfield) {

      // If allowed values are limited pick one of them from field settings.
      if ($settings[$subfield]['list']) {
        $data[$subfield] = array_rand($settings[$subfield]['allowed_values']);
        continue;
      }

      switch ($settings['storage'][$subfield]['type']) {

        // @see \Drupal\Core\Field\Plugin\Field\FieldType\BooleanItem::generateSampleValue()
        case 'boolean':
          $data[$subfield] = (bool) mt_rand(0, 1);
          break;

        // @see \Drupal\datetime\Plugin\Field\FieldType\DateTimeItem::generateSampleValue()
        case 'datetime_iso8601':
          $date_type = $settings['storage'][$subfield]['datetime_type'];
          $timestamp = \Drupal::time()->getRequestTime() - mt_rand(0, 86400 * 365);
          $storage_format = $date_type == 'date'
            ? self::DATETIME_DATE_STORAGE_FORMAT
            : self::DATETIME_DATETIME_STORAGE_FORMAT;
          $data[$subfield] = gmdate($storage_format, $timestamp);
          break;

        // @see \Drupal\Core\Field\Plugin\Field\FieldType\StringItem::generateSampleValue()
        case 'string':
          $data[$subfield] = (new Random())->word(mt_rand(1, $settings['storage'][$subfield]['maxlength']));
          break;

        // @see \Drupal\text\Plugin\Field\FieldType\TextItemBase::generateSampleValue()
        case 'text':
          $data[$subfield] = (new Random())->paragraphs(5);
          break;

        // @see \Drupal\Core\Field\Plugin\Field\FieldType\IntegerItem::generateSampleValue()
        case 'integer':
          $min = is_numeric($settings[$subfield]['min']) ? $settings[$subfield]['min'] : -1000;
          $max = is_numeric($settings[$subfield]['max']) ? $settings[$subfield]['max'] : 1000;
          $data[$subfield] = mt_rand($min, $max);
          break;

        // @see \Drupal\Core\Field\Plugin\Field\FieldType\FloatItem::generateSampleValue()
        case 'float':
          $settings = $field_definition->getSettings();
          $precision = rand(10, 32);
          $scale = rand(1, 5);
          $max = is_numeric($settings[$subfield]['min']) ? $settings[$subfield]['min'] : pow(10, ($precision - $scale)) - 1;
          $min = is_numeric($settings[$subfield]['max']) ? $settings[$subfield]['max'] : -pow(10, ($precision - $scale)) + 1;
          $random_decimal = $min + mt_rand() / mt_getrandmax() * ($max - $min);
          $data[$subfield] = floor($random_decimal * pow(10, $scale)) / pow(10, $scale);
          break;

        // @see \Drupal\Core\Field\Plugin\Field\FieldType\DecimalItem::generateSampleValue()
        case 'numeric':
          $precision = $settings['storage'][$subfield]['precision'] ?: 10;
          $scale = $settings['storage'][$subfield]['scale'] ?: 2;
          $min = is_numeric($settings[$subfield]['min']) ? $settings[$subfield]['min'] : -pow(10, ($precision - $scale)) + 1;
          $max = is_numeric($settings[$subfield]['max']) ? $settings[$subfield]['max'] : pow(10, ($precision - $scale)) - 1;

          $set_decimal_digits = function ($decimal) {
            $digits = 0;
            while ($decimal - round($decimal)) {
              $decimal *= 10;
              $digits++;
            }
            return $digits;
          };

          $decimal_digits = $set_decimal_digits($max);
          $decimal_digits = max($set_decimal_digits($min), $decimal_digits);
          $scale = rand($decimal_digits, $scale);
          $random_decimal = $min + mt_rand() / mt_getrandmax() * ($max - $min);
          $data[$subfield] = floor($random_decimal * pow(10, $scale)) / pow(10, $scale);
          break;

        // @see \Drupal\Core\Field\Plugin\Field\FieldType\EmailItem::generateSampleValue()
        case 'email':
          $data[$subfield] = strtolower((new Random())->name()) . '@example.com';
          break;

        // @see \Drupal\telephone\Plugin\Field\FieldType\TelephoneItem::generateSampleValue()
        case 'telephone':
          $data[$subfield] = mt_rand(pow(10, 8), pow(10, 9) - 1);
          break;

        // @see \Drupal\link\Plugin\Field\FieldType\LinkItem::generateSampleValue()
        case 'uri':
          $random = new Random();
          $tlds = ['com', 'net', 'gov', 'org', 'edu', 'biz', 'info'];
          $domain_length = mt_rand(7, 15);
          $protocol = mt_rand(0, 1) ? 'https' : 'http';
          $www = mt_rand(0, 1) ? 'www' : '';
          $domain = $random->word($domain_length);
          $tld = $tlds[mt_rand(0, (count($tlds) - 1))];
          $data[$subfield] = "$protocol://$www.$domain.$tld";
          break;

      }
    }

    return $data;
  }

  /**
   * Creates date object for a given subfield using storage timezone.
   */
  public function createDate($subfield) {
    if ($this->{$subfield}) {
      $is_date_only = $this->getSetting('storage')[$subfield]['datetime_type'] == 'date';
      $format = $is_date_only ? static::DATETIME_DATE_STORAGE_FORMAT : static::DATETIME_DATETIME_STORAGE_FORMAT;
      $date = DrupalDateTime::createFromFormat($format, $this->{$subfield}, static::DATETIME_STORAGE_TIMEZONE);
      if ($is_date_only) {
        $date->setDefaultDateTime();
      }
      return $date;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getSettings() {
    $settings = parent::getSettings();
    foreach (['first', 'second'] as $subfield) {
      // BC Layer. The settings below may not be set if site was updated from
      // version below 3.3.
      // @todo Remove this in 4.0.
      if (!isset($settings[$subfield]['label'])) {
        $settings[$subfield]['label'] = '';
      }
    }
    return $settings;
  }

}
