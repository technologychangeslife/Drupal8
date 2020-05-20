<?php

namespace Drupal\gemc_podcast_embed\Element;

use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Template\Attribute;

/**
 * Providers an element design for embedding script.
 *
 * @RenderElement("gemc_podcast_embed_script")
 */
class PodcastEmbedScript extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return [
      '#theme' => 'gemc_podcast_embed_script',
      '#provider' => '',
      '#url' => '',
      '#query' => [],
      '#target_container_attributes' => [],
      '#attributes' => [],
      '#fragment' => [],
      '#pre_render' => [
        [static::class, 'preRenderInlineFrameEmbed'],
      ],
    ];
  }

  /**
   * Transform the render element structure into a renderable one.
   *
   * @param array $element
   *   An element array before being processed.
   *
   * @return array
   *   The processed and renderable element.
   */
  public static function preRenderInlineFrameEmbed($element) {
    $element['#theme'] .= !empty($element['#provider']) ? '__' . $element['#provider'] : '';

    if (is_array($element['#target_container_attributes'])) {
      $element['#target_container_attributes'] = new Attribute($element['#target_container_attributes']);
    }

    if (is_array($element['#attributes'])) {
      $element['#attributes'] = new Attribute($element['#attributes']);
    }

    return $element;
  }

}
