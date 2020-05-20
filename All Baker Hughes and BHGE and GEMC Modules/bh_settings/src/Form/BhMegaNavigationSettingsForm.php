<?php

namespace Drupal\bh_settings\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\file\Entity\File;

/**
 * Class SpectrumSettingsForm.
 *
 * @package Drupal\spin_settings\Form
 */
class BhMegaNavigationSettingsForm extends ConfigFormBase {

  protected $config;

  const FORM_FIELDS = [
    'use_mega_nav',
    'use_mega_footer'
  ];

  /**
   * SpinAwardsSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->config = $this->configFactory->getEditable('bh.mega_nav_settings');
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
    return 'bh_mega_nav_settings_form';
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

    $form['mega_nav_site_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Mega Navigation settings'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      'options' => [
        'use_mega_nav' => [
          '#type' => 'checkbox',
          '#title' => t('Use Mega Navigation Menu for this site'),
          '#default_value' => $this->config->get('use_mega_nav'),
          '#description' => $this->t('Check this in order to use mega navigation for this site')
        ],
        'use_mega_footer' => [
          '#type' => 'checkbox',
          '#title' => t('Use Mega Footer for this site'),
          '#default_value' => $this->config->get('use_mega_footer'),
          '#description' => $this->t('Check this in order to use mega footer for this site')
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
