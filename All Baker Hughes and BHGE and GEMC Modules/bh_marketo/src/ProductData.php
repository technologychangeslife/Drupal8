<?php

namespace Drupal\bh_marketo;

use Drupal\Core\Database\Connection;
use Drupal\Core\Url;

/**
 * Load product related data.
 */
class ProductData {

  protected $connection;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The active database connection.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * Get parent section of given nid.
   *
   * @param int $nid
   *   Node id.
   * @param string $ctype
   *   Node type.
   * @param bool $onlyPublished
   *   Load only published.
   *
   * @return mixed
   *   Parent section.
   */
  public function getParentSection($nid, $ctype, $onlyPublished = TRUE) {
    $query = $this->connection->select('node_field_data', 'n');

    if ($ctype == 'product_category') {
      $query->leftJoin('node__field_parent_categories', 'sect', 'n.nid = sect.entity_id');
      $query->addField('sect', 'field_parent_categories_target_id', 'section_id');
    }
    elseif ($ctype == 'product') {
      $query->leftJoin('node__field_parent_categories', 'sect', 'n.nid = sect.entity_id');
      $query->addField('sect', 'field_parent_categories_target_id', 'section_id');
    }

    // Add title.
    $query->fields('n', ['title', 'status']);
    $query->addField('n', 'nid', 'id');

    if ($onlyPublished) {
      $query->condition('n.status', 1);
    }
    $query->condition('n.nid', $nid);
    $query->range(0, 1);
    $result = $query->execute()->fetchObject();
    return $result;
  }

}
