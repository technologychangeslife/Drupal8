<?php

namespace Drupal\cke_trademark\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "Trademark Dagger" plugin.
 *
 * @CKEditorPlugin(
 *   id = "trademarkdagger",
 *   label = @Translation("Trademark Dagger"),
 *   module = "cke_trademark"
 * )
 */
class Trademark extends PluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface {

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'cke_trademark') . '/js/plugins/trademark/plugin.js';
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
    $iconImage = drupal_get_path('module', 'cke_trademark') . '/js/plugins/trademark/images/icon.png';

    return [
      'Trademark' => [
        'label' => t('Add Trademark Dagger'),
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
