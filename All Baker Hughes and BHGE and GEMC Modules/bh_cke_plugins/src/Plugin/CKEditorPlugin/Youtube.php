<?php

namespace Drupal\bh_cke_plugins\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "Youtube" plugin.
 *
 * @CKEditorPlugin(
 *   id = "youtube",
 *   label = @Translation("Youtube Plugin"),
 *   module = "bh_cke_plugins"
 * )
 */
class Youtube extends PluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface {

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'bh_cke_plugins') . '/js/plugins/youtube/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function isInternal() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    $iconImage = drupal_get_path('module', 'bh_cke_plugins') . '/js/plugins/youtube/images/icon.png';

    return [
      'Youtube' => [
        'label' => t('Add Youtube Video'),
        'image' => $iconImage,
      ]
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled(Editor $editor) {
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return [];
  }

}
