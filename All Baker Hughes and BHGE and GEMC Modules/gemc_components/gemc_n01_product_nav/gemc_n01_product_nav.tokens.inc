<?php

/**
 * @file
 * Builds placeholder replacement tokens for commercial content.
 */

use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Language\LanguageInterface;

/**
 * Implements hook_token_info().
 */
function gemc_n01_product_nav_token_info() {
  $info = [];

  $info['tokens']['node']['commercial-path'] = [
    'name' => t('GEMC Commercial path'),
    'description' => t('Returns the current section parent hierarchy for commercial content GEMC.'),
  ];

  $info['tokens']['node']['parent-industry-path'] = [
    'name' => t('GEMC Parent Industry Title'),
    'description' => t('Returns the current industry segment node\'s parent industry title for GEMC.'),
  ];

  $info['tokens']['term']['source-term-name'] = [
    'name' => t('Translation source term name'),
    'description' => t("The source term name for this current term's translation set."),
    'type' => 'term',
  ];

  return $info;
}

/**
 * Implements hook_tokens().
 */
function gemc_n01_product_nav_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = [];

  $language_manager = \Drupal::languageManager();
  if (isset($options['langcode'])) {
    $langcode = $options['langcode'];
  }
  else {
    $langcode = $language_manager->getCurrentLanguage()->getId();
  }
  if (isset($tokens['commercial-path'])) {
    if (isset($data['entity_type']) && $data['entity_type'] == 'node' && !empty($data['entity']) && in_array($data['entity']->bundle(), ['section', 'product'])) {

      $arr_path = [];
      $original = $tokens['commercial-path'];

      // Initialise static cache.
      if (!isset($nids_parsed)) {
        static $nids_parsed = [];
      }

      // Get parent trail.
      $trail = \Drupal::service('gemc_n01_product_nav.section_trail')->currentTrail($data['entity'], FALSE);

      // Dont parse twice, use cached alias.
      if (isset($nids_parsed[$data['entity']->id()])) {
        $replacements = $nids_parsed[$data['entity']->id()];
      }
      else {
        foreach ($trail['parents'] as $parent) {
          $arr_path[] = $parent->title;
        }
        $arr_path[] = $data['entity']->getTitle();
        foreach ($arr_path as &$path_part) {
          $path_part = \Drupal::service('pathauto.alias_cleaner')->cleanString($path_part, $options);
        }
        $replacements[$original] = implode('/', $arr_path);

        // Fill static cache.
        if ($replacements) {
          $nids_parsed[$data['entity']->id()] = $replacements;
        }
      }
    }
  }

  if (isset($tokens['parent-industry-path'])) {
    if (isset($data['entity_type']) && $data['entity_type'] == 'node' && !empty($data['entity']) && in_array($data['entity']->bundle(), ['industry_segment'])) {
      $original = $tokens['parent-industry-path'];

      // Initialise static cache.
      if (!isset($nids_parsed)) {
        static $nids_parsed = [];
      }

      // Dont parse twice, use cached alias.
      if (isset($nids_parsed[$data['entity']->id()])) {
        $replacements = $nids_parsed[$data['entity']->id()];
      }
      else {
        $lang_code = $data['entity']->get('langcode')->value;
        $query = \Drupal::database()->select('node_field_data', 'n');
        $query->leftJoin('node__field_parent_industry', 'pind', 'n.nid = pind.field_parent_industry_target_id');
        $query->addField('n', 'title');
        $query->condition('pind.entity_id', $data['entity']->id());
        $query->condition('n.langcode', $lang_code);
        $query->range(0, 1);
        $parent_title = $query->execute()->fetchField();

        if (strlen($parent_title) > 0) {
          $path_part = \Drupal::service('pathauto.alias_cleaner')->cleanString($parent_title, $options);
          $replacements[$original] = $path_part;
          // Fill static cache.
          if ($replacements) {
            $nids_parsed[$data['entity']->id()] = $replacements;
          }
        }
      }
    }
  }
  if (isset($tokens['source-term-name'])) {
    if (isset($data['entity_type']) && $data['entity_type'] == 'taxonomy_term' && !empty($data['entity'])) {
      $original = $tokens['source-term-name'];

      // Initialise static cache.
      if (!isset($nids_parsed)) {
        static $nids_parsed = [];
      }

      // Dont parse twice, use cached alias.
      if (isset($nids_parsed[$data['entity']->id()])) {
        $replacements = $nids_parsed['source-term-name'][$data['entity']->id()];
      }
      else {
        $source_term = \Drupal::service('entity.repository')->getTranslationFromContext($data['entity'], LanguageInterface::LANGCODE_DEFAULT);
        $source_term_name = $source_term->getName();
        if (strlen($source_term_name) > 0) {
          $replacements[$original] = $source_term_name;
          // Fill static cache.
          if ($replacements) {
            $nids_parsed[$data['entity']->id()] = $replacements;
          }
        }
      }
    }
  }
  return $replacements;
}
