<?php

namespace Drupal\cubic_scheduler;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Modifies the scheduler manager service.
 */
class CubicSchedulerServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Overrides language_manager class to test domain language negotiation.
    $definition = $container->getDefinition('scheduler.manager');
    $definition->setClass('Drupal\cubic_scheduler\CubicSchedulerManager');
  }
}