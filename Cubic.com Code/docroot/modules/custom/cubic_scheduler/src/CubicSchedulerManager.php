<?php

namespace Drupal\cubic_scheduler;

use Drupal\scheduler\SchedulerManager;
use Drupal\scheduler\SchedulerEvent;
use Drupal\scheduler\SchedulerEvents;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\scheduler\Exception\SchedulerMissingDateException;
use Drupal\scheduler\Exception\SchedulerNodeTypeNotEnabledException;

/**
 * Extends scheduler manager.
 */
class CubicSchedulerManager extends SchedulerManager {
  /**
   * Publish scheduled nodes.
   *
   * @return bool
   *   TRUE if any node has been published, FALSE otherwise.
   *
   * @throws \Drupal\scheduler\Exception\SchedulerMissingDateException
   * @throws \Drupal\scheduler\Exception\SchedulerNodeTypeNotEnabledException
   */
  public function publish() {
    // @TODO: \Drupal calls should be avoided in classes.
    // Replace \Drupal::service with dependency injection?
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher */
    $dispatcher = \Drupal::service('event_dispatcher');

    $result = FALSE;
    $action = 'publish';

    // Select all nodes of the types that are enabled for scheduled publishing
    // and where publish_on is less than or equal to the current time.
    $nids = [];
    $scheduler_enabled_types = array_keys(_scheduler_get_scheduler_enabled_node_types($action));

    // WORKBENCH INTEGRATION POINT
    // Per ticket #4449 Adding allRevisions flag to entityQuery to allow publish_on values from revisions to be seen.
    if (!empty($scheduler_enabled_types)) {
      // @TODO: \Drupal calls should be avoided in classes.
      // Replace \Drupal::entityQuery with dependency injection?
      $query = \Drupal::entityQuery('node')
        ->exists('publish_on')
        ->condition('publish_on', REQUEST_TIME, '<=')
        ->condition('type', $scheduler_enabled_types, 'IN')
        ->sort('publish_on')
        ->sort('nid')
        ->allRevisions();
      // Disable access checks for this query.
      // @see https://www.drupal.org/node/2700209
      $query->accessCheck(FALSE);
      $nids = $query->execute();
    }

    // WORKBENCH INTEGRATION POINT
    // Per ticket #4449 Allow revision nids through in the nidList by simply flipping the array; previous methodology is commented
    // Allow other modules to add to the list of nodes to be published.
    // $nids = array_unique(array_merge($nids, $this->nidList($action)));
    // we want the revisions so flip the array
    $nids = array_flip($nids);

    // Allow other modules to alter the list of nodes to be published.
    $this->moduleHandler->alter('scheduler_nid_list', $nids, $action);

    // WORKBENCH INTEGRATION POINT
    // Per ticket #4449 Comment Node::loadMultiple in favor of loading revisions; entityTypeManager loadRevision is preferred methodology
    // @TODO: Node::loadMultiple calls should be avoided in classes.
    // Replace with dependency injection?
    // $nodes = Node::loadMultiple($nids);
    // foreach ($nodes as $node_multilingual) {
    foreach ($nids as $nid) {
      $node_multilingual = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->loadRevision($nid);

      // The API calls could return nodes of types which are not enabled for
      // scheduled publishing, so do not process these. This check can be done
      // once, here, as the setting will be the same for all translations.
      if (!$node_multilingual->type->entity->getThirdPartySetting('scheduler', 'publish_enable', $this->setting('default_publish_enable'))) {
        throw new SchedulerNodeTypeNotEnabledException(sprintf("Node %d '%s' will not be published because node type '%s' is not enabled for scheduled publishing", $node_multilingual->id(), $node_multilingual->getTitle(), node_get_type_label($node_multilingual)));
      }

      $languages = $node_multilingual->getTranslationLanguages();
      foreach ($languages as $language) {
        // The object returned by getTranslation() behaves the same as a $node.
        $node = $node_multilingual->getTranslation($language->getId());

        // If the current translation does not have a publish on value, or it is
        // later than the date we are processing then move on to the next.
        $publish_on = $node->publish_on->value;
        if (empty($publish_on) || $publish_on > REQUEST_TIME) {
          continue;
        }

        // Check that other modules allow the action on this node.
        if (!$this->isAllowed($node, $action)) {
          continue;
        }

        // $node->set('changed', $publish_on) will fail badly if an API call has
        // removed the date. Trap this as an exception here and give a
        // meaningful message.
        // @TODO This will now never be thrown due to the empty(publish_on)
        // check above to cater for translations. Remove this exception?
        if (empty($node->publish_on->value)) {
          $field_definitions = $this->entityManager->getFieldDefinitions('node', $node->getType());
          $field = (string) $field_definitions['publish_on']->getLabel();
          throw new SchedulerMissingDateException(sprintf("Node %d '%s' will not be published because field '%s' has no value", $node->id(), $node->getTitle(), $field));
        }

        // Trigger the PRE_PUBLISH event so that modules can react before the
        // node is published.
        $event = new SchedulerEvent($node);
        $dispatcher->dispatch(SchedulerEvents::PRE_PUBLISH, $event);
        $node = $event->getNode();

        // Update timestamps.
        $node->set('changed', $publish_on);
        $old_creation_date = $node->getCreatedTime();
        if ($node->type->entity->getThirdPartySetting('scheduler', 'publish_touch', $this->setting('default_publish_touch'))) {
          $node->setCreatedTime($publish_on);
        }

        $create_publishing_revision = $node->type->entity->getThirdPartySetting('scheduler', 'publish_revision', $this->setting('default_publish_revision'));
        if ($create_publishing_revision) {
          $node->setNewRevision();
          // Use a core date format to guarantee a time is included.
          // @TODO: 't' calls should be avoided in classes.
          // Replace with dependency injection?
          $node->revision_log = t('Node published by Scheduler on @now. Previous creation date was @date.', [
            '@now' => $this->dateFormatter->format(REQUEST_TIME, 'short'),
            '@date' => $this->dateFormatter->format($old_creation_date, 'short'),
          ]);
        }

        // Unset publish_on so the node will not get rescheduled by subsequent
        // calls to $node->save().
        $node->publish_on->value = NULL;

        // Log the fact that a scheduled publication is about to take place.
        $view_link = $node->link(t('View node'));
        $nodetype_url = Url::fromRoute('entity.node_type.edit_form', ['node_type' => $node->getType()]);
        // @TODO: \Drupal calls should be avoided in classes.
        // Replace \Drupal::l with dependency injection?
        $nodetype_link = \Drupal::l(node_get_type_label($node) . ' ' . t('settings'), $nodetype_url);
        $logger_variables = [
          '@type' => node_get_type_label($node),
          '%title' => $node->getTitle(),
          'link' => $nodetype_link . ' ' . $view_link,
        ];
        $this->logger->notice('@type: scheduled publishing of %title.', $logger_variables);

        // Use the actions system to publish the node.
        //$this->entityManager->getStorage('action')->load('node_publish_action')->getPlugin()->execute($node);

        // Invoke the event to tell Rules that Scheduler has published the node.
        if ($this->moduleHandler->moduleExists('scheduler_rules_integration')) {
          _scheduler_rules_integration_dispatch_cron_event($node, 'publish');
        }

        // WORKBENCH INTEGRATION POINT
        // Per ticket #4449 Adding workbench override to play nicely with scheduler
        $node->moderation_state->setValue('published');

        $event->getNode()->save();

        // WORKBENCH INTEGRATION POINT
        // Per ticket #4449 Reordering the event dispatch to ensure that all revisions have published_on cleared
        // Trigger the PUBLISH event so that modules can react after the node is
        // published.
        $event = new SchedulerEvent($node);
        $dispatcher->dispatch(SchedulerEvents::PUBLISH, $event);

        $result = TRUE;
      }
    }

    return $result;
  }

  /**
   * Unpublish scheduled nodes.
   *
   * @return bool
   *   TRUE if any node has been unpublished, FALSE otherwise.
   *
   * @throws \Drupal\scheduler\Exception\SchedulerMissingDateException
   * @throws \Drupal\scheduler\Exception\SchedulerNodeTypeNotEnabledException
   */
  public function unpublish() {
    // @TODO: \Drupal calls should be avoided in classes.
    // Replace \Drupal::service with dependency injection?
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher */
    $dispatcher = \Drupal::service('event_dispatcher');

    $result = FALSE;
    $action = 'unpublish';

    // Select all nodes of the types that are enabled for scheduled unpublishing
    // and where unpublish_on is less than or equal to the current time.
    $nids = [];
    $scheduler_enabled_types = array_keys(_scheduler_get_scheduler_enabled_node_types($action));

    // WORKBENCH INTEGRATION POINT
    // Per ticket #4449 Adding allRevisions flag to entityQuery to allow unpublish_on values from revisions to be seen.
    if (!empty($scheduler_enabled_types)) {
      // @TODO: \Drupal calls should be avoided in classes.
      // Replace \Drupal::entityQuery with dependency injection?
      $query = \Drupal::entityQuery('node')
        ->exists('unpublish_on')
        ->condition('unpublish_on', REQUEST_TIME, '<=')
        ->condition('type', $scheduler_enabled_types, 'IN')
        ->sort('unpublish_on')
        ->sort('nid');
      // Disable access checks for this query.
      // @see https://www.drupal.org/node/2700209
      $query->accessCheck(FALSE);
      $nids = $query->execute();
    }

    // WORKBENCH INTEGRATION POINT
    // Per ticket #4449 Allow revision nids through in the nidList by simply flipping the array; previous methodology is commented
    // Allow other modules to add to the list of nodes to be published.
    // $nids = array_unique(array_merge($nids, $this->nidList($action)));
    // we want the revisions so flip the array
    $nids = array_flip($nids);

    // Allow other modules to alter the list of nodes to be unpublished.
    $this->moduleHandler->alter('scheduler_nid_list', $nids, $action);

    // WORKBENCH INTEGRATION POINT
    // Per ticket #4449 Comment Node::loadMultiple in favor of loading revisions; entityTypeManager loadRevision is preferred methodology
    // @TODO: Node::loadMultiple calls should be avoided in classes.
    // Replace with dependency injection?
    // $nodes = Node::loadMultiple($nids);
    // foreach ($nodes as $node_multilingual) {
    foreach ($nids as $nid) {
      $node_multilingual = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->loadRevision($nid);

      // The API calls could return nodes of types which are not enabled for
      // scheduled unpublishing. Do not process these.
      if (!$node_multilingual->type->entity->getThirdPartySetting('scheduler', 'unpublish_enable', $this->setting('default_unpublish_enable'))) {
        throw new SchedulerNodeTypeNotEnabledException(sprintf("Node %d '%s' will not be unpublished because node type '%s' is not enabled for scheduled unpublishing", $node_multilingual->id(), $node_multilingual->getTitle(), node_get_type_label($node_multilingual)));
      }

      $languages = $node_multilingual->getTranslationLanguages();
      foreach ($languages as $language) {
        // The object returned by getTranslation() behaves the same as a $node.
        $node = $node_multilingual->getTranslation($language->getId());

        // If the current translation does not have an unpublish on value, or it
        // is later than the date we are processing then move on to the next.
        $unpublish_on = $node->unpublish_on->value;
        if (empty($unpublish_on) || $unpublish_on > REQUEST_TIME) {
          continue;
        }

        // Do not process the node if it still has a publish_on time which is in
        // the past, as this implies that scheduled publishing has been blocked
        // by one of the hook functions we provide, and is still being blocked
        // now that the unpublishing time has been reached.
        $publish_on = $node->publish_on->value;
        if (!empty($publish_on) && $publish_on <= REQUEST_TIME) {
          continue;
        }

        // Check that other modules allow the action on this node.
        if (!$this->isAllowed($node, $action)) {
          continue;
        }

        // $node->set('changed', $unpublish_on) will fail badly if an API call
        // has removed the date. Trap this as an exception here and give a
        // meaningful message.
        // @TODO This will now never be thrown due to the empty(unpublish_on)
        // check above to cater for translations. Remove this exception?
        if (empty($unpublish_on)) {
          $field_definitions = $this->entityManager->getFieldDefinitions('node', $node->getType());
          $field = (string) $field_definitions['unpublish_on']->getLabel();
          throw new SchedulerMissingDateException(sprintf("Node %d '%s' will not be unpublished because field '%s' has no value", $node->id(), $node->getTitle(), $field));
        }

        // Trigger the PRE_UNPUBLISH event so that modules can react before the
        // node is unpublished.
        $event = new SchedulerEvent($node);
        $dispatcher->dispatch(SchedulerEvents::PRE_UNPUBLISH, $event);
        $node = $event->getNode();

        // Update timestamps.
        $old_change_date = $node->getChangedTime();
        $node->set('changed', $unpublish_on);

        $create_unpublishing_revision = $node->type->entity->getThirdPartySetting('scheduler', 'unpublish_revision', $this->setting('default_unpublish_revision'));
        if ($create_unpublishing_revision) {
          $node->setNewRevision();
          // Use a core date format to guarantee a time is included.
          // @TODO: 't' calls should be avoided in classes.
          // Replace with dependency injection?
          $node->revision_log = t('Node unpublished by Scheduler on @now. Previous change date was @date.', [
            '@now' => $this->dateFormatter->format(REQUEST_TIME, 'short'),
            '@date' => $this->dateFormatter->format($old_change_date, 'short'),
          ]);
        }
        // Unset unpublish_on so the node will not get rescheduled by subsequent
        // calls to $node->save(). Save the value for use when calling Rules.
        $node->unpublish_on->value = NULL;

        // Log the fact that a scheduled unpublication is about to take place.
        $view_link = $node->link(t('View node'));
        $nodetype_url = Url::fromRoute('entity.node_type.edit_form', ['node_type' => $node->getType()]);
        // @TODO: \Drupal calls should be avoided in classes.
        // Replace \Drupal::l with dependency injection?
        $nodetype_link = \Drupal::l(node_get_type_label($node) . ' ' . t('settings'), $nodetype_url);
        $logger_variables = [
          '@type' => node_get_type_label($node),
          '%title' => $node->getTitle(),
          'link' => $nodetype_link . ' ' . $view_link,
        ];
        $this->logger->notice('@type: scheduled unpublishing of %title.', $logger_variables);

        // Use the actions system to publish the node.
        $this->entityManager->getStorage('action')->load('node_unpublish_action')->getPlugin()->execute($node);

        // Invoke event to tell Rules that Scheduler has unpublished this node.
        if ($this->moduleHandler->moduleExists('scheduler_rules_integration')) {
          _scheduler_rules_integration_dispatch_cron_event($node, 'unpublish');
        }

        // WORKBENCH INTEGRATION POINT
        // Per ticket #4449 Adding workbench override to play nicely with scheduler
        $node->moderation_state->setValue('archived');

        $event->getNode()->save();

        // WORKBENCH INTEGRATION POINT
        // Per ticket #4449 Reordering the event dispatch to ensure that all revisions have unpublished_on cleared
        // Trigger the UNPUBLISH event so that modules can react before the node
        // is unpublished.
        $event = new SchedulerEvent($node);
        $dispatcher->dispatch(SchedulerEvents::UNPUBLISH, $event);

        $result = TRUE;
      }
    }

    return $result;
  }
}
