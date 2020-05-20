<?php

namespace Drupal\bh_filters\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Site\Settings;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BhExternalAPISettingsForm.
 *
 * @package Drupal\bh_filters\Form
 */
class BhFilterViewsConfigForm extends ConfigFormBase {

  protected $config;

  const FORM_FIELDS = [
    'bh_filters_formids',
  ];

  /**
   * SpinAwardsSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->config = $this->configFactory->getEditable('bh.filter_settings');
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
    return 'bh_filter_views_settings';
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

    $form['filter_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Filter Settings'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      'options' => [
        'bh_filters_formids' => [
          '#type' => 'textarea',
          '#title' => $this->t('View Form ids'),
          '#default_value' => $this->config->get('bh_filters_formids'),
          '#maxlength' => 120,
          '#size' => 60,
          '#description' => $this->t(
            'Provide a list of view form ids where you want to treat 
             Better Exposed Filters with our styling. 
             List each id on a new line. ')
        ]
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    foreach (self::FORM_FIELDS as $formField) {
      $this->config->set($formField, $form_state->getValue($formField))->save();
    }
  }

}
