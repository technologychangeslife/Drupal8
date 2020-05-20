<?php

/**
 * @file
 * GEMC Core post update functions.
 */

/**
 * Delete orphaned paragraphs.
 */
function gemc_core_post_update_delete_orphaned(&$sandbox) {
  $context['sandbox'] = &$sandbox;
  $orphan_purger = \Drupal::service('entity_reference_revisions.orphan_purger');
  $orphan_purger->deleteOrphansBatchOperation('paragraph', $context);

  $sandbox['#finished'] = $context['finished'];
}
