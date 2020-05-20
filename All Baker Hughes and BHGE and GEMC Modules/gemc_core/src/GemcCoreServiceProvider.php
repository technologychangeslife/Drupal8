<?php

namespace Drupal\gemc_core;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Overrides the class for the menu link tree.
 */
class GemcCoreServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('menu.active_trail');
    $definition->setClass('Drupal\gemc_core\GemcActiveTrail');
    $definition->addArgument(new Reference('language_manager'));
  }

}
