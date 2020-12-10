<?php

namespace Drupal\cubic_solutions\Routing;

use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\Routing\Route;

/**
 * Provides a helper class to determine whether the route is an admin one.
 */
class SolutionContext {

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Construct a new solution context helper instance.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   */
  public function __construct(RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
  }

  /**
   * Gets the active solution name for the current route.
   *
   * @param \Symfony\Component\Routing\Route $route
   *   The current route.
   *
   * @return string
   *   String for matching solution.
   */
  public function activeSolution(Route $route = NULL) {
    $current_uri = \Drupal::request()->getRequestUri();
    $current_solution = NULL;

    if (preg_match('!^/solutions/c4isr/secure-communications/expeditionary-satcom!', $current_uri)) {
      $current_solution = 'c4isr-gatr';
    }
    elseif (preg_match('!^/solutions/c4isr/(secure-communications/personnel-locator-systems|c2isr/enterprise-cloud-solutions|secure-networking/cross-domain)!', $current_uri)) {
      $current_solution = 'c4isr-manuel';
    }
    elseif (preg_match('!^/solutions/c4isr/secure-networking/connectivity-gateways!', $current_uri)) {
      $current_solution = 'c4isr-vocality';
    }
    elseif (preg_match('!^solutions/c4isr/secure-networking!', $current_uri)) {
      $current_solution = 'c4isr-secure-networking';
    }
    elseif (preg_match('!^/solutions/c4isr/c2isr/(cyber-security-services|video-streaming|sensor-data-transport)!', $current_uri)) {
      $current_solution = 'c4isr-teralogics';
    }
    elseif (preg_match('!^/careers/apprenticeships$!', $current_uri)) {
      $current_solution = 'careers-apprenticeships';
    }
    elseif (preg_match('!^/careers/culture$!', $current_uri)) {
      $current_solution = 'corporate';
    }
    elseif (preg_match('!^/careers!', $current_uri)) {
      $current_solution = 'careers';
    }
    elseif (preg_match('!^/cubic-defense-australia!', $current_uri)) {
      $current_solution = 'defense-australia';
    }
    elseif (preg_match('!^/investor-relations!', $current_uri)) {
      $current_solution = 'investor-relations';
    }
    elseif (preg_match('!^/nuvotronics!', $current_uri)) {
      $current_solution = 'nuvotronics';
    }
    elseif (preg_match('!^/solutions/training/.+!', $current_uri)) {
      $current_solution = 'training';
    }
    elseif (preg_match('!^/solutions/transportation/.+!', $current_uri)) {

      if(preg_match('!^/solutions/transportation/revenue-management!', $current_uri)) {
        $current_solution = 'transportation-revenue-management';
      } else {
        $current_solution = 'transportation';
      }

    }
    elseif (preg_match('!^/innovation/success-stories/nextbus-solutions-are-taking-flight!', $current_uri)) {
      $current_solution = 'nextbus-solutions';
    }
    else {
      $current_solution = 'corporate';
    }

    return $current_solution;
  }

}
