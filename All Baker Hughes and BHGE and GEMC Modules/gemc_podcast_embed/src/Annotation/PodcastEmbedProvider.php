<?php

namespace Drupal\gemc_podcast_embed\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a PodcastEmbedProvider item annotation object.
 *
 * @Annotation
 */
class PodcastEmbedProvider extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The title of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $title;

}
