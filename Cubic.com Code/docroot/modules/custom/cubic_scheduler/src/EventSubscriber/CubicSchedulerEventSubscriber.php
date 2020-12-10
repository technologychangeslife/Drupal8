<?php

namespace Drupal\cubic_scheduler\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\scheduler\SchedulerEvents;
use Drupal\scheduler\SchedulerEvent;

class CubicSchedulerEventSubscriber implements EventSubscriberInterface {

  /**
   * Constructs a new CubicSchedulerEventSubscriber object.
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[SchedulerEvents::PUBLISH] = 'publish';
    $events[SchedulerEvents::UNPUBLISH] = 'unpublish';
    return $events;
  }

  /**
   * Event listener callback that sets all revisions with publish_on values to NULL
   * when content is published via scheduler
   *
   * @param \Drupal\scheduler\SchedulerEvent $event
   *   The scheduler publish event.
   */
  public function publish(SchedulerEvent $event) {
    $node = $event->getNode();

    $num_updated = db_update('node_field_revision')
      ->fields(array(
        'publish_on' => NULL,
      ))
      ->condition('nid', $node->id(), '=')
      ->execute();

    \Drupal::logger('cubic_scheduler')->notice('Removed publish_on from %count rows in node_field_revision for %nid', ['%count' => $num_updated, '%nid' => $node->id()]);
  }

  /**
   * Event listener callback that sets all revisions with unpublish_on values to NULL
   * when content is unpublished via scheduler
   *
   * @param \Drupal\scheduler\SchedulerEvent $event
   *   The scheduler unpublish event.
   */
  public function unpublish(SchedulerEvent $event) {
    $node = $event->getNode();

    $num_updated = db_update('node_field_revision')
      ->fields(array(
        'unpublish_on' => NULL,
      ))
      ->condition('nid', $node->id(), '=')
      ->execute();

    \Drupal::logger('cubic_scheduler')->notice('Removed unpublish_on from %count rows in node_field_revision for %nid', ['%count' => $num_updated, '%nid' => $node->id()]);
  }

}
