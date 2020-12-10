<?php

namespace Drupal\cubic_search\Plugin\search_api\processor;

trait CubicProcessorRequiredFieldsTrait {
  public function preIndexSave() {
    foreach ($this->index->getDatasources() as $datasource_id => $datasource) {
      $entity_type = $datasource->getEntityTypeId();
      if ($entity_type == 'node') {
        foreach ($this->getRequiredFields() as $propertyInfo) {
          $this->ensureField($datasource_id, $propertyInfo['path'], $propertyInfo['type']);
        }
      }
    }
  }
}
