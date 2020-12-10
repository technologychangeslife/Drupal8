<?php

namespace Drupal\cubic_search\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Processor\ProcessorProperty;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Item\ItemInterface;
use Drupal\core\Site\Settings;

/**
 * Adds General data for all relevant content types
 * These are added automatically in the preIndex step
 * @see GeneralData ::preIndexSave()
 * Values are populated via @see GeneralData ::addFieldValues()
 *
 *
 * @SearchApiProcessor(
 *   id = "cubic_search_general_data",
 *   label = @Translation("Cubic Search: General Data"),
 *   description = @Translation("Adds general data to index"),
 *   stages = {
 *     "add_properties" = 0,
 *     "pre_index_save" = -10
 *   },
 *   locked = false,
 *   hidden = false,
 * )
 */
class GeneralData extends ProcessorPluginBase {
  use CubicProcessorRequiredFieldsTrait;

  const NODE_DATASOURCE = 'entity:node';
  const P_SORT_TITLE = 'sort_title';

  //Custom bucket for full text search
  const P_TEXT_PAYLOAD = 'text_payload';

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    // fields are only available on nodes
    if ($datasource && $datasource->getPluginId() == 'entity:node') {

      // this is where the custom index document property is defined
      $definition = [
        'label' => $this->t('Sortable Title'),
        'description' => $this->t('Text fields aren\'t sortable by default, so pass through a copy of title for sorting purposes'),
        'type' => 'string',
        'processor_id' => $this->getPluginId(),
        'hidden' => TRUE
      ];
      $properties[self::P_SORT_TITLE] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Full Text Bucket'),
        'description' => $this->t('Aggregate Full text from referenced Paragraph Entities'),
        'type' => 'text',
        'processor_id' => $this->getPluginId(),
        'hidden' => TRUE
      ];
      $properties[self::P_TEXT_PAYLOAD] = new ProcessorProperty($definition);
    }

    return $properties;
  }

  /**
   * Implement addFieldValues to map actual values to the definitions from getPropertyDefinitions
   * @param \Drupal\search_api\Item\ItemInterface $item
   */
  public function addFieldValues(ItemInterface $item) {
    $all_fields = $item->getFields();

    // get node/nid from the item
    $node = $item->getOriginalObject()->getValue();

    $fields_helper = $this->getFieldsHelper();

    // add sortable title field
    $sort_title_field = current($fields_helper->filterForPropertyPath($all_fields, self::NODE_DATASOURCE, self::P_SORT_TITLE));
    $sort_title_field->addValue($node->getTitle());

    $full_text = $this->getFullTextContentForNode($node);
    $full_text_field = current($fields_helper->filterForPropertyPath($all_fields, self::NODE_DATASOURCE, self::P_TEXT_PAYLOAD));
    if ($full_text) {
      $full_text_field->addValue(implode(' ', $full_text));
    }
  }

  /**
   * Helper function to aggregate the full text content for the given node
   * This is returned in array form and should be imploded before delivering to SOLR
   *
   * @param $node \Drupal\node\Entity\Node
   *
   * @return array
   */
  private function getFullTextContentForNode($node) {
    $return_text = [];
    $has_paragraphs = [];

    // title and body are already indexed-- @see getRequiredFields

    // retrieve text payload for the given node
    $return_text = $this->getTextPayloadContentForEntity($node);
    $return_text = array_unique($return_text);
    return $return_text;
  }

  /**
   * Helper function to automatically extract text fields for the given entity.
   *
   * @param object $entity
   *   The entity.
   *
   * @return array
   *   Returned payload.
   */
  private function getTextPayloadContentForEntity($entity) {
    $text_payload = [];
    // Tterate through ALL fields on the entity.
    foreach ($entity->getIterator() as $field_name => $field_value) {
      // Ignore any non-custom fields-- e.g. id, nid, etc. only look for field
      // FOO.
      if (stripos($field_name, 'field_') !== 0) {
        continue;
      }

      $field_value_contents = $field_value->getValue();
      if (isset($field_value_contents[0]['target_id'])) {
        // This is an entity reference.
        foreach ($field_value as $value_content) {
          if (isset($value_content->entity)) {
            $value_content = $value_content->entity;
            $value_entity_type = $value_content->getEntityTypeId();
            if ($value_entity_type !== 'file') {
              if ($value_entity_type === 'paragraph') {
                $text_payload += $this->getTextPayloadContentForEntity($value_content);
              }
              else {
                // If we want to add any content from referenced entities other
                // than paragraphs...
                if ($value_content->getEntityTypeId() === 'node') {
                  // TODO add any fields that you want indexed from referenced nodes into this document.
                  /* e.g. $text_payload[] = $value_content->getTitle(); */
                  /* e.g. $text_payload[] = strip_tags($value_content->body->value); */
                  $i = 1;
                }
                else {
                  // Other entity types-- taxonomy term?
                  $i = 2;
                }
              }
            }
          }
        }
      }
      elseif (is_string($field_value->value) && $field_value->value) {
        // If this is a string-based value.
        // note ->value only returns the first value from a field so we still
        // iterate through to pull in all string/text values from a multival
        // field.
        foreach ($field_value as $value_content) {
          // Strip tags before ingesting-- SOLR processing will handle this, but
          // doesn't hurt to take care ourselves since we're touching the data.
          $text_payload[] = strip_tags($value_content->value);
        }
      }
    }
    return $text_payload;
  }

  /**
   * Helper function to return array of fields/types that are required for this processor
   * @return array
   */
  private function getRequiredFields() {
    //TODO add any general case OOB fields here in this format.
    //to get them into the index, disable the processor and re-enable/reindex
    $fields = [
      ['path' => 'nid', 'type' => 'integer'],
      ['path' => 'title', 'type' => 'text'],
      ['path' => 'body', 'type' => 'text'],
      ['path' => self::P_SORT_TITLE, 'type' => 'string'],
      ['path' => 'type', 'type' => 'string'],
      ['path' => 'created', 'type' => 'date'],
      ['path' => self::P_TEXT_PAYLOAD, 'type' => 'text'],
    ];
    return $fields;
  }
}
