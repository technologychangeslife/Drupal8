<?php

namespace Drupal\cubic_nyse_ticker\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Cache\CacheableJsonResponse;

/**
 * @file
 * Contains \Drupal\cubic_nyse_ticker\Controller\CubicQuoteTickerController.
 */

/**
 * Controller routines for port_events routes.
 */
class CubicQuoteTickerController extends ControllerBase {

  /**
   * Callback for `cubic-nyse-quote.json` API method.
   */
  public function get(Request $request) {
    // Call for the ticket info everytime this endpoint is hit as we are
    // protected by the Drupal url cache.
    $json_content = _cubic_nyse_ticker_json_content();

    // If there is no response then return empty and uncached.
    if (!$json_content) {
      return new JsonResponse([]);
    }

    $json = [
      'change_direction' => $json_content->change_direction,
      'change' => $json_content->change,
      'formatted_amount' => $json_content->formatted_amount,
      'latest_update' => $json_content->latestUpdate,
    ];

    $ttl = 300;
    $response = new CacheableJsonResponse($json, 200);
    $response->getCacheableMetadata()->setCacheMaxAge($ttl);
    $response->getCacheableMetadata()->setCacheContexts(['url.path']);

    $response->setPublic();
    $response->setMaxAge($ttl);

    $request_time = $request->server->get('REQUEST_TIME');
    $expires_time = (new \DateTime)->setTimestamp($request_time + $ttl);
    $response->setExpires($expires_time);

    return $response;
  }

}
