<?php

namespace Drupal\bh_cke_plugins\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "BhTable" plugin.
 *
 * @CKEditorPlugin(
 *   id = "bh_table",
 *   label = @Translation("BH Table Plugin"),
 *   module = "bh_cke_plugins"
 * )
 */
class BhTable extends PluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface {

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'bh_cke_plugins') . '/js/plugins/bh_table/plugin.js';
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
    $iconImage = drupal_get_path('module', 'bh_cke_plugins') . '/js/plugins/bh_table/images/icon.png';

    return [
      'BhTable' => [
        'label' => t('Add BH Table'),
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
