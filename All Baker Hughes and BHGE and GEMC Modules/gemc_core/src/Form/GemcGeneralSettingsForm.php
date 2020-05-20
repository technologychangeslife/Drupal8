<?php

namespace Drupal\gemc_core\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\file\Entity\File;

/**
 * Class SpectrumSettingsForm.
 *
 * @package Drupal\spin_settings\Form
 */
class GemcGeneralSettingsForm extends ConfigFormBase {

  protected $config;

  const FORM_FIELDS = [
    'internal_site',
    'enable_search',
    'enable_global_nav',
    'use_theme_logo',
    'website_logo',
    'website_favicon',
    'website_logo_alternative_text',
  ];

  /**
   * GemcGeneralSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   File system.
   */
  public function __construct(ConfigFactoryInterface $config_factory, FileSystemInterface $file_system) {
    parent::__construct($config_factory);
    $this->config = $this->configFactory->getEditable('bhge.general_settings');
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('file_system')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bhge_general_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    // return;.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['group_basic_site_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Basic site settings'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      'options' => [
        'enable_search' => [
          '#type' => 'checkbox',
          '#title' => $this->t('Enable search field in navigation'),
          '#default_value' => $this->config->get('enable_search'),
        ],
        'enable_global_nav' => [
          '#type' => 'checkbox',
          '#title' => $this->t('Enable Global Navigation'),
          '#default_value' => $this->config->get('enable_global_nav'),
        ],
        'use_theme_logo' => [
          '#type' => 'checkbox',
          '#title' => $this->t('Use Theme Logo'),
          '#default_value' => $this->config->get('use_theme_logo'),
        ],
        'website_logo' => [
          '#type' => 'managed_file',
          '#title' => $this->t('Website Specific logo'),
          '#default_value' => $this->config->get('website_logo'),
          '#description'          => t('Allowed extensions: svg'),
          '#upload_validators'    => [
            'file_validate_extensions'    => ['svg'],
          ],
        ],
        'website_logo_alternative_text' => [
          '#type' => 'textfield',
          '#title' => $this->t('Website Logo Alternative Text'),
          '#default_value' => $this->config->get('website_logo_alternative_text'),
          '#size' => 60,
        ],
        'website_favicon' => [
          '#type' => 'managed_file',
          '#title' => $this->t('Website Specific Favicon'),
          '#default_value' => $this->config->get('website_favicon'),
          '#description'          => t('Allowed extensions: svg, ico, png, gif, jpg, jpeg'),
          '#upload_validators'    => [
            'file_validate_extensions'    => ['svg ico gif png jpg jpeg'],
          ],
        ],
      ],
    ];

    $form['group_internal_site'] = [
      '#type' => 'details',
      '#title' => $this->t('Internal site'),
      '#description' => $this->t('An internal site will be only available to logged in users. Please configure the filesystem accordingly: an internal site should have private files, not public.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      'options' => [
        'internal_site' => [
          '#type' => 'checkbox',
          '#title' => $this->t('Internal Site'),
          '#default_value' => $this->config->get('internal_site'),
          '#description' => $this->t('WARNING this option should be changed ONLY after initial install. Dont change it after content is created. Also configuration should be re-imported after changing this setting.'),
        ],
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
      if ($formField == 'website_logo' | $formField == 'website_favicon') {
        $image = $form_state->getValue($formField);
        if (!empty($image[0])) {
          $file = File::load($image[0]);
          if (!empty($file)) {
            $file->setPermanent();
            $file->save();
          }
        }
      }
      $this->config->set($formField, $form_state->getValue($formField))->save();
    }

    // Generate or remove file for state detection early in drupal bootstrap.
    $filename = 'public://isinternalsite.dontremove';
    if ($form_state->getValue('internal_site') == 1) {
      file_save_data('Generated by ' . __CLASS__, $filename);
    }
    else {
      $this->fileSystem->delete($filename);
    }
    // Generate or remove file for custom favicon .
    $faviconfilename = 'public://isfaviconset.dontremove';
    if ($form_state->getValue('use_theme_logo') == 1 && !empty($form_state->getValue('website_favicon')) && !empty($form_state->getValue('website_favicon'))) {
      file_save_data('Custom Favicon Generated by ' . __CLASS__, $faviconfilename);
    }
    else {
      $this->fileSystem->delete($faviconfilename);
    }
  }

}
