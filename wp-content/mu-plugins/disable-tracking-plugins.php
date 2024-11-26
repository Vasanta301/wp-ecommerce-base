<?php

/*
Plugin Name: Disable Tracking Plugins for Landing Pages
Description: E.g. Google analytics, tiktok analytics, etc
Author: Mude
Version: 1.0
Author URI: https://www.mude.com.au
*/

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$is_landing_page = strpos($request_uri, '/landing-page/') !== false || isset($_GET['landing_page']);

add_filter('option_active_plugins', function ($plugins) use ($is_landing_page) {
  if ($is_landing_page) {
    $tracking_plugins_to_disable = [
      'google-site-kit/google-site-kit.php',
      'hotjar/hotjar.php',
      'official-facebook-pixel/facebook-for-wordpress.php',
      'insert-script-in-headers-and-footers/insert-script-in-headers-and-footers.php'
    ];
    $plugins = array_diff($plugins,  $tracking_plugins_to_disable);
  }
  return $plugins;
});
