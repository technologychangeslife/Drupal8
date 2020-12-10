<?php

namespace Drupal\cubic_events\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'cubic_date_range' formatter.
 *
 * @FieldFormatter(
 *   id = "cubic_date_range",
 *   label = @Translation("Cubic Date Range"),
 *   field_types = {
 *     "daterange"
 *   }
 * )
 */
class CubicDateRange extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays a calendar date range.');

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $date_values = $items->first()->getValue();
    $start_time_month = date('M', strtotime($date_values['value']));
    $start_time_day = date('d', strtotime($date_values['value']));
    $start_time = '<span class="month">' . $start_time_month . '</span><span class="day">' . $start_time_day . '</span>';
    $end_time_month = date('M', strtotime($date_values['end_value']));
    $end_time_day = date('d', strtotime($date_values['end_value']));
    $end_time = '<span class="month">' . $end_time_month . '</span><span class="day">' . $end_time_day . '</span>';

    $markup = '<span class="item start">' . $start_time . '</span>';
    if ($date_values['value'] !== $date_values['end_value']) {
      $markup .= '<span class="item end">' . $end_time . '</span>';
    }

    $element[] = ['#markup' => $markup];

    return $element;
  }

}
