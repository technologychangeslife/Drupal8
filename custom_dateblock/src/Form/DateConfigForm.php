<?php

namespace Drupal\custom_dateblock\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;


/**
 * Our config form.
 */
class DateConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "custom_timezone_confighero";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_timezone.settings');

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default Title'),
      '#default_value' => $config->get('title'),
    ];
    
    $form['desc'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default Desc'),
      '#default_value' => $config->get('desc'),
    ];
    
   $form['expiration'] = array(
    '#type' => 'date',
    '#title' => $this
    ->t('Content expiration'),
    '#default_value' => $config->get('expiration'),
   );
    //$timestamp = $request_time = \Drupal::time()->getCurrentTime();
    $new_timezone = 'America/Chicago';
    
    $date = new DrupalDateTime();
    $date->setTimezone(new \DateTimeZone($new_timezone));
    print 'America/Chicago'.$date->format('m/d/Y g:i a');
    print '<br>';
    $date2 = new DrupalDateTime();
    $date2->setTimezone(new \DateTimeZone('America/New_York'));
    print 'America/New_York'.$date2->format('m/d/Y g:i a');
    print '<br>';
    $date2 = new DrupalDateTime();
    $date2->setTimezone(new \DateTimeZone('Asia/Tokyo'));
    print 'Asia/Tokyo'.$date2->format('m/d/Y g:i a');
    print '<br>';
    $date2 = new DrupalDateTime();
    $date2->setTimezone(new \DateTimeZone('Asia/Kolkata'));
    print 'Asia/Kolkata'.$date2->format('m/d/Y g:i a');
    print '<br>';
    $date2 = new DrupalDateTime();
    $date2->setTimezone(new \DateTimeZone('Europe/Amsterdam'));
    print 'Europe/Amsterdam'.$date2->format('m/d/Y g:i a');
    
    
    $get_timezone = \Drupal::service('custom_timezone.get_timezone');
    print $get_timezone->getCurrentTime($new_timezone);
    
    $options = array(
    'America/Chicago' => 'America/Chicago',
    'America/New_York' => 'America/New_York',
    'Asia/Tokyo' => 'Asia/Tokyo',
    'Asia/Dubai' => 'Asia/Dubai',
    'Asia/Kolkata' => 'Asia/Kolkata',
    'Europe/Amsterdam' => 'Europe/Amsterdam',
    'Europe/Oslo' => 'Europe/Oslo',
    'Europe/London' => 'Europe/London');
    
    $form['timezone'] = [
      '#type' => 'select',
      '#options' => $options,
      '#title' => $this->t('Timezone'),
      '#default_value' => $config->get('timezone'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom_timezone.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('custom_timezone.settings');

    $config
      ->set('title', $form_state->getValue('title'))
      ->save();
      
    $config
      ->set('desc', $form_state->getValue('desc'))
      ->save();
      
    $config
      ->set('expiration', $form_state->getValue('expiration'))
      ->save();
      
    $config
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
