<?php

namespace Drupal\gemc_core\Extension;

/**
 * Create custom Twig UI extentions.
 */
class UIUtilsExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'ui_util_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('getSocialPageLinks', [
        $this,
        'getSocialPageLinks',
      ], ['is_safe' => ['html']]),
    ];
  }

  /**
   * Social platform links.
   */
  public function getSocialPageLinks() {
    $socialConfig = \Drupal::configFactory()->get('bhge.social_settings');

    $socialLinks = [];
    $socialFields = [
      'facebook',
      'twitter',
      'linkedin',
      'instagram',
      'youtube',
    ];
    foreach ($socialFields as $field) {
      $link = $socialConfig->get($field . '_page_url');
      if (!empty($link)) {
        $socialLinks[$field] = $link;
      }
    }

    return $socialLinks;
  }

}
