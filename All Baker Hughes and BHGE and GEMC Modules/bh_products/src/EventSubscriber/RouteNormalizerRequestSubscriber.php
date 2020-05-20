<?php

namespace Drupal\bh_products\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\RequestHelper;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Normalizes GET requests performing a redirect if required.
 *
 * The normalization can be disabled by setting the "_disable_route_normalizer"
 * request parameter to TRUE. However, this should be done before
 * onKernelRequestRedirect() method is executed.
 */
class RouteNormalizerRequestSubscriber implements EventSubscriberInterface {

  /**
   * Module specific configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Disable router normalizer for product category.
   */
  public function onKernelRequestRedirectContentType(GetResponseEvent $event) {

    $route_match = \Drupal::routeMatch();
    if ($route_match->getRouteName() == 'entity.node.canonical') {
      $node = $route_match->getParameter('node');
      if ($node instanceof NodeInterface) {
        if ($node->getType() == 'product' || $node->getType() == 'product_category') {
          $request = $event->getRequest();
          $request->attributes->set('_disable_route_normalizer', TRUE);
        }
      }
    }

  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onKernelRequestRedirectContentType', 31];
    return $events;
  }

}
