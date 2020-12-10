<?php

namespace Drupal\cubic_workbench_moderation\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\workbench_moderation\Event\WorkbenchModerationEvents;
use Drupal\workbench_moderation\Event\WorkbenchModerationTransitionEvent;

/**
 * Class CubicWorkbenchModerationEventSubscriber.
 *
 * @package Drupal\cubic_workbench_moderation\EventSubscriber
 */
class CubicWorkbenchModerationEventSubscriber implements EventSubscriberInterface {

  /**
   * Constructs a new CubicWorkbenchModerationEventSubscriber object.
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[WorkbenchModerationEvents::STATE_TRANSITION] = 'notifyContentAdministrators';
    return $events;
  }

  /**
   * Event listener callback.
   *
   * Emails specific list of content administrators when content is published.
   *
   * @param \Drupal\workbench_moderation\Event\WorkbenchModerationTransitionEvent $event
   *   The workbench_moderation transition event.
   */
  public function notifyContentAdministrators(WorkbenchModerationTransitionEvent $event) {
    // We only need to notify if the stateAfter is published}.
    if ($event->getStateAfter() === 'published') {
      // We only want to run this from here if the entity is in the proper
      // workflow from draft or needs review.
      $entity = $event->getEntity();

      if (!$entity->isNew()) {
        cubic_workbench_moderation_notify_content_administrators($entity);
      }
    }
  }

}
