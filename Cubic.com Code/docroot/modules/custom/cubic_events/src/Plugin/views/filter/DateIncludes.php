<?php

namespace Drupal\cubic_events\Plugin\views\filter;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\views\Plugin\views\filter\FilterPluginBase;

/**
 * Filter to handle dates stored as a timestamp.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("date_includes")
 */
class DateIncludes extends FilterPluginBase {

  public $no_operator = TRUE;

  /**
   * Display the filter on the administrative summary.
   */
  public function adminSummary() {
    if ($this->isAGroup()) {
      return $this->t('grouped');
    }
    if (!empty($this->options['exposed'])) {
      return $this->t('exposed');
    }

    $options = $this->operatorOptions('short');
    $output = $options[$this->operator];
    $output .= ' ' . $this->value;

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {
    $options = [];
    $query = \Drupal::entityQuery('node');

    $query->condition('type', 'event')
      ->condition('status', 1)
      ->sort('field_date', 'DESC');

    $result = $query->execute();

    if ($result) {
      /* @var \Drupal\node\NodeStorage $storage */
      $storage = \Drupal::service('entity_type.manager')->getStorage('node');
      /* @var \Drupal\node\Entity\Node $node */
      $nodes = $storage->loadMultiple($result);

      foreach ($nodes as $node) {
        $date = $node->get('field_date')->value;

        if ($date) {
          $date = new DrupalDateTime($date, new \DateTimeZone('UTC'));
          $year = $date->format('Y');

          if (!isset($options[$year])) {
            $options[$year] = $year;
          }
        }
      }

      $form['value'] = [
        '#title' => new TranslatableMarkup('Year'),
        '#type' => 'select',
        '#options' => $options,
        '#size' => NULL,
        '#default_value' => '',
      ];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->ensureMyTable();
    $field = "node__field_date.$this->realField";
    $year = reset($this->value);

    if (!empty($year)) {
      // Pseudo code:
      // In a new WHERE group
      //   Check that table column value for node__field_date.field_date_value
      //     when formatted as 4 digit year
      //     is greater than or equal to $year
      //   And
      //   Check that table column value for node__field_date.field_date_end_value
      //     when formatted as 4 digit year
      //     is less than or equal to $year
      $this->query->addWhereExpression($this->options['group'], "DATE_FORMAT({$field}_value, '%Y') <= $year");
      $this->query->addWhereExpression($this->options['group'], "DATE_FORMAT({$field}_end_value, '%Y') >= $year");
    }
  }

}
