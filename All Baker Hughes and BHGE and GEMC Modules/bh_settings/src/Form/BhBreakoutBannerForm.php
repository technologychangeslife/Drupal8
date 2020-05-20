<?php

namespace Drupal\bh_settings\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\file\Entity\File;
use Drupal\Component\Utility\UrlHelper;

/**
 * Class BhBreakoutBannerForm.
 *
 * @package Drupal\bh_settings\Form
 */
class BhBreakoutBannerForm extends ConfigFormBase {

  protected $config;

  const FORM_FIELDS = [
    'connect_header',
    'connect_description',
    'connect_link',
    'media_header',
    'media_description',
    'media_link',
    
  ];

  /**
   * SpinAwardsSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->config = $this->configFactory->getEditable('bh.breakout_settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bh_breakout_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::FORM_FIELDS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['connect_with_us'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Connect With Us'),
    ];

    $form['connect_with_us']['connect_header'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Header'),
        '#required' => TRUE,
        '#maxlength' => 20,
        '#default_value' => $this->config->get('connect_header'),
    ];  
  
    $form['connect_with_us']['connect_description'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Description'),
        '#required' => TRUE,
        '#maxlength' => 50,
        '#default_value' => $this->config->get('connect_description'),
    ];

    $form['connect_with_us']['connect_link'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Link'),
        '#required' => TRUE,
        '#default_value' => $this->config->get('connect_link'),
    ];

    $form['view_media_contacts'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('View Media Contacts'),
    ];
    $form['view_media_contacts']['media_header'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Header'),
      '#required' => TRUE,
      '#maxlength' => 20,
      '#default_value' => $this->config->get('media_header'),
  ];  

  $form['view_media_contacts']['media_description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#required' => TRUE,
      '#maxlength' => 50,
      '#default_value' => $this->config->get('media_description'),
  ];

  $form['view_media_contacts']['media_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link'),
      '#required' => TRUE,
      '#default_value' => $this->config->get('media_link'),
  ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $getFormElements = array_keys($form_state->getValues());
    // Unset form component field.
    $submit = array_search('submit', $getFormElements);
    $form_build_id = array_search('form_build_id', $getFormElements);
    $form_token = array_search('form_token', $getFormElements);
    $form_id = array_search('form_id', $getFormElements);
    $op = array_search('op', $getFormElements);
    $connect_header = array_search('connect_header', $getFormElements);
    $connect_description = array_search('connect_description', $getFormElements);
    $media_header = array_search('media_header', $getFormElements);
    $media_description = array_search('media_description', $getFormElements);
    unset($getFormElements[$connect_header], $getFormElements[$connect_description], $getFormElements[$media_header], $getFormElements[$media_description],$getFormElements[$submit],$getFormElements[$form_build_id],$getFormElements[$form_token],$getFormElements[$form_id],$getFormElements[$op]);
    foreach ($getFormElements as $val) {
      $uri = $form_state->getValue($val);
      if (!empty($uri)) {
        // Prase the uri.
        $prase_uri = UrlHelper::parse($uri);
        $path = $prase_uri['path'];
        if (!empty($path)) {
          $position = strrpos($path, ".");
          // Path validation if it is not file.
          if ($position === FALSE) {
            $external = UrlHelper::isExternal($path);
            $url_object = \Drupal::service('path.validator')->getUrlIfValid($path);
            if ($external) {
              $form_state->setErrorByName($val, 'Please enter valid Url');
            }
            elseif ($url_object === FALSE) {
              $form_state->setErrorByName($val, 'Please enter valid Url');
            }
          }
        }

      }
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    
    foreach (self::FORM_FIELDS as $formField) {
      $this->config->set($formField, $form_state->getValue($formField))->save();
    }
  }

}
