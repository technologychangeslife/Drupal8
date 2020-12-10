<?php

namespace Drupal\cubic_controller\Form;

/**
 * @file
 * Contains Drupal\cubic_controller\Form\DeleteFilesForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DeleteFilesForm.
 *
 * @package Drupal\welcome\Form
 */
class DeleteFilesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cubic_controller_delete_files_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('File ID'),
      '#description' => $this->t('Enter the file ID (or IDs seperated by commas) to force-delete the file from disk and DB.'),
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
    $file_ids = $form_state->getValue('file_id');
    $file_ids = explode(',', $file_ids);
    $file_ids = array_map('trim', $file_ids);

    if (!empty($file_ids)) {
      /* @var \Drupal\file\FileStorage $controller */
      $controller = \Drupal::getContainer()->get('entity_type.manager')->getStorage('file');
      $files = $controller->loadMultiple($file_ids);

      /* @var \Drupal\file\Entity\File $file */
      foreach ($files as $file) {
        \Drupal::logger('cubic_controller')->notice('Attempting to delete: @title', ['@title' => $file->getFilename()]);
      }

      $controller->delete($files);
    }
  }

}
