<?php

namespace Drupal\custom_dateblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_dateblock\GetTimezoneService;
use Drupal\Core\Datetime\DrupalDateTime;


/**
 * Provides a block called "Custom TimeZone Block".
 *
 * @Block(
 *  id = "custom_date_block",
 *  admin_label = @Translation("Custom Date Block")
 * )
 */
class DateBlock extends BlockBase implements ContainerFactoryPluginInterface {
 
  protected $timeZoneService;

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('custom_dateblock.get_timezone')
    );
    
    
  }

  public function __construct(array $configuration, $plugin_id, $plugin_definition, GetTimezoneService $timeZoneService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->timeZoneService = $timeZoneService;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
   
    $config = \Drupal::config('custom_dateblock.settings');
    // Will print 'Hello'.
    $config->get('title');
    // Will print 'en'.
    $config->get('desc');
    
    $config->get('timezone');
    
    $curent_time = $this->timeZoneService->getCurrentTime($config->get('timezone'));
    
    $compare_date = $config->get('expiration');
    
    $date = new DrupalDateTime();
    print $date->format('d/m/Y');
    
    if ($compare_date > $date) {
     $title = "This title will appear for dates lesser than ".$compare_date;
     $desc = "This desc will appear for dates lesser than ".$compare_date;
    }
    else {
     $title = $config->get('title'); 
     $desc = $config->get('desc');
    }
    
    $heroes = [
      ['Title' => $title, 'Desc' => $desc, 'timezone' => $curent_time ]
    ];

    $table = [
      '#type' => 'table',
      '#header' => [
        $this->t('Title'),
        $this->t('Desc'),
        $this->t('Current Time'),
      ]
    ];

    foreach($heroes as $hero) {
      $table[] = [
        'Title' => [
          '#type' => 'markup',
          '#markup' => $hero['Title'],
        ],
        'Desc' => [
          '#type' => 'markup',
          '#markup' => $hero['Desc'],
          '#prefix' => '<div id="form-wrapper">',
          '#suffix' => '</div>',
        ],
        'timezone' => [
          '#type' => 'markup',
          '#markup' => $hero['timezone'],
          '#cache' => [
           'tags' => ['config:custom_dateblock.settings'],
          ]
        ],
      ];
    }

    return $table;
  }
  
   public static function getTime() {
    return "Ajax...";
  }
}
