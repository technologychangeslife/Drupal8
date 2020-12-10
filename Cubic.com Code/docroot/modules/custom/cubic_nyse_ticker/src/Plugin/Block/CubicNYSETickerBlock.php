<?php

namespace Drupal\cubic_nyse_ticker\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CubicNYSETickerBlock' block.
 *
 * @Block(
 *  id = "cubic_nyseticker_block",
 *  admin_label = @Translation("Cubic NYSE Ticker Block"),
 * )
 */
class CubicNYSETickerBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Provide a default array of values to be filled for the ajax json request.
    $json_content = _cubic_nyse_ticker_json_content_defaults();

    return array(
      'cubic_nyseticker_block' => [
        '#title' => FALSE,
        '#theme' => 'cubic_nyse_ticker_block',
        '#attached' => ['library' => ['cubic_nyse_ticker/cubic-nyse-ticker']],
        '#attributes' => [
          'class' => ['ticker', 'hide'],
          'data-latest-update' => [$json_content->latestUpdate],
        ],
        '#exchange_name' => $json_content->exchange_name,
        '#symbol' => $json_content->symbol,
        '#amount' => $json_content->formatted_amount,
        '#change' => $json_content->change,
        '#change_direction' => $json_content->change_direction,
      ],
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

}
