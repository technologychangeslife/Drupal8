<?php

namespace Drupal\cubic_timeline\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CubicTimelineBlock' block.
 *
 * @Block(
 *  id = "cubic_timeline_block",
 *  admin_label = @Translation("Cubic Timeline Block"),
 * )
 */
class CubicTimelineBlock extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['cubic_timeline_block']['#theme'] = 'cubic_timeline_block';
    $build['cubic_timeline_block']['#attached']['library'] = array(
      'cubic_timeline/cubic_timeline_block',
    );
    return $build;
  }

}
