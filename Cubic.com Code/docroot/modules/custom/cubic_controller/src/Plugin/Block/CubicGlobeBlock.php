<?php

namespace Drupal\cubic_controller\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides a 'CubicGlobeBlock' block.
 *
 * @Block(
 *  id = "cubic_globe_block",
 *  admin_label = @Translation("Cubic Globe Block"),
 * )
 */
class CubicGlobeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = $this->getConfiguration();

    return array(
      'cubic_globe_block' => [
        '#title' => FALSE,
        '#theme' => 'cubic_globe_block',
        '#body' => check_markup($config['cubic_globe_block_body']['value'], $config['cubic_globe_block_body']['format']),
        '#path' => $config['cubic_globe_block_about_path'],
        '#cache' => [
          'max-age' => 86400, // Cache 1 day.
          'tags' => $this->getCacheTags(),
          'contexts' => $this->getCacheContexts(),
        ],
      ],
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();
    $form['cubic_globe_block_body'] = array(
      '#type' => 'text_format',
      '#format' => isset($config['cubic_globe_block_body']['format']) ? $config['cubic_globe_block_body']['format'] : '',
      '#title' => $this->t('Body'),
      '#description' => $this->t('Markup that will be displayed in the body of the block.'),
      '#default_value' => isset($config['cubic_globe_block_body']['value']) ? $config['cubic_globe_block_body']['value'] : '',
    );
    $form['cubic_globe_block_about_path'] = array(
      '#title' => $this->t('About Cubic'),
      '#description' => $this->t('Please supply an absolute path to where the %text link should navigate to. If no path is set, there will be no link rendered.', array('%text' => 'About Cubic')),
      '#type' => 'textfield',
      '#default_value' => '/about',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['cubic_globe_block_body'] = $values['cubic_globe_block_body'];
    $this->configuration['cubic_globe_block_about_path'] = $values['cubic_globe_block_about_path'];

  }

}
