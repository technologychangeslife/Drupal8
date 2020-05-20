<?php

namespace Drupal\bh_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Product controller for functions used in ajax.
 */
class ProductController extends ControllerBase {

  /**
   * Get child data for all related product categories.
   */
  public function getProductCatChildData() {
    $options = bh_products_get_parent_categories_options();
    $child_count_arr = [];
    if (count($options) > 0) {
      foreach ($options as $key => $option) {
        if (isset($option['child']) && count($option['child']) > 0) {
          // $child_count_arr[$key] = count($option['child']);.
          $html = $this->getChildCheckboxesStructure($key, $option['child']);
          $child_count_arr[$key] = $html;
        }
      }
    }
    return new JsonResponse($child_count_arr);
  }

  /**
   * Get child checkboxes structure .
   */
  public function getChildCheckboxesStructure($cat_id, $child_cat_list) {
    $html = '';
    if (count($child_cat_list) > 0) {
      $field_name = 'product-cat-child-list-' . $cat_id;
      $html = '<div id="' . $field_name . '-wrapper"><ul class="master">';
      foreach ($child_cat_list as $child_cat => $child_cat_title) {
        $html .= '<li class="check-item-wrap"><div class="js-form-item form-item js-form-type-checkbox "><input type="checkbox" name="' . $field_name . '" value="' . $child_cat . '" id="' . $field_name . '-' . $child_cat . '"><label for="' . $field_name . '-' . $child_cat . '">' . $child_cat_title . '</label></div></li>';
      }
      $html .= '</ul></div>';
    }
    return $html;
  }

}
