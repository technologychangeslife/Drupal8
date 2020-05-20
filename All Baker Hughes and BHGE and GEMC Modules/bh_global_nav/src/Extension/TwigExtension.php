<?php

namespace Drupal\bh_global_nav\Extension;

/**
 * Create custom Twig UI extensions.
 */
class TwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'twig_util_nav_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('getIconSelect', [
        $this,
        'getIconSelect',
      ], ['is_safe' => ['html']]),
    ];
  }

  /**
   * Get the icons path.
   */
  public function getIconSelect() {
    $config = \Drupal::service('config.factory')->getEditable('icon_select.settings');
    $path = !empty($config->get('path')) ? $config->get('path') : 'icons/icon_select_map.svg';
    return '/sites/bakerhughes/files/' . $path;
  }

}
