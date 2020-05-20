<?php

namespace Drupal\bhge_training_video_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Our config form.
 */
class ApiConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "bhge_training_video_api_config";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bhge_training_video_api.settings');

    $form['live_api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Live API Url'),
      '#default_value' => $config->get('live_api_url'),
    ];

    $form['live_api_username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Live Api Username'),
      '#default_value' => $config->get('live_api_username'),
    ];

    $form['live_api_password'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Live Api password'),
      '#default_value' => $config->get('live_api_password'),
    ];

    $form['dev_api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Dev API Url'),
      '#default_value' => $config->get('dev_api_url'),
    ];

    $form['dev_api_username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Live Api Username'),
      '#default_value' => $config->get('dev_api_username'),
    ];

    $form['dev_api_password'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Dev Api password'),
      '#default_value' => $config->get('dev_api_password'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bhge_training_video_api.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this
      ->configFactory->getEditable('bhge_training_video_api.settings');

    $config
      ->set('live_api_url', $form_state->getValue('live_api_url'))
      ->save();

    $config
      ->set('live_api_username', $form_state->getValue('live_api_username'))
      ->save();

    $config
      ->set('live_api_password', $form_state->getValue('live_api_password'))
      ->save();

    $config
      ->set('dev_api_url', $form_state->getValue('dev_api_url'))
      ->save();

    $config
      ->set('dev_api_username', $form_state->getValue('dev_api_username'))
      ->save();

    $config
      ->set('dev_api_password', $form_state->getValue('dev_api_password'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
