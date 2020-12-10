<?php

namespace Drupal\cubic_controller\Form;

/**
 * @file
 * Contains Drupal\cubic_controller\Form\DeleteNodeRevisionsForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DeleteNodeRevisionsForm.
 *
 * @package Drupal\welcome\Form
 */
class DeleteNodeRevisionsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cubic_controller_delete_node_revisions_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['node_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node ID'),
      '#description' => $this->t('Enter the node ID to delete all revisions.'),
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Delete'),
      '#button_type' => 'primary',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $node_id = $form_state->getValue('node_id');

    if (intval($node_id) > 0) {
      /* @var \Drupal\Core\Entity\EntityTypeManager $entity_type_manager */
      $entity_type_manager = \Drupal::getContainer()->get('entity_type.manager');
      /* @var \Drupal\node\NodeStorage $node_storage */
      $node_storage = $entity_type_manager->getStorage('node');
      /* @var \Drupal\node\Entity\Node $node */
      $node = $node_storage->load($node_id);

      $vids = $node_storage->revisionIds($node);
      array_pop($vids);

      if (count($vids) >= 1) {
        $batch = array(
          'title' => t('Deleting revisions for "@title"...', ['@title' => trim($node->getTitle())]),
          'operations' => [],
          'finished' => 'cubic_controller_delete_node_revision_batch_finished',
          'init_message' => t('Delete node revisions started.'),
          'progress_message' => t('Processed @current out of @total. Estimated time: @estimate.'),
          'error_message' => t('The process has encountered an error.'),
          'file' => drupal_get_path('module', 'cubic_controller') . '/cubic_controller.batch.inc',
        );

        foreach ($vids as $vid) {
          if ($vid !== $node->getLoadedRevisionId()) {
            $batch['operations'][] = [
              'cubic_controller_delete_node_revision_batch',
              [$vid],
            ];
          }
        }

        if (count($batch['operations']) >= 1) {
          batch_set($batch);
        }
      }
    }
  }

}
