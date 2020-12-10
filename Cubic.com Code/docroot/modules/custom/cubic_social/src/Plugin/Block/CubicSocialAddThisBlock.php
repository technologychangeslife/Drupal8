<?php

namespace Drupal\cubic_social\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * Provides a 'CubicSocialAddThisBlock' block.
 *
 * @Block(
 *  id = "cubic_social_addthis_block",
 *  admin_label = @Translation("Cubic Social AddThis Block"),
 * )
 */
class CubicSocialAddThisBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['cubic_social_addthis_block'] = [
      '#theme' => 'cubic_social_addthis',
      '#twitter_only' => FALSE,
      '#attached' => array('library' => array('cubic_social/cubic_social_addthis')),
    ];
    return $build;
  }

}
