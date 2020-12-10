<?php

/**
 * @file
 * Contains cubic_views\cubic_views.views.inc..
 * Provide a custom views data that isn't tied to any other module. */

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Render\Markup;
use Drupal\field\FieldConfigInterface;
use Drupal\field\FieldStorageConfigInterface;
use Drupal\system\ActionConfigEntityInterface;

/**
 * Implements hook_field_views_data_alter().
 *
 * Views integration for entity reference fields which reference nodes.
 * Adds a term relationship to the default field data.
 *
 * @see views_field_default_views_data()
 *
 * @param array $data
 * @param \Drupal\field\FieldStorageConfigInterface $field_storage
 */
function cubic_views_field_views_data_alter(array &$data, FieldStorageConfigInterface $field_storage) {
  if ($field_storage->getType() == 'entity_reference' && $field_storage->getSetting('target_type') == 'node') {
    foreach ($data as $table_name => $table_data) {
      foreach ($table_data as $field_name => $field_data) {
        if (isset($field_data['filter']) && $field_name != 'delta') {
          $data[$table_name][$field_name]['filter']['id'] = 'node_index_nid';
        }
      }
    }
  }
}
