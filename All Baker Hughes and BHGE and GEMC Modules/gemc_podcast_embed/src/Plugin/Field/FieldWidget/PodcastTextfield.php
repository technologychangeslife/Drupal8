<?php

namespace Drupal\gemc_podcast_embed\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * A widget to input podcast URLs.
 *
 * @FieldWidget(
 *   id = "gemc_podcast_embed_textfield",
 *   label = @Translation("Podcast Textfield"),
 *   field_types = {
 *     "gemc_podcast_embed"
 *   },
 * )
 */
class PodcastTextfield extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['value'] = $element + [
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
      '#size' => 60,
      '#maxlength' => $this->getFieldSetting('max_length'),
      '#attributes' => ['class' => ['js-text-full', 'text-full']],
      '#allowed_providers' => $this->getFieldSetting('allowed_providers'),
      '#theme' => 'input__podcast',
    ];
    return $element;
  }

}
