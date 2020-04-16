<?php

namespace Drupal\dashboards\Entity;

use Drupal\Core\Config\Entity\ConfigEntityStorage;
use Drupal\Core\Entity\EntityInterface;
use Drupal\layout_builder\Section;

/**
 * Class DashboardStorage.
 *
 * @package Drupal\dashboards\Entity
 */
class DashboardStorage extends ConfigEntityStorage {

  /**
   * {@inheritdoc}
   */
  public function loadMultipleOrderedByWeight(?array $ids = NULL) {
    $entites = parent::loadMultiple($ids);
    usort($entites, function ($a, $b) {
      return $a->get('weight') <=> $b->get('weight');
    });
    return $entites;
  }

  /**
   * {@inheritdoc}
   */
  protected function mapToStorageRecord(EntityInterface $entity) {
    $record = parent::mapToStorageRecord($entity);

    /**
     * @var integer $delta
     * @var \Drupal\layout_builder\Section $section
     */
    foreach ($record['sections'] as $delta => $section) {
      $record['sections'][$delta] = $section->toArray();
    }

    return $record;
  }

  /**
   * {@inheritdoc}
   */
  protected function mapFromStorageRecords(array $records) {
    foreach ($records as &$record) {
      if (!empty($record['sections'])) {
        $sections = &$record['sections'];
        $sections = array_map([Section::class, 'fromArray'], $sections);
      }
    }
    return parent::mapFromStorageRecords($records);
  }

}
