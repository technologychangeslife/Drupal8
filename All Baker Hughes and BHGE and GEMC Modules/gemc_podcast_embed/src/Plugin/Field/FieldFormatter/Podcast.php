<?php

namespace Drupal\gemc_podcast_embed\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\gemc_podcast_embed\ProviderManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the podcast field formatter.
 *
 * @FieldFormatter(
 *   id = "gemc_podcast_embed_podcast",
 *   label = @Translation("Podcast"),
 *   field_types = {
 *     "gemc_podcast_embed"
 *   }
 * )
 */
class Podcast extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The embed provider plugin manager.
   *
   * @var \Drupal\gemc_podcast_embed\ProviderManagerInterface
   */
  protected $providerManager;

  /**
   * The logged in user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructs a new instance of the plugin.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Third party settings.
   * @param \Drupal\gemc_podcast_embed\ProviderManagerInterface $provider_manager
   *   The podcast embed provider manager.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The logged in user.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, $settings, $label, $view_mode, $third_party_settings, ProviderManagerInterface $provider_manager, AccountInterface $current_user) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->providerManager = $provider_manager;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('gemc_podcast_embed.provider_manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    foreach ($items as $delta => $item) {
      $provider = $this->providerManager->loadProviderFromInput($item->value);

      if (!$provider) {
        $element[$delta] = ['#theme' => 'gemc_podcast_embed_missing_provider'];
      }
      else {
        $autoplay = $this->currentUser->hasPermission('never autoplay podcasts') ? FALSE : $this->getSetting('autoplay');
        $element[$delta] = $provider->renderEmbedCode($this->getSetting('width'), $this->getSetting('height'), $autoplay);
        $element[$delta]['#cache']['contexts'][] = 'user.permissions';

        $element[$delta] = [
          '#type' => 'container',
          '#target_container_attributes' => [],
          '#attributes' => ['class' => [Html::cleanCssIdentifier(sprintf('gemc-podcast-embed-provider-%s', $provider->getPluginId()))]],
          'children' => $element[$delta],
        ];
//TODO
//        // For responsive podcasts, wrap each field item in it's own container.
//        if ($this->getSetting('responsive')) {
//          $element[$delta]['#attached']['library'][] = 'gemc_podcast_embed/responsive-podcast';
//          $element[$delta]['#attributes']['class'][] = 'gemc-podcast-embed-responsive-podcast';
//        }
      }

    }
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'responsive' => TRUE,
      'width' => '854',
      'height' => '480',
      'autoplay' => TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    // To restore features video_embed_field equivalent as example.
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    // To restore features video_embed_field equivalent as example.
    $summary[] = $this->t('Embedded Podcast template managed.');
    return $summary;
  }

  /**
   * Get an instance of the Podcast field formatter plugin.
   *
   * This is useful because there is a lot of overlap to the configuration and
   * display of a podcast in a WYSIWYG and configuring a field formatter. We
   * get an instance of the plugin with our own WYSIWYG settings shimmed in,
   * as well as a fake field_definition because one in this context doesn't
   * exist. This allows us to reuse aspects such as the form and settings
   * summary for the WYSIWYG integration.
   *
   * @param array $settings
   *   The settings to pass to the plugin.
   *
   * @return static
   *   The formatter plugin.
   */
  public static function mockInstance($settings) {
    return \Drupal::service('plugin.manager.field.formatter')->createInstance('gemc_podcast_embed_podcast', [
      'settings' => !empty($settings) ? $settings : [],
      'third_party_settings' => [],
      'field_definition' => new FieldConfig([
        'field_name' => 'mock',
        'entity_type' => 'mock',
        'bundle' => 'mock',
      ]),
      'label' => '',
      'view_mode' => '',
    ]);
  }

}
