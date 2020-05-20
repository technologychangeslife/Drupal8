<?php

namespace Drupal\bh_cke_plugins\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "BhBlockQuote" plugin.
 *
 * @CKEditorPlugin(
 *   id = "bh_blockquote",
 *   label = @Translation("BH BlockQuote Plugin"),
 *   module = "bh_cke_plugins"
 * )
 */
class BhBlockQuote extends PluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface {

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'bh_cke_plugins') . '/js/plugins/bh_blockquote/plugin.js';
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
    $iconImage = drupal_get_path('module', 'bh_cke_plugins') . '/js/plugins/bh_blockquote/images/icon.png';

    return [
      'BhBlockquote' => [
        'label' => t('Add BH BlockQuote'),
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
