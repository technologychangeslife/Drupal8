<?php

namespace Drupal\bh_cke_plugins\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "Cke_Readmore" plugin, with a CKEditor.
 *
 * @CKEditorPlugin(
 *   id = "readmore",
 *   label = @Translation("Cke_Readmore Plugin")
 * )
 */
class Readmore extends PluginBase implements CKEditorPluginInterface,CKEditorPluginButtonsInterface {

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
  public function getFile() {
    return drupal_get_path('module', 'bh_cke_plugins') . '/js/plugins/readmore/Plugin.js';
  }

  /**
   * @return array
   */
  public function getButtons() {
    $iconImage = drupal_get_path('module', 'bh_cke_plugins') . '/js/plugins/readmore/icon/readmore.png';

    return [
      'Readmore' => [
        'label' => t('readmore icon'),
        'desc' => t('CKEditor Read more'),
        'image' => $iconImage,

      ]
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return [];
  }

}