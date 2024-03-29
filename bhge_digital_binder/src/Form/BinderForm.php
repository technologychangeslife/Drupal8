<?php

namespace Drupal\bhge_digital_binder\Form;

use Drupal\node\Entity\Node;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Render\Element;
use Drupal\taxonomy\Entity\Term;

/**
 * Implements a simple form.
 */
class BinderForm extends FormBase {

  /**
   * Build the simple form.
   *
   * @param array $form
   *   Default form array structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object containing current form state.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $tempstore = \Drupal::service('tempstore.shared')->get('bhge_digital_binder');
    $tempstore->delete('search_result_data', $search_result);
    $form['submit-2'] = [
      '#type' => 'submit',
      '#value' => $this->t('Review'),
      '#prefix' => '<div class="edit-review-2">',
      '#suffix' => '</div><div class="ajax-progress ajax-progress-throbber ajax-through-js"><div class="throbber"> </div><div class="message">Please wait...</div></div>',
    ];
    $form['document_title'] = [
      '#type' => 'textfield',
      // '#title' => t('Search with document title'),.
      '#required' => FALSE,
      '#attributes' => ['placeholder' => t('Search with keywords')],
    ];

    $vid = 'language';
    $engish_term_id = '';
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($terms as $term) {
      if ($term->name == 'English') {
        $term->name;
        $engish_term_id = $term->tid;
      }
    }

    $query = \Drupal::entityQuery('node')
    // Published or not.
      ->condition('status', 1)
    // Content type.
      ->condition('type', 'section');
    // ->pager(100); //specify results to return.
    $query->sort('field_weight', 'DESC');
    $nids = $query->execute();
    foreach ($nids as $nid) {
      $node = Node::load($nid);
      $weight = $node->field_weight->value;
      // $body = $node->body->value;
      $document_query1 = \Drupal::entityQuery('node');
      // Published or not.
      $document_query1->condition('status', 1);
      // Content types.
      $document_query1->condition('type', 'document');
      $document_query1->condition('field_language', $engish_term_id);

      if (!empty($nid)) {
        // Filtering brands.
        $document_query1->condition('select_brand', $nid, 'IN');
      }

      $count = $document_query1->count()->execute();

      if ($count > 0) {
        if ($node->title->value == 'Masoneilan' || $node->title->value == 'Consolidated' || $node->title->value == 'Mooney' || $node->title->value == 'Becker') {
          $nid;
          $title = $node->title->value;
          $brands[$nid] = $title;
        }
        else {
          $product_types[$nid] = $title;
          $product_section_product_with_order[$weight] = $title;
        }
      }
    }

    // The drupal checkboxes form field definition.
    $form['brands'] = [
      '#title' => t('Select Brands'),
      '#type' => 'checkboxes',
      // '#description' => t('Select the brands you want.'),.
      '#options' => $brands,
      '#required' => FALSE,
      '#ajax' => [
        // 'callback' => '::setMessage',
        // 'wrapper' => 'search-result-wrapper',.
        'callback' => '::updateCheckboxes',
        'wrapper' => 'edit-options',
        'method' => 'replace',
      ],
    ];

    $form['product_type_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => 'Select Product',
      '#prefix' => '<div class="product-types-brand"><ul id="myUL">',
      '#suffix' => '</ul></div>',
    ];
    $query_product = \Drupal::entityQuery('node')
    // Published or not.
      ->condition('status', 1)
    // Content type.
      ->condition('type', 'product');
    // ->pager(100); //specify results to return.
    $query_product->sort('field_weight', 'DESC');
    $product_nids = $query_product->execute();
    foreach ($product_nids as $product_id) {
      $node = Node::load($product_id);
      // $body = $node->body->value;
      $document_query2 = \Drupal::entityQuery('node');
      $weight = $node->field_weight->value;
      // Published or not.
      $document_query2->condition('status', 1);
      // Content types.
      $document_query2->condition('type', 'document');
      $document_query2->condition('field_language', $engish_term_id);

      if (!empty($nid)) {
        // Filtering brands.
        $document_query2->condition('field_select_product', $product_id, 'IN');
      }
      $count2 = $document_query2->count()->execute();
      if ($count2 > 0) {
        if ($node->title->value != 'Masoneilan' || $node->title->value != 'Consolidated' || $node->title->value != 'Mooney' || $node->title->value != 'Becker') {
          $title = $node->title->value;
          $product_only_array[$product_id] = $product_id;
          $product_section_product_with_order[$weight] = $title . "(Product Only)";
        }
      }
    }

    if (!empty($product_only_array)) {
      // Combining product section and product array.
      $product_and_section = array_merge($nids, $product_only_array);
    }
    if (empty($product_only_array)) {
      // Combining product section and product array.
      $product_and_section = $nids;
    }
    foreach ($product_and_section as $nid) {
      $node = Node::load($nid);
      // $body = $node->body->value;
      $document_query2 = \Drupal::entityQuery('node');
      $weight = $node->field_weight->value;
      // Published or not.
      $document_query2->condition('status', 1);
      // Content types.
      $document_query2->condition('type', 'document');
      $document_query2->condition('field_language', $engish_term_id);

      if (!empty($nid)) {
        // Filtering brands.
        $document_query2->condition('field_select_product', $nid, 'IN');
      }
      $count2 = $document_query2->count()->execute();
      if ($count2 > 0) {
        $node->title->value;
        if ($node->title->value != 'Masoneilan' || $node->title->value != 'Consolidated' || $node->title->value != 'Mooney' || $node->title->value != 'Becker') {
          $nid;
          $title = $node->title->value;
          $product_types_array[$nid] = $title;
          $product_section_product_with_order[$weight] = $title;
        }
      }
    }

    $vid = 'language';
    $engish_term_id = '';
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($terms as $term) {
      if ($term->name == 'English') {
        $term->name;
        $engish_term_id = $term->tid;
      }
    }

    $document_query_mn = \Drupal::entityQuery('node');
    // Published or not.
    $document_query_mn->condition('status', 1);
    // Content types.
    $document_query_mn->condition('type', 'document');
    $document_query_mn->condition('field_language', $engish_term_id);
    $document_query_mn->sort('created', 'DESC');
    $nids_mn = $document_query_mn->execute();
    foreach ($nids_mn as $nid_mn) {
      $document_node = Node::load($nid_mn);
      $field_part_number = $document_node->get('field_part_number')->getValue();
      $field_select_product = $document_node->get('field_select_product')->getValue();
      $product_type_for_this_model = '';
      foreach ($field_select_product as $key => $value) {
        $pid = $field_select_product[$key]['target_id'];
        $product_node = Node::load($pid);
        $product_type_for_this_model = $product_node->title->value . ',' . $product_type_for_this_model;
      }

      foreach ($field_part_number as $k => $val) {
        $mystring = $val['value'];
        $findme   = 'GEA';
        $findme2  = 'gea';
        $pos      = strpos($mystring, $findme);
        $pos2     = strpos($mystring, $findme2);
        if ($pos === FALSE && $pos2 === FALSE) {
          $model_number[$mystring] = $mystring . '(' . $product_type_for_this_model . ')';
        }
      }
    }

    $vid = 'language';
    $engish_term_id = '';
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($terms as $term) {
      if ($term->name == 'English') {
        $term->name;
        $engish_term_id = $term->tid;
      }
    }

    $vid = 'documents_topic';
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    $field_topic_filter_for_binder = [];
    foreach ($terms as $term) {
      $document_query3 = \Drupal::entityQuery('node');
      // Published or not.
      $document_query3->condition('status', 1);
      // Content types.
      $document_query3->condition('type', 'document');
      $document_query3->condition('field_language', $engish_term_id);

      if (!empty($nid)) {
        // Filtering brands.
        $document_query3->condition('field_topic', $term->tid, 'IN');
      }

      $count3 = $document_query3->count()->execute();
      if ($count3) {
        if ($term->name == 'Catalog' || $term->name == 'Brochures' || $term->name == 'Tech Spec/Fact Sheet' || $term->name == 'Manual' || $term->name == 'Articles and Case Studies') {
          $term_name = $term->name;
          $term_data[$term->tid] = $term_name;
          array_push($field_topic_filter_for_binder, $term->tid);
        }
      }
    }

    // Getting the orders of all Product Types and Product Categories.
    $product_section_product_with_order = [];
    foreach ($product_types_array as $k => $v) {
      $node_details = Node::load($k);
      $title = $node_details->title->value;
      $weight = $node_details->field_weight->value;
      $product_section_product_with_order[$weight][$k] = $title;
    }
    // Sorting array in descending order based on field wieght / order given from backend.
    krsort($product_section_product_with_order);
    foreach ($product_section_product_with_order as $order) {
      foreach ($order as $keys => $values) {
        $parent_node = Node::load($keys);
        foreach ($parent_node->field_section_parents as $reference) {
          $reference->target_id;
          // If you chose "Entity ID" as the display mode for the entity reference field,
          // the target_id is the ONLY value you will have access to.
          // 1 (a node's nid)
          $parent_brand = $reference->target_id;

          // If you chose "Rendered Entity" as the display mode, you'll be able to
          // access the rest of the node's data.
          // "Moby Dick".
          $parent_brand_name = $reference->entity->title->value;

        }
        
        if(empty($parent_brand)) {
         foreach ($parent_node->field_prod_section as $reference) {
          $reference->target_id;
          // If you chose "Entity ID" as the display mode for the entity reference field,
          // the target_id is the ONLY value you will have access to.
          // 1 (a node's nid)
          $parent_brand = $reference->target_id;

          // If you chose "Rendered Entity" as the display mode, you'll be able to
          // access the rest of the node's data.
          // "Moby Dick".
          $parent_brand_name = $reference->entity->title->value;

         }
        }
        $parent_brand; $parent_node->title->value; 
        
        $section_query = \Drupal::entityQuery('node');
        // Published or not.
        $section_query->condition('status', 1);
        // Content types.
        $section_query->condition('type', 'section');
        // Only English docs required.
        $section_query->condition('title', $parent_node->title->value, 'CONTAINS');
        $section_nids = $section_query->execute();
        $parent_brand_array = array();
        foreach ($section_nids as $section_nid) {
          $section_nid;  //print '<br>';
          $section_node = Node::load($section_nid);
          $section_node->title->value; //print '<br>';
          
          foreach ($section_node->field_section_parents as $reference) {
           $reference->target_id;
           // If you chose "Entity ID" as the display mode for the entity reference field,
           // the target_id is the ONLY value you will have access to.
           // 1 (a node's nid)
            $reference->target_id;  //print '<br>';
            //$parent_brand = $reference->target_id.",".$parent_brand;
            array_push($parent_brand_array, $reference->target_id);

           // If you chose "Rendered Entity" as the display mode, you'll be able to
           // access the rest of the node's data.
           // "Moby Dick".
           $parent_brand_name = $reference->entity->title->value;  //print '<br>';

          }
        }
        //print_r($parent_brand_array); print '<br>';
        $result = array_unique($parent_brand_array);
        $parent_ids = '';
        foreach($result as $key => $val) {
         if($val == 546) {
          $val = 1186;
         }
          $parent_ids = $val.",".$parent_ids;
        }
        $parent_ids; //print '<br>';
        $field_select_product_filter = $keys;
        $document_query = \Drupal::entityQuery('node');
        // Published or not.
        $document_query->condition('status', 1);
        // Content types.
        $document_query->condition('type', 'document');
        // Only English docs required.
        $document_query->condition('field_language', $engish_term_id);
        if (!empty($field_select_product_filter)) {
          // Filtering product types.
          $document_query->condition('field_select_product', $field_select_product_filter, 'IN');
        }
        $document_query->condition('field_topic', $field_topic_filter_for_binder, 'IN');
        $document_query->sort('created', 'DESC');
        $document_nids_m = $document_query->execute();
        $numeric_model_number_sequence = [];
        foreach ($document_nids_m as $document_nid) {
          $document_node = Node::load($document_nid);
          $field_part_number = $document_node->get('field_part_number')->getValue();
          $document_node->get('field_select_product')->getValue();
          $field_select_product = $document_node->get('field_select_product')->getValue();
          $product_type_for_this_model = '';
          foreach ($field_select_product as $key => $value) {
            $pid = $field_select_product[$key]['target_id'];
            $product_node = Node::load($pid);
            $product_type_for_this_model = $product_node->title->value . ',' . $product_type_for_this_model;
          }
          // Filtering the model number.
          foreach ($field_part_number as $k => $val) {
            $mystring = $val['value'];
            $findme   = 'GEA';
            $findme2  = 'gea';
            $pos      = strpos($mystring, $findme);
            $pos2     = strpos($mystring, $findme2);
            if ($pos === FALSE && $pos2 === FALSE) {
              if ($mystring == 'General Products') {
                $model_number_sequence[0] = $mystring;
              }
              elseif ($mystring == 'Condensed Catalog') {
                $model_number_sequence[1] = $mystring;
              }
              elseif ($mystring == 'Accessories') {
                $model_number_sequence[2] = $mystring;
              }
              else {
                // $num_check = (explode(" ",$mystring));
                $num_check = substr($mystring, 0, 1);
                if (!is_numeric($num_check)) {
                  array_push($model_number_sequence, $mystring);
                }
                else {

                  array_push($numeric_model_number_sequence, $mystring);
                }
              }
            }
          }

          foreach ($field_part_number as $k => $val) {
            $mystring = $val['value'];
            $findme   = 'GEA';
            $findme2  = 'gea';
            $pos      = strpos($mystring, $findme);
            $pos2     = strpos($mystring, $findme2);
            if ($pos === FALSE && $pos2 === FALSE) {
              // $keys is the ID of Parent product type.
              // $model_number_specific[$mystring.":".$keys] = $mystring;
            }
          }
        }

        $model_number_sequence = array_unique($model_number_sequence);
        ksort($model_number_sequence);
        $last_element_model_number_sequence = $model_number_sequence;
        // Move the internal pointer to the end of the array.
        end($model_number_sequence);
        $key = key($model_number_sequence);
        $numeric_model_number_sequence = array_unique($numeric_model_number_sequence);

        asort($numeric_model_number_sequence);
        $final_model_number_sequence = array_merge($model_number_sequence, $numeric_model_number_sequence);
        foreach ($final_model_number_sequence as $k => $val) {
          $model_number_specific[$val . ":" . $keys] = $val;
        }
        $form['product_type_fieldset']['product_brands_test_' . $keys . ''] = [
          '#type' => 'checkboxes',
          '#options' => $model_number_specific,
          '#ajax' => [
            'callback' => '::updateCheckboxes',
            'wrapper' => 'edit-options',
            'method' => 'replace',
          ],
          '#prefix' => '<li class="model-number-li" data-parent-brand-id="' . $parent_ids . '"><span class="box" id="' . $keys . '"><span class="regular-checkbox"></span>' . $values . '</span>
                        			<ul class="nested ' . $keys . '">',
          '#suffix' => '</ul></li>',
        ];
        $model_number_sequence = [];
        $model_number_specific = [];
        $parent_brand = '';
      }
    }
    // exit();
    foreach ($product_types_array as $keys => $values) {

    }

    // The drupal checkboxes form field definition.
    $form['product_types'] = [
      '#title' => t('Select Product types'),
      '#type' => 'checkboxes',
      // '#description' => t('Select the brands you want.'),.
      '#options' => $product_types_array,
      '#required' => FALSE,
      '#ajax' => [
        // 'callback' => '::setMessage',
        // 'wrapper' => 'search-result-wrapper',.
        'callback' => '::updateCheckboxes',
        'wrapper' => 'edit-options',
        'method' => 'replace',
      ],
    ];

    // The drupal checkboxes form field definition.
    krsort($term_data);
    $form['file_types'] = [
      '#title' => t('Select File Types'),
      '#type' => 'checkboxes',
      '#description' => t('Select the file types you want.'),
      '#options' => $term_data,
      '#required' => FALSE,
      '#ajax' => [
        // 'callback' => '::setMessage',
        // 'wrapper' => 'search-result-wrapper',.
        'callback' => '::updateCheckboxes',
        'wrapper' => 'edit-options',
        'method' => 'replace',
      ],
    ];

    $form['actions_search'] = [
      '#type' => 'button',
      '#value' => $this->t('Search'),
      '#ajax' => [
        // 'callback' => '::setMessage',
        // 'wrapper' => 'search-result-wrapper',.
        'callback' => '::updateCheckboxes',
        'wrapper' => 'edit-options',
        'method' => 'replace',
      ],
    ];

    // Getting default search list.
    $document_query = \Drupal::entityQuery('node');
    // Published or not.
    $document_query->condition('status', 1);
    // Content types.
    $document_query->condition('type', 'document');
    // Only English docs required.
    $document_query->condition('field_language', $engish_term_id);
    // Only 5 types of file types.
    $document_query->condition('field_topic', $field_topic_filter_for_binder, 'IN');
    $document_query->sort('field_topic', 'DESC');
    $document_query->sort('created', 'DESC');
    $document_nids = $document_query->execute();
    foreach ($document_nids as $document_nid) {
      $document_node = Node::load($document_nid);
      $dam_field_file = $document_node->get('field_dam_file')->getValue();
      if (!empty($dam_field_file[0]['target_id'])) {
        $dam_url = $this->getfileurl($dam_field_file[0]['target_id']);
        $dam_url = str_replace("%20", " ", $dam_url);
        $dam_url = str_replace("%28", "(", $dam_url);
        $dam_url = str_replace("%29", ")", $dam_url);
        $files_path = explode("sites", $dam_url);
        $dam_file_path = $_SERVER['DOCUMENT_ROOT'] . base_path() . 'sites' . $files_path[1];
        if (file_exists($dam_file_path)) {
          $file_size = filesize($dam_file_path);
          $file_size_format = $this->formatSizeUnits($file_size);
        }
        else {
          $file_size_format = "File not found.";
        }

      }
      $file_type = $document_node->field_topic->getValue();
      $tid = $file_type[0]['target_id'];
      if (!empty($tid)) {
        $term = Term::load($tid);
        $name = $term->getName();
      }
      $default_search_results_items[$document_nid] = '<div class="file-details">
				<div class="file-title-info file-search-results">
				<div class="binder-title">
					<div class="download-type"></div>
					<div class="file-title">' . $document_node->title->value . '</div>
				</div>
					<div class="file-info"><span class="file-name">' . $name . '</span><span>' . $file_size_format . '</span></div>
				</div>';
      if ($file_size_format != "File not found.") {
        $default_search_results_items[$document_nid] .= '<div class="file-search-results search-results-btn">
					<span class="btn-add btn-add-' . $document_nid . '" data-id = ' . $document_nid . '>ADD</span> 
					<span class="btn-remove btn-remove-' . $document_nid . '" data-id = ' . $document_nid . '>ADDED</span>
				</div>';
      }
      $default_search_results_items[$document_nid] .= '</div>';
    }

    if (!empty($form_state->getValue('file_types')) || !empty($form_state->getValue('document_title')) || !empty($form_state->getValue('brands'))
    || !empty($form_state->getValue('model_number')) || !empty($form_state->getValue('product_types')) || !empty($form_state->getValue('file_types'))
        ) {
      $all_fields = $form_state->getValues();
      $model_number_new_filter = [];
      $product_type_id_array = [];
      foreach ($all_fields as $key_all_fields => $val_all_fields) {
        $my_string = $key_all_fields;
        $find_me   = 'product_brands_test_';
        $pos_find  = strpos($my_string, $find_me);

        // Note our use of ===.  Simply == would not work as expected
        // because the position of 'a' was the 0th (first) character.
        if ($pos_find === FALSE) {
        }
        else {
          foreach ($val_all_fields as $k => $v) {
            if ($k == $v) {
            }
            if (!empty($v)) {
              $mn_and_product_type_id = explode(":", $v);
              $mn = $mn_and_product_type_id[0];
              $product_type_id = $mn_and_product_type_id[1];
              $product_type_id_array[$product_type_id] = $product_type_id;
              $model_number_new_filter[$mn] = $mn;
            }
          }
        }
      }
      $document_title = $form_state->getValue('document_title');
      $model_number = $form_state->getValue('model_number');
      $brands = $form_state->getValue('brands');
      $brands_filter = [];
      foreach ($brands as $key => $value) {
        if ($key == $value) {
          array_push($brands_filter, $value);
        }
      }

      $product_types_array = $form_state->getValue('product_types');
      $products_filter = [];
      foreach ($product_types_array as $key => $value) {
        if ($key == $value) {
          array_push($products_filter, $value);
        }
      }

      $file_types = $form_state->getValue('file_types');
      $files_filter = [];
      foreach ($file_types as $key => $value) {
        if ($key == $value) {
          array_push($files_filter, $value);
        }
      }

      $model_number = $form_state->getValue('model_number');
      $document_title = $form_state->getValue('document_title');
      $select_brand_filter = $brands_filter;
      $field_select_product_filter = $products_filter;
      $field_topic_filter = $files_filter;
      $field_part_number_filter = $model_number;

      $vid = 'language';
      $engish_term_id = '';
      $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
      foreach ($terms as $term) {
        if ($term->name == 'English') {
          $term->name;
          $engish_term_id = $term->tid;
        }
      }

      $document_query = \Drupal::entityQuery('node');
      // Published or not.
      $document_query->condition('status', 1);
      // Content types.
      $document_query->condition('type', 'document');
      // Only English docs required.
      $document_query->condition('field_language', $engish_term_id);

      if (!empty($document_title)) {
        // Content title.
        $document_query->condition('title', $document_title, 'CONTAINS');
      }
      if (!empty($select_brand_filter)) {
        // Filtering brands.
        $document_query->condition('select_brand', $select_brand_filter, 'IN');
      }
      if (!empty($field_select_product_filter)) {
        // Filtering product types.
        $document_query->condition('field_select_product', $field_select_product_filter, 'IN');
      }
      if (!empty($product_type_id_array)) {
        // Filtering product types.
        $document_query->condition('field_select_product', $product_type_id_array, 'IN');
      }
      if (!empty($field_topic_filter)) {
        // Filtering file types.
        $document_query->condition('field_topic', $field_topic_filter, 'IN');
      }
      if (empty($field_topic_filter)) {
        // Filtering file types.
        // If no file types selected default 5 file types will apply.
        $document_query->condition('field_topic', $field_topic_filter_for_binder, 'IN');
      }
      // $field_part_number_filter; previouls model number filter.
      // new model number filter $model_number_new_filter;
      if (!empty($model_number_new_filter)) {
        // Filtering file types.
        $document_query->condition('field_part_number', $model_number_new_filter, 'IN');
      }

      $document_query->sort('field_topic', 'DESC');
      $document_query->sort('created', 'DESC');
      $document_nids = $document_query->execute();

      // $response = new AjaxResponse();
      $document_title = '';
      $x = 1;

      foreach ($document_nids as $document_nid) {
        $document_node = Node::load($document_nid);

        $dam_field_file = $document_node->get('field_dam_file')->getValue();
        if (!empty($dam_field_file[0]['target_id'])) {
          $dam_url = $this->getfileurl($dam_field_file[0]['target_id']);
          $dam_url = str_replace("%20", " ", $dam_url);
          $dam_url = str_replace("%28", "(", $dam_url);
          $dam_url = str_replace("%29", ")", $dam_url);
          $files_path = explode("sites", $dam_url);
          $dam_file_path = $_SERVER['DOCUMENT_ROOT'] . base_path() . 'sites' . $files_path[1];
          if (file_exists($dam_file_path)) {
            $file_size = filesize($dam_file_path);
            $file_size_format = $this->formatSizeUnits($file_size);
          }
          else {
            $file_size_format = "File not found.";
          }
        }
        $file_type = $document_node->field_topic->getValue();
        $tid = $file_type[0]['target_id'];
        if (!empty($tid)) {
          $term = Term::load($tid);
          $name = $term->getName();
        }

        $dt = $document_node->title->value;
        $dam_field_file = $document_node->get('field_dam_file')->getValue();
        $document_title = $x . ':-' . $dt . '  <a href="#' . $dam_url . '">Add</a><div>' . $document_title . ' </div></br>';
        $search_results_items[$document_nid] = '<div class="file-details">
	 <div class="file-title-info file-search-results">
	 <div class="binder-title">
		 <div class="download-type"></div>
		 <div class="file-title">' . $document_node->title->value . '</div>
	 </div>
		 <div class="file-info"><span class="file-name">' . $name . '</span><span>' . $file_size_format . '</span></div>
	 </div>';
        if ($file_size_format != "File not found.") {
          $search_results_items[$document_nid] .= '<div class="file-search-results search-results-btn">
		 	 <span class="btn-add btn-add-' . $document_nid . '" data-id = ' . $document_nid . '>ADD</span> 
			 <span class="btn-remove btn-remove-' . $document_nid . '" data-id = ' . $document_nid . '>ADDED</span>
		 </div>';
        }
        $search_results_items[$document_nid] .= '</div>';
        $field_topic = $document_node->get('field_topic')->getValue();
        $field_select_product = $document_node->get('field_select_product')->getValue();
        $select_brand = $document_node->get('select_brand')->getValue();
        $field_part_number = $document_node->get('field_part_number')->getValue();
        $x = $x + 1;
      }

      // $options = ['test3' => 'Test 3', 'test4' => 'Test4'];.
      $options = $search_results_items;
    }
    else {
      // $options = ['test1' => 'Test 1', 'test2' => 'Test2'];.
      $options = $default_search_results_items;
    }

    $form['search_result'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Options'),
      '#options' => $options,
      '#prefix' => '<div id="edit-options">',
      '#suffix' => '</div>',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Review'),
      '#prefix' => '<div class="edit-review">',
      '#suffix' => '</div>',
    ];
    return $form;
  }

  /**
   * Update check function.
   */
  public function updateCheckboxes($form, FormStateInterface $form_state) {

    return $form['search_result'];
  }

  /**
   * This function will create PHP file size in MB, GB, etc.
   */
  public function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
      $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576) {
      $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024) {
      $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1) {
      $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1) {
      $bytes = $bytes . ' byte';
    }
    else {
      $bytes = '0 bytes';
    }

    return $bytes;
  }

  /**
   * This function will give the DAM file Url.
   */
  public function getfileurl($dam_field_file_target_id) {
    $media = Media::load($dam_field_file_target_id);
    if (!empty($media)) {
      $check = $media->get('field_asset')->getValue();
      $media_field_asset = $media->get('field_asset')->getValue();
      $file = File::load($media_field_asset[0]['target_id']);
      $dam_file_uri = $file->getFileUri();
      $dam_url = file_create_url($dam_file_uri);
      return $dam_url;
    }
  }

  /**
   * This function will have all selected docs form the search.
   */
  public function selectedDocuments(array &$form, FormStateInterface $form_state) {
    $search_result = $form_state->getValue('search_result');
    $response = new AjaxResponse();
    $response->addCommand(
          new HtmlCommand(
          '.selected_document_wrapper',
          '<div class="my_top_message_res"> Ajax Responding </div>')
        );
    $selected_docs = [];
    foreach ($search_result as $key => $value) {
      if ($key == $value) {
        // array_push($selected_docs ,$value);.
        $selected_docs[$key] = $value;
      }
    }

    $form['selected_documents']['#options'] = $selected_docs;

    return $form['selected_documents'];
  }

  /**
   * This function will give us model numbers.
   *
   * Based on selected Product Type.
   */
  public function filterModelNumber(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $product_types_array = $form_state->getValue('product_types');
    $products_filter = [];
    foreach ($product_types_array as $key => $value) {
      if ($key == $value) {
        array_push($products_filter, $value);
      }
    }
    $response->addCommand(
      new HtmlCommand(
      '.res',
      '<div class="my_top_message_res"> Ajax Responding </div>')
    );

    $vid = 'language';
    $engish_term_id = '';
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($terms as $term) {
      if ($term->name == 'English') {
        $term->name;
        $engish_term_id = $term->tid;
      }
    }

    $field_select_product_filter = $products_filter;
    $document_query = \Drupal::entityQuery('node');
    // Published or not.
    $document_query->condition('status', 1);
    // Content types.
    $document_query->condition('type', 'document');
    // Only English docs required.
    $document_query->condition('field_language', $engish_term_id);
    if (!empty($field_select_product_filter)) {
      // Filtering product types.
      $document_query->condition('field_select_product', $field_select_product_filter, 'IN');
    }

    $document_query->sort('created', 'DESC');
    $document_nids = $document_query->execute();

    $response = new AjaxResponse();
    $document_title = '';
    $x = 1;

    foreach ($document_nids as $document_nid) {
      $document_node = Node::load($document_nid);
      $field_part_number = $document_node->get('field_part_number')->getValue();
      $field_select_product = $document_node->get('field_select_product')->getValue();
      $product_type_for_this_model = '';
      foreach ($field_select_product as $key => $value) {
        $pid = $field_select_product[$key]['target_id'];
        $product_node = Node::load($pid);
        $product_type_for_this_model = $product_node->title->value . ',' . $product_type_for_this_model;
      }
      foreach ($field_part_number as $k => $val) {
        $mystring = $val['value'];
        $findme   = 'GEA';
        $findme2  = 'gea';
        $pos      = strpos($mystring, $findme);
        $pos2     = strpos($mystring, $findme2);
        if ($pos === FALSE && $pos2 === FALSE) {
          $model_number[$mystring] = $mystring . '(' . $product_type_for_this_model . ')';
        }
      }
    }

    $form['model_number']['#options'] = $model_number;
    return $form['model_number'];
  }

  /**
   * This function will return search results.
   *
   * Based on filter selection.
   */
  public function setMessage(array $form, FormStateInterface $form_state) {
    $document_title = $form_state->getValue('document_title');
    $model_number = $form_state->getValue('model_number');
    $brands = $form_state->getValue('brands');
    $brands_filter = [];
    foreach ($brands as $key => $value) {
      if ($key == $value) {
        array_push($brands_filter, $value);
      }
    }

    $product_types_array = $form_state->getValue('product_types');
    $products_filter = [];
    foreach ($product_types_array as $key => $value) {
      if ($key == $value) {
        array_push($products_filter, $value);
      }
    }

    $file_types = $form_state->getValue('file_types');
    $files_filter = [];
    foreach ($file_types as $key => $value) {
      if ($key == $value) {
        array_push($files_filter, $value);
      }
    }

    $model_number = $form_state->getValue('model_number');
    $document_title = $form_state->getValue('document_title');
    $select_brand_filter = $brands_filter;
    $field_select_product_filter = $products_filter;
    $field_topic_filter = $files_filter;
    $field_part_number_filter = $model_number;

    $vid = 'language';
    $engish_term_id = '';
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($terms as $term) {
      if ($term->name == 'English') {
        $term->name;
        $engish_term_id = $term->tid;
      }
    }

    $document_query = \Drupal::entityQuery('node');
    // Published or not.
    $document_query->condition('status', 1);
    // Content types.
    $document_query->condition('type', 'document');
    // Only English docs required.
    $document_query->condition('field_language', $engish_term_id);

    if (!empty($document_title)) {
      // Content title.
      $document_query->condition('title', $document_title, 'CONTAINS');
    }
    if (!empty($select_brand_filter)) {
      // Filtering brands.
      $document_query->condition('select_brand', $select_brand_filter, 'IN');
    }
    if (!empty($field_select_product_filter)) {
      // Filtering product types.
      $document_query->condition('field_select_product', $field_select_product_filter, 'IN');
    }
    if (!empty($field_topic_filter)) {
      // Filtering file types.
      $document_query->condition('field_topic', $field_topic_filter, 'IN');
    }
    if (!empty($field_part_number_filter)) {
      // Filtering file types.
      $document_query->condition('field_part_number', $field_part_number_filter, 'IN');
    }

    $document_query->sort('created', 'DESC');
    $document_nids = $document_query->execute();

    // $response = new AjaxResponse();
    $document_title = '';
    $x = 1;

    foreach ($document_nids as $document_nid) {
      $document_node = Node::load($document_nid);

      $dt = $document_node->title->value;
      $dam_field_file = $document_node->get('field_dam_file')->getValue();
      $document_title = $x . ':-' . $dt . '  <a href="#' . $dam_url . '">Add</a><div>' . $document_title . ' </div></br>';
      $search_results_items[$document_nid] = $document_node->title->value;
      $field_topic = $document_node->get('field_topic')->getValue();
      $field_select_product = $document_node->get('field_select_product')->getValue();
      $select_brand = $document_node->get('select_brand')->getValue();
      $field_part_number = $document_node->get('field_part_number')->getValue();
      $x = $x + 1;
    }

    $model_number_array = [
      '23976' => 'Masoneilan 74000 Series Case Study (English)',
    ];

    $response = new AjaxResponse();
    $renderer = \Drupal::service('renderer');
    foreach (Element::children($form['search_result']) as $i) {
      unset($form['search_result'][$i]);
    }

    $form['search_result']['#options'] = $search_results_items;
    // Return $form['search_result'];.
    $response->addCommand(new HtmlCommand(
      '#search-result-wrapper',
      $form['search_result']['#options'])
    );
    return $response;
  }

  /**
   * Getter method for Form ID.
   *
   * The form ID is used in implementations of hook_form_alter() to allow other
   * modules to alter the render array built by this form controller.  it must
   * be unique site wide. It normally starts with the providing module's name.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
  public function getFormId() {
    return 'bhge_digital_binder_simple_form';
  }

  /**
   * Implements form validation.
   *
   * The validateForm method is the default method called to validate input on
   * a form.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $search_result = $form_state->getValue('search_result');

    if (count($search_result) > 0) {

      $tempstore = \Drupal::service('user.private_tempstore')->get('bhge_digital_binder');
      $tempstore->set('search_result_data', $search_result);

      $session = \Drupal::request()->getSession();
      $session->set('search_result_data_session', $search_result);
      $search_result_data_session = $session->get('search_result_data_session');
      $tempstore = \Drupal::service('user.private_tempstore')->get('bhge_digital_binder');
      $some_data = $tempstore->get('search_result_data');
    }
  }

  /**
   * Implements a form submit handler.
   *
   * The submitForm method is the default method called for any submit elements.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /*
     * This would normally be replaced by code that actually does something
     * with the title.
     */

    $search_result_data = $form_state->getValue('search_result');
    // die();
    $search_result = [];
    foreach ($search_result_data as $key => $val) {
      if ($key == $val) {
        $search_result[$key] = $val;
        // array_push($search_result,$val);.
      }
    }
    // die();
    $tempstore = \Drupal::service('tempstore.shared')->get('bhge_digital_binder');
    $tempstore->set('search_result_data', $search_result);

    // $tempstore->get('search_result_data');.
    $response = new RedirectResponse('/digital-binder-list');
    $response->send();

  }

}
