<?php

/**
 * @file
 * Controller batch functions.
 */

/**
 * @param $nids
 * @param $context
 *
 * @throws \Drupal\Core\Entity\EntityStorageException
 */
function cubic_controller_delete_node_revision_batch($vid, &$context) {
  $message = t('Deleting revisions...');
  $results = NULL;

  if (intval($vid) > 0) {
    $results = \Drupal::getContainer()->get('entity_type.manager')->getStorage('node')->deleteRevision($vid);
    $alert = t('Deleting revision: @vid', ['@vid' => $vid]);

    \Drupal::messenger()->addStatus($alert);
  }

  $context['message'] = $message;
  $context['results'][] = $results;
}

/**
 * @param $success
 * @param $results
 * @param $operations
 */
function cubic_controller_delete_node_revision_batch_finished($success, $results, $operations) {
  // The 'success' parameter means no fatal PHP errors were detected. All
  // other error management should be handled using 'results'.
  if ($success) {
    $message = \Drupal::translation()->formatPlural(
      count($results),
      'One revision deleted.', '@count revisions deleted.'
    );
    \Drupal::messenger()->addMessage($message);
  }
  else {
    $message = t('Finished with an error.');
    \Drupal::messenger()->addError($message);
  }
}
