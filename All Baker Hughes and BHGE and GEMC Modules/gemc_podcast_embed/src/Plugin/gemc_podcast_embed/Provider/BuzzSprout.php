<?php

namespace Drupal\gemc_podcast_embed\Plugin\gemc_podcast_embed\Provider;

use Drupal\gemc_podcast_embed\ProviderPluginBase;

/**
 * A BuzzSprout provider plugin.
 *
 * @PodcastEmbedProvider(
 *   id = "buzzsprout",
 *   title = @Translation("BuzzSprout")
 * )
 */
class BuzzSprout extends ProviderPluginBase {

  /**
   * {@inheritdoc}
   */
  public function renderEmbedCode($width, $height, $autoplay) {
    // Explode show id & episode string out of podcast id.
    $id_vars = explode('|', $this->getPodcastId());
    $url = 'https://www.buzzsprout.com/' . $id_vars[0] . '/' . $id_vars[1];;
    $embed_code = [
      '#type' => 'gemc_podcast_embed_script',
      '#provider' => 'buzzsprout',
      '#url' => $url,
      '#query' => [
        'player' => 'small',
        'container_id' => $this->getTargetContainerID(),
      ],
      '#target_container_attributes' => [
        'id' => $this->getTargetContainerID(),
        'class' => ['podcast-embed'],
      ],
      '#attributes' => [
        //TODO anything?
//        'width' => $width,
//        'height' => $height,
//        'frameborder' => '0',
//        'allowfullscreen' => 'allowfullscreen',
      ],
    ];
    if ($language = $this->getLanguagePreference()) {
      $embed_code['#query']['cc_lang_pref'] = $language;
    }
    return $embed_code;
  }

  /**
   * Get the time index for when the given podcast starts.
   *
   * @return int
   *   The time index where the podcast should start based on the URL.
   */
  protected function getTimeIndex() {
    preg_match('/[&\?]t=((?<hours>\d+)h)?((?<minutes>\d+)m)?(?<seconds>\d+)s?/', $this->getInput(), $matches);

    $hours = !empty($matches['hours']) ? $matches['hours'] : 0;
    $minutes = !empty($matches['minutes']) ? $matches['minutes'] : 0;
    $seconds = !empty($matches['seconds']) ? $matches['seconds'] : 0;

    return $hours * 3600 + $minutes * 60 + $seconds;
  }

  /**
   * Extract the language preference from the URL for use in closed captioning.
   *
   * @return string|FALSE
   *   The language preference if one exists or FALSE if one could not be found.
   */
  protected function getLanguagePreference() {
    preg_match('/[&\?]hl=(?<language>[a-z\-]*)/', $this->getInput(), $matches);
    return isset($matches['language']) ? $matches['language'] : FALSE;
  }

  /**
   * Return target container html ID.
   */
  public function getTargetContainerID() {
    $id_vars = explode('|', $this->getPodcastId());
    preg_match('/(?<episode_id>.[0-9]+)-(.*)/', $id_vars[1], $matches);
//    drupal_set_message($id_vars[1] . '|||'. var_export($matches), 'message');
    return isset($matches['episode_id']) ? 'buzzsprout-player-' . $matches['episode_id'] : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteThumbnailUrl() {
    return;
  }

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    //TODO refine and perfect behaviro.
    preg_match('/^https?:\/\/(www\.)?((?!.*list=)buzzsprout\.com)\/(?<show_id>[0-9A-Za-z_-]*)\/(?<episode_id_name>[0-9A-Za-z_-]*\.js)(.*)/', $input, $matches);
    return isset($matches['show_id']) && isset($matches['episode_id_name']) ? $matches['show_id'] . '|' . $matches['episode_id_name'] : FALSE;
  }

}
