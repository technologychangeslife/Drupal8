<?php

namespace Drupal\site_api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Implementing our example JSON api.
 */
class JsonApiController {

  /**
   * Callback for the API.
   */
  public function renderApi($api_key, $node_dynamic_id) {
    $site_config = \Drupal::config('system.site');
    if ($site_config->get('siteapikey') == $api_key) {
      return new JsonResponse([
        'data' => $this->getResults($node_dynamic_id),
        'method' => 'GET',
      ]);
    }
    else {
      return new JsonResponse([
        'data' => $this->noResults(),
        'method' => 'GET',
      ]);
    }

  }

  /**
   * A helper function returning results.
   */
  public function noResults() {
    return [
      "Access Denied",
    ];
  }

  /**
   * A helper function returning results.
   */
  public function getResults($node_dynamic_id) {
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $node_details = $node_storage->load($node_dynamic_id);
    $title = $node_details->title->value;
    if (empty($title)) {
      $title = "Please check your Node Id. It seems there is no content present in the system for the ID you provided.";
    }
    $body = $node_details->title->value;
    return [
      [
        "Title" => $title,
        "Body" => $body,
      ],
    ];
  }

}
