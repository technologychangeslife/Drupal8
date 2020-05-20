<?php

namespace Drupal\bh_share_price\Controller;

use Drupal\Component\FileSystem\FileSystem;
use Drupal\Component\Serialization\Json;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class SharePriceInfoController.
 *
 * @package Drupal\bh_share_price\Controller
 */
class SharePriceInfoController extends ControllerBase {

  public $config;

  /**
   * The Create Function.
   *
   * @inheritdoc
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('config.factory'));
  }

  /**
   * Config Factory.
   *
   * @inheritdoc
   */
  public function __construct($config_factory) {
    $this->config = $config_factory->get('bh.stock_info_settings');
  }

  /**
   * Get info url.
   *
   * @return mixed
   *   url.
   */
  public function getSharePriceInfoUrl() {
    return $this->config->get('stock_info_url');
  }

  /**
   * Get json data from url.
   *
   * @return string|null
   *   json data.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  protected function fetchJsonFromUrl() {
    if ($this->config->get('stock_info_url')) {
      $url = $this->config->get('stock_info_url');
      $client = new Client();
      try {
        $response = $client->request('GET', $url, ['timeout' => 15]);
        return $response->getBody()->getContents();
      }
      catch (RequestException $e) {
        return NULL;
      }
    }
  }

  /**
   * Get json file from disk.
   *
   * @return false|string|null
   *   Data from JSON file.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  protected function fetchJsonFromDisk() {
    $tempDir = new FileSystem();
    $feedFile = $tempDir->getOsTemporaryDirectory() . '/market-info.json';
    if (is_file($feedFile)) {
      if ((filesize($feedFile) > 0) && (time() - filemtime($feedFile) <= 30)) {
        return file_get_contents($feedFile);
      }
    }

    $data = $this->fetchJsonFromUrl();
    if (JSON::decode($data)) {
      file_put_contents($feedFile, $data);
    }
    else {
      $data = NULL;
    }
    return $data;
  }

  /**
   * Get json feed from disk.
   *
   * @return object
   *   Returns the stock quote.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getFeed() {
    $data = $this->fetchJsonFromDisk();
    if ($data) {
      $feed = JSON::decode($data);
      foreach ($feed['data'] as $quote) {
        if ($quote['isDefault'] == 'true') {
          return (object) $quote;
        }
      }
    }
  }

}
