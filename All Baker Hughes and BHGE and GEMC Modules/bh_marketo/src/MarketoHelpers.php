<?php

namespace Drupal\bh_marketo;

use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\Node;

/**
 * The MarketoHelpers.
 */
class MarketoHelpers {

  /**
   * Populate Marketo meta data.
   *
   * @param array $meta
   *   The Meta array.
   * @param \Drupal\node\Entity\Node $entity
   *   The node entity.
   *
   * @return array
   *   Returns meta.
   */
  public function populateMarketoMeta($meta = [], $entity) { //phpcs:ignore
    // Populate ProductofInterest with node title.
    $ctype = $entity->getType();
    // Populate meta only on following CTs.
    if (in_array($ctype, ['product_category', 'product'])) {

      if (!empty($entity->title) && !empty($entity->title->value)) {
        $meta['mCProductofInterestGEMKTO'] = urldecode($entity->title->value);
      }

      // Populate N Levels.
      $meta = $this->populateNData($meta, $entity);
    }

    return $meta;
  }

  /**
   * The populateNdata function.
   *
   * @param array $meta
   *   The meta array.
   * @param \Drupal\node\Entity\Node $entity
   *   The entity object.
   *
   * @return array
   *   Return meta array.
   */
  public function populateNdata(array $meta, Node $entity) {

    $hierarchy = $this->getNhierarchy($entity, TRUE);

    foreach ($hierarchy as $key => $item) {
      switch ($key) {

        // N1 - level 1 data of hierarchy.
        case 1:
          $meta['mCProductApplicationGEMkto'] = urldecode($item);
          break;

        // N2 - level 2 data of hierarchy.
        case 2:
          $meta['mCProductCategoryGEMkto'] = urldecode($item);
          break;

        // N3 - level 3 data of hierarchy.
        case 3:
          $meta['mCProductSubCategoryGEMkto'] = urldecode($item);
          break;
      }
    }
    return $meta;
  }

  /**
   * Get N Levels.
   *
   * @param int $node
   *   The node id.
   * @param bool $reverse
   *   Array reverse variable.
   *
   * @return array
   *   Returns hierarchy of nav.
   */
  public function getNhierarchy($node, $reverse = FALSE) {

    /** @var \Drupal\bh_marketo\SectionTrail $trail */
    $trail = \Drupal::service('bh_marketo.section_trail');
    $trail = $trail->currentTrail($node);
    $hierarchy = [];

    if (!empty($trail['parents'])) {
      $hierarchy[] = $trail['current']->title;

      foreach (array_reverse($trail['parents']) as $item) {
        $hierarchy[] = $item->title;
      }

      if ($reverse) {
        $hierarchy = array_reverse($hierarchy);
      }

      $hierarchy = array_combine(range(1, count($hierarchy)), array_values($hierarchy));
    }

    return $hierarchy;
  }

}
