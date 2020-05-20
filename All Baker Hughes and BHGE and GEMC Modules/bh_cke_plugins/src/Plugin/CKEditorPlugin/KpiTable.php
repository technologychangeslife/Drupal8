<?php

namespace Drupal\bh_cke_plugins\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "KpiTable" plugin.
 *
 * @CKEditorPlugin(
 *   id = "kpi_table",
 *   label = @Translation("KPI Table Plugin"),
 *   module = "bh_cke_plugins"
 * )
 */
class KpiTable extends PluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface {

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'bh_cke_plugins') . '/js/plugins/kpi_table/plugin.js';
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
    $iconImage = drupal_get_path('module', 'bh_cke_plugins') . '/js/plugins/kpi_table/images/icon.png';

    return [
      'KpiTable' => [
        'label' => t('Add KPI Table'),
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
