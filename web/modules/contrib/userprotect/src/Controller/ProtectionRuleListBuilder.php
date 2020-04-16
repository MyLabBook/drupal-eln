<?php

namespace Drupal\userprotect\Controller;

use Drupal\userprotect\Entity\ProtectionRuleInterface;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of protection rules.
 */
class ProtectionRuleListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Name');
    $header['entity'] = $this->t('Protected entity');
    $header['type'] = $this->t('Type');
    $header['protection'] = $this->t('Protection');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $protected_entity = $entity->getProtectedEntity();
    if ($protected_entity instanceof EntityInterface) {
      $row['entity'] = $protected_entity->label();
    }
    else {
      $row['entity'] = new FormattableMarkup('%missing', ['%missing' => $this->t('Missing')]);
    }
    $row['type'] = $entity->getProtectedEntityTypeId();
    $row['protection'] = $this->getProtections($entity);
    return $row + parent::buildRow($entity);
  }

  /**
   * Gets enabled protections for entity as a string.
   *
   * @param \Drupal\userprotect\Entity\ProtectionRuleInterface $entity
   *   The entity the to get protections for.
   *
   * @return string
   *   The enabled protections, comma-separated.
   */
  public function getProtections(ProtectionRuleInterface $entity) {
    $all_protections = $entity->getProtections()->getAll();
    $enabled_protections = $entity->getProtections()->getEnabledPlugins();
    if (count($all_protections) == count($enabled_protections)) {
      return $this->t('All');
    }
    return implode(', ', array_map(function ($item) {
      return $item->label();
    }, $enabled_protections));
  }

}
