<?php

/**
 * @file
 * Drupal site-specific configuration file.
 */

$databases = array();
$config_directories = array();
# $settings['install_profile'] = '';
$settings['hash_salt'] = 'A3sORTBZA6IpOnuLAxb5LhT8j0EmObBSzsqJylFLk4UtIAobLibOvdz16DA2keu4OGLMhW2BVg';
# $settings['deployment_identifier'] = \Drupal::VERSION;
$settings['update_free_access'] = FALSE;
# $settings['http_client_config']['proxy']['http'] = 'http://proxy_user:proxy_pass@example.com:8080';
# $settings['http_client_config']['proxy']['https'] = 'http://proxy_user:proxy_pass@example.com:8080';
# $settings['http_client_config']['proxy']['no'] = ['127.0.0.1', 'localhost'];
# $settings['reverse_proxy'] = TRUE;
# $settings['reverse_proxy_addresses'] = array('a.b.c.d', ...);
# $settings['reverse_proxy_header'] = 'X_CLUSTER_CLIENT_IP';
# $settings['reverse_proxy_proto_header'] = 'X_FORWARDED_PROTO';
# $settings['reverse_proxy_host_header'] = 'X_FORWARDED_HOST';
# $settings['reverse_proxy_port_header'] = 'X_FORWARDED_PORT';
# $settings['reverse_proxy_forwarded_header'] = 'FORWARDED';
# $settings['omit_vary_cookie'] = TRUE;
# $settings['cache_ttl_4xx'] = 3600;
# $settings['form_cache_expiration'] = 21600;
# $settings['class_loader_auto_detect'] = FALSE;

/*
if ($settings['hash_salt']) {
  $prefix = 'drupal.' . hash('sha256', 'drupal.' . $settings['hash_salt']);
  $apc_loader = new \Symfony\Component\ClassLoader\ApcClassLoader($prefix, $class_loader);
  unset($prefix);
  $class_loader->unregister();
  $apc_loader->register();
  $class_loader = $apc_loader;
}
*/
$settings['allow_authorize_operations'] = FALSE;
# $settings['file_chmod_directory'] = 0775;
# $settings['file_chmod_file'] = 0664;
# $settings['file_public_base_url'] = 'http://downloads.example.com/files';
# $settings['file_public_path'] = 'sites/default/files';
# $settings['file_private_path'] = '';
# $settings['session_write_interval'] = 180;
# $settings['locale_custom_strings_en'][''] = array(
#   'forum'      => 'Discussion board',
#   '@count min' => '@count minutes',
# );
# $settings['maintenance_theme'] = 'bartik';
# ini_set('pcre.backtrack_limit', 200000);
# ini_set('pcre.recursion_limit', 200000);
# $settings['bootstrap_config_storage'] = array('Drupal\Core\Config\BootstrapConfigStorageFactory', 'getFileStorage');
# $config['system.file']['path']['temporary'] = '/tmp';
# $config['system.site']['name'] = 'My Drupal site';
# $config['system.theme']['default'] = 'stark';
# $config['user.settings']['anonymous'] = 'Visitor';
# $config['system.performance']['fast_404']['exclude_paths'] = '/\/(?:styles)|(?:system\/files)\//';
# $config['system.performance']['fast_404']['paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
# $config['system.performance']['fast_404']['html'] = '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
# $settings['container_base_class'] = '\Drupal\Core\DependencyInjection\Container';
# $settings['yaml_parser_class'] = NULL;
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];
$settings['entity_update_batch_size'] = 50;

// On Acquia Cloud, this include file configures Drupal to use the correct
// database in each site environment (Dev, Stage, or Prod). To use this
// settings.php for development on your local workstation, set $db_url
// (Drupal 5 or 6) or $databases (Drupal 7 or 8) as described in comments above.
if (file_exists('/var/www/site-php')) {
  require('/var/www/site-php/cubic/cubic-settings.inc');

  if (isset($settings['memcache']['servers'])) {
    // Memcache settings.
    $settings['cache']['default'] = 'cache.backend.memcache';
  }
}

// Turn off email for dev/test.
if (isset($_ENV['AH_NON_PRODUCTION']) && $_ENV['AH_NON_PRODUCTION']) {
  $config['system.mail']['interface']['default'] = 'devel_mail_log';
  $config['mailsystem.settings']['defaults']['formatter'] = 'devel_mail_log';
  $config['mailsystem.settings']['defaults']['sender'] = 'devel_mail_log';
  $config['mailsystem.settings']['modules'] = '';
}

if (isset($_ENV['AH_PRODUCTION']) && $_ENV['AH_PRODUCTION'] == 1) {
  /* @see cubic_workbench_moderation_notify_content_administrators() Expecting machine name of Drupal role. */
  $settings['cubic_workbench_moderation.distribution_group'] = 'administrator';
}

// settings for NYSE Stock ticker. Currently same across all environemts
$settings['cubic_nyse_ticker.symbol'] = 'CUB';
$settings['cubic_nyse_ticker.apiKey'] = '9f_2_rYjR13uTBnX_kKaQ7JUnCkHl4I9pyZG_j';

// simplesaml settings
$settings['simplesamlphp_dir'] = '../simplesamlphp';

// Private files.
if (isset($_ENV['AH_SITE_ENVIRONMENT'])) {
  $settings['file_private_path'] = '/mnt/files/' . $_ENV['AH_SITE_GROUP'] . '.' . $_ENV['AH_SITE_ENVIRONMENT'] . '/' . $site_path . '/files-private';
}

/**
 * Load local development override configuration, if available.
 *
 * Use settings.local.php to override variables on secondary (staging,
 * development, etc) installations of this site. Typically used to disable
 * caching, JavaScript/CSS compression, re-routing of outgoing emails, and
 * other things that should not happen on development and testing sites.
 *
 * Keep this code block at the end of this file to take full effect.
 */
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}

/**
 * Password protecting nonproduction environments
 */

// Make sure Drush keeps working.
// Modified from function drush_verify_cli()
$cli = (php_sapi_name() == 'cli');
// PASSWORD-PROTECT ACQUIA SITES (i.e. prod/staging/dev)
if (!$cli && (isset($_ENV['AH_NON_PRODUCTION']) && $_ENV['AH_NON_PRODUCTION'])) {
 $username = 'cubic-dev';
 $password = 'E#SXrZ$kuZXH';
 if (!(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_USER']==$username && $_SERVER['PHP_AUTH_PW']==$password))) {
   header('WWW-Authenticate: Basic realm="This site is protected"');
   header('HTTP/1.0 401 Unauthorized');
   // Fallback message when the user presses cancel / escape
   echo 'Access denied';
   exit;
 }
}


$settings['cache']['bins']['render'] = 'cache.backend.database';
//$settings['config_sync_directory'] = 'sites/default/files/config';


  $databases['default']['default'] = [
    'database' => 'cubicstg',
    'username' => 'root',
    'password' => '',
    'host' => 'localhost',
    'port' => '3306',
    'driver' => 'mysql',
    'prefix' => '',
    'collation' => 'utf8mb4_general_ci',
  ];