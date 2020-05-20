<?php

namespace Drupal\gemc_core;

use Drupal\Core\Menu\MenuActiveTrail;
use Drupal\Core\Menu\MenuLinkManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Lock\LockBackendInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Url;
use Drupal\menu_link_content\Entity\MenuLinkContent;

/**
 * Overrides the class for the file entity normalizer from HAL.
 */
class GemcActiveTrail extends MenuActiveTrail {

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(MenuLinkManagerInterface $menu_link_manager, RouteMatchInterface $route_match, CacheBackendInterface $cache, LockBackendInterface $lock, LanguageManagerInterface $languageManager) {
    parent::__construct($menu_link_manager, $route_match, $cache, $lock);
    $this->languageManager = $languageManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getActiveLink($menu_name = NULL) {
    if ($menu_name == 'main-navigation') {
      $route_name = $this->routeMatch->getRouteName();
      if ($route_name) {
        $route_parameters = $this->routeMatch->getRawParameters()->all();
        $currentUrl = Url::fromRoute($route_name, $route_parameters);
        $langcode = $this->languageManager->getCurrentLanguage()->getId();
        $links[] = 'internal:' . $currentUrl->toString();
        $links[] = 'internal:/' . $currentUrl->getInternalPath();
        $links[] = 'entity:' . $currentUrl->getInternalPath();
        $results = \Drupal::entityQuery('menu_link_content')
          ->condition('field_detail_heading_link', $links, 'IN')
          ->condition('langcode', $langcode)
          ->condition('bundle', $menu_name)
          ->execute();

        if ($results) {
          $found = reset($results);
          $menu_item = MenuLinkContent::load($found);
          if ($menu_item) {
            return $menu_item;
          }
        }
      }
    }

    return parent::getActiveLink($menu_name);
  }

}
