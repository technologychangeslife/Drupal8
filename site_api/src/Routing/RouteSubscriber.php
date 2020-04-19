<?php

namespace Drupal\site_api\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $site_config_route = $collection->get('system.site_information_settings');
    if ($route = $site_config_route) {
      $route->setDefault('_form', 'Drupal\site_api\Form\ExtendedSiteInformationForm');
    }
  }

}
