<?php

/**
 * @file
 * Hooks provided by gemc_podcast_embed.
 */

/**
 * Preprocess podcast script.
 *
 * For podcast providers that use the "podcast_embed_script" element, you can
 * preprocess the element to access the individual components which make up the
 * script including:
 *  - url: The URL of the script, excluding the query parameters.
 *  - query: Individually manipulatable query string parameters.
 *  - target_container_attributes:  The attributes on the separate target HTML
 *    element.
 *  - attributes: The attributes on the script HTML element.
 *  - provider: The provider which has rendered the script, available for
 *    conditional logic only, should not be changed.
 */
function hook_preprocess_podcast_embed_script(&$variables) {
  // Add a class to all script that point to vimeo.
  if ($variables['provider'] == 'vimeo') {
    $variables['attributes']['class'][] = 'vimeo-embed';
  }
}

/**
 * Preprocess script in the format of preprocess_podcast_embed_script__PROVIDER.
 *
 * Allows you to preprocess podcast embed script but only for specific providers.
 * This allows you to, for instance control things specific to each provider.
 * For example, if you wanted to enable a specific buzzsprout feature by altering
 * the query string, you could do so as demonstrated.
 */
function hook_preprocess_podcast_embed_script__buzzsprout(&$variables) {
  // Remove the BuzzSprout logo from buzzsprout embeds.
  $variables['query']['modestbranding'] = '1';
}

/**
 * Alter the gemc_podcast_embed plugin definitions.
 *
 * This hook allows you alter the plugin definitions managed by ProviderManager.
 * This could be useful if you wish to remove a particular definition or perhaps
 * replace one with your own implementation (as demonstrated).
 */
function hook_gemc_podcast_embed_provider_info_alter(&$definitions) {
  // Replace the BuzzSprout provider class with another implementation.
  $definitions['buzzsprout']['class'] = 'Drupal\my_module\CustomBuzzSproutProvider';
}
