<?php

namespace Drupal\gemc_components\Plugin\ThemeFieldProcessor;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Url;
use Drupal\handlebars_theme_handler\Plugin\ThemeFieldProcessor\FieldLink;
use Drupal\link\LinkItemInterface;

/**
 * Returns the (structured) data of a field.
 *
 * @ThemeFieldProcessor(
 *   id = "gemc_field_link",
 *   label = @Translation("Link"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class GemcFieldLink extends FieldLink {

  /**
   * {@inheritdoc}
   */
  protected function getItemData(FieldItemInterface $field, $options = []) {
    $url_obj = $this->buildUrl($field);

    $url = $url_obj->toString();
    $options = $url_obj->getOptions();

    $data = [
      'text' => $field->title,
      'url' => $url,
      'target' => '_self'
    ];

    if (isset($options['attributes']) && isset($options['attributes']['target']) && (strlen($options['attributes']['target']) > 0)) {
      $data['target'] = $options['attributes']['target'];
    }
    return $data;
  }

  /**
   * Builds the \Drupal\Core\Url object for a link field item.
   *
   * @param \Drupal\link\LinkItemInterface $item
   *   The link field item being rendered.
   *
   * @return \Drupal\Core\Url
   *   A Url object.
   */
  protected function buildUrl(LinkItemInterface $item) {
    /* get field name */
    $name = $item->getFieldDefinition()->getName();

    $url = $item->getUrl() ?: Url::fromRoute('<none>');

    $options = $item->options;
    $options += $url->getOptions();
    /* if link for download content type then alter link to asset url */
    if (!empty($name) && $name == 'field_cta_link') {
      $string_url = $url->toString();
      $file_url = gemc_core_get_download_cta_asset_url($string_url);

      if (strlen($file_url) > 0) {
        $url = Url::fromUri($file_url);
        $options['attributes']['target'] = '_blank';
      }
    }

    $url->setOptions($options);
    return $url;
  }

}
