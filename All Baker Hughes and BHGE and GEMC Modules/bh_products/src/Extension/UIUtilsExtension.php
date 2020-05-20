<?php

namespace Drupal\bh_products\Extension;

use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\Core\Database\Database;
use Drupal\taxonomy\Entity\Term;

/**
 * Create custom Twig UI extentions.
 */
class UIUtilsExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'ui_products_util_extension';
  }

  /**
   * In this function we can declare the extension function.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('getmediaFileFieldValues', [
        $this,
        'getmediaFileFieldValues',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getPathAliasParentCategoriesAttachedwithProduct', [
        $this,
        'getPathAliasParentCategoriesAttachedwithProduct',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getAllPathAliasofProduct', [
        $this,
        'getALLPathAliasofProduct',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getparentTaxonomyTerm', [
        $this,
        'getparentTaxonomyTerm',
      ], ['is_safe' => ['html']]),
    ];
  }

  /**
   * Process file field.
   */
  public function getmediaFileFieldValues($mid) {
    $media = Media::load($mid);
    if (!empty($media)) {
      $fid = $media->field_media_file->target_id;
      $file = File::load($fid);
      if (!empty($file) && $file instanceof File) {
        $filesize = format_size($file->filesize->value);
        $file_path_info = pathinfo($file->filename->value);
        $url = $file->hasField('uri') ? file_create_url($file->get('uri')->value) : '';
        return [
          'name' => !empty($file_path_info) ? $file_path_info['filename'] : '',
          'size' => $filesize,
          'type' => !empty($file_path_info) ? $file_path_info['extension'] : '',
          'url' => $url,
        ];
      }

    }
    return NULL;
  }

  /**
   * Get Url Alias of the parent category associated with the Product.
   */
  public function getPathAliasParentCategoriesAttachedwithProduct($productid) {
    $product_cat = [];
    $product_node = Node::load($productid);
    foreach ($product_node->field_parent_categories as $item) {
      $alias = '';
      if ($item->entity) {
        $names[$item->entity->id()]['title'] = $item->entity->label();
        $alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $item->entity->id());
        $product_cat[$item->entity->id()]['alias'] = $alias;
      }
    }
    return $product_cat;
  }

  /**
   * Get All the Url Alias of the product.
   */
  public function getAllPathAliasofProduct($productid) {
    $product_node_aliases = [];
    $dbConn = Database::getConnection();
    $path_alias_product = $dbConn->select('path_alias')
      ->fields('path_alias')
      ->condition('path', '/node/' . $productid, '=')
      ->execute()
      ->fetchAll();
    if (!empty($path_alias_product)) {
      foreach ($path_alias_product as $val) {
        $product_node_aliases[] = $val->alias;
      }
    }
    return $product_node_aliases;
  }

  /**
   * Get the parent taxonomy term of a child term.
   */
  public function getparentTaxonomyTerm($termId) {
    $arr_parent = [];
    if (!empty($termId)) {
      $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($termId);
      if (!empty($term->parent)) {
        $parent_term_id = $term->parent->target_id;
        if (!empty($parent_term_id)) {
          $parent = Term::load($parent_term_id);
          if (!empty($parent)) {
            $name = $parent->getName();
            $arr_parent['tid'] = $parent_term_id;
            $arr_parent['name'] = $name;

          }
        }
      }
    }
    return $arr_parent;
  }

}
