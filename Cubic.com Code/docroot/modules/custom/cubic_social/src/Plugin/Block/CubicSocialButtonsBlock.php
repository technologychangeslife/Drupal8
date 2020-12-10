<?php

namespace Drupal\cubic_social\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * Provides a 'CubicSocialButtonsBlock' block.
 *
 * @Block(
 *  id = "cubic_social_buttons_block",
 *  admin_label = @Translation("Cubic Social Buttons Block"),
 * )
 */
class CubicSocialButtonsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $items = $this->getHardCodedConfig(); // TODO: Remove and replace this function with $this->configuration once this has been developed

    $build = [];
    $build['cubic_social_buttons_block']['#theme'] = 'cubic_social_buttons_block';
    $build['cubic_social_buttons_block']['#items'] = $items;
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getHardCodedConfig() {
    // Simple array that stores our hard coded links.
    $default_config_links = array(
      'instagram' => 'https://www.instagram.com/cubic_corp',
      'youtube' => 'https://www.youtube.com/user/CubicCorporation',
      'twitter' => 'https://www.cubic.com/Twitter',
      'linkedin' => 'http://www.linkedin.com/company/cubic',
      'facebook' => 'http://www.facebook.com/cubiccorporation',
      'vimeo' => 'https://vimeo.com/user15047547',
    );

    // Construct our items.
    $items = array();
    foreach ($default_config_links as $label => $uri) {
      $options = array(
        'attributes' => array(
          'class' => 'social-link social-link-' . $label,
          'target' => '_social',
        ),
      );

      $items[$label] = [
        '#title' => t($label),
        '#type' => 'link',
        '#url' => Url::fromUri($uri, $options),
        'text' => t($label),
        // redundant indexes for use in twig
        'url' => Url::fromUri($uri, $options),
        // redundant indexes for use in twig
      ];
    }
    return $items;
  }


}
