<?php

namespace Drupal\double_field\Feeds\Target;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\feeds\FieldTargetDefinition;
use Drupal\feeds\Plugin\Type\Target\FieldTargetBase;

/**
 * Defines a field mapper for Double Field.
 *
 * @FeedsTarget(
 *   id = "double_field",
 *   field_types = {"double_field"}
 * )
 */
class DoubleField extends FieldTargetBase {

  /**
   * {@inheritdoc}
   */
  protected static function prepareTarget(FieldDefinitionInterface $field_definition) {
    return FieldTargetDefinition::createFromFieldDefinition($field_definition)
      ->addProperty('first')
      ->addProperty('second');
  }

}
