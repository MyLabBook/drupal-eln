<?php

namespace Drupal\existing_values_autocomplete_widget\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Utility\Tags;
use Drupal\Component\Utility\Unicode;

/**
 * Class AutocompleteController.
 */
class AutocompleteController extends ControllerBase {
  /**
   * Handleautocomplete.
   *
   * @return string
   *   Return Hello string.
   */
  public function handleAutocomplete(Request $request, $field_name = NULL, $count = 15, $entity_type_id = NULL) {
    $existing_values = [];
    $entity_type_id = $entity_type_id ?: $request->query->get('entity_type_id') ?: 'node';

    if ($input = $request->query->get('q')) {
      $typed_string = Tags::explode($input);
      $typed_string = Unicode::strtolower(array_pop($typed_string));

      $entity_type_manager = \Drupal::entityTypeManager();
      $table_mapping = $entity_type_manager->getStorage($entity_type_id)->getTableMapping();
      $field_table = $table_mapping->getFieldTableName($field_name);
      $field_storage_definitions = \Drupal::service('entity_field.manager')->getFieldStorageDefinitions($entity_type_id)[$field_name];
      $field_column = $table_mapping->getFieldColumnName($field_storage_definitions, 'value');

      $query = \Drupal::database()->select($field_table, 'f');
      $query->fields('f', ['entity_id', $field_column]);
      $query->condition($field_column, $query->escapeLike($typed_string) . '%', 'LIKE');
      $query->distinct(TRUE);
      $results = $query->execute()->fetchAllKeyed();

      foreach ($results as $id => $value) {
        $entity = $entity_type_manager->getStorage($entity_type_id)->load($id);
        if($entity->access('edit')) {
          $existing_values[] = [
            'value' => $value,
            'label' => $value,
          ];
        }
      }
    }

    return new JsonResponse($existing_values);
  }

}
