<?php

namespace Drupal\gemc_podcast_embed\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Validation constraint for the podcast embed field.
 *
 * @Constraint(
 *   id = "PodcastEmbedValidation",
 *   label = @Translation("PodcastEmbed provider constraint", context = "Validation"),
 * )
 */
class PodcastEmbedConstraint extends Constraint {

  /**
   * Message shown when a podcast provider is not found.
   *
   * @var string
   */
  public $message = 'Could not find a podcast provider to handle the given URL.';

}
