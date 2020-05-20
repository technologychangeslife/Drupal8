<?php

namespace Drupal\bh_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\NodeInterface;
use Drupal\Core\Url;
use Drupal\Component\Utility\UrlHelper;

/**
 * Provides a 'Breakout Banner' block.
 *
 * @Block(
 *  id = "breakout_banner",
 *  admin_label = @Translation("Breakout Banner"),
 * )
 */
class BreakoutBanner extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('bh.breakout_settings');
    $connect['header'] = $config->get('connect_header');
    $connect['description'] = $config->get('connect_description');
    $connect['link'] = $config->get('connect_link');
    $media['header'] = $config->get('media_header');
    $media['description'] = $config->get('media_description');
    $media['link'] = $config->get('media_link');

    // Add Marketo meta data as query params to contact us link.
    if (!empty($connect['link'])) {
      $external = UrlHelper::isExternal($connect['link']);
      $url_object = \Drupal::service('path.validator')->getUrlIfValid($connect['link']);

      if (!$external && $url_object) {
        $entity = \Drupal::routeMatch()->getParameter('node');

        if ($entity instanceof NodeInterface) {
          if ($entity->getType() == 'product') {
            /** @var \Drupal\bh_marketo\MarketoHelpers $marketoHelpers */
            $marketoHelpers = \Drupal::service('bh_marketo.helpers');

            // Populate meta.
            $options = $marketoHelpers->populateMarketoMeta([], $entity);

            $url_object->setOption('query', $options);
            $connect['link'] = $url_object->toString();
          }
        }

      }
    }

    $build = [
      '#theme' => 'breakout_banner',
      '#connectus' => $connect,
      '#media' => $media,
      '#attached' => [
        'library' => 'bh_blocks/bh-blocks-library'
      ],
    ];

    // No cache.
    $build['#cache']['max-age'] = 0;
    $build['#cache']['contexts'][] = 'url.path';
    $build['#cache']['contexts'][] = 'url.query_args';

    return $build;

  }

}
