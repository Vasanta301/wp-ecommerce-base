<?php

/**
 * Disable REST API in website unless the user is authenticated with an application password
 *
 * @param WP_Error $result The error that would be returned if authentication failed
 * @return WP_Error The error to return if authentication failed
 */
function force_application_password_authentication($result) {
  // If the user is logged in, allow access without requiring application password
  if (is_user_logged_in()) {
    return $result; // Allow access
  }

  // Check for application password
  $username = $_SERVER['PHP_AUTH_USER'] ?? '';
  $password = $_SERVER['PHP_AUTH_PW'] ?? '';

  if (empty($username) || empty($password)) {
    return new WP_Error(
      'rest_authentication_required',
      __('Authentication is required to access the API.'),
      ['status' => 401]
    );
  }

  // If application password is present, allow the request
  return $result;
}
//add_filter('rest_authentication_errors', 'force_application_password_authentication');

// Disable some endpoints for unauthenticated users
add_filter('rest_endpoints', 'disable_default_endpoints');
function disable_default_endpoints($endpoints) {
  $endpoints_to_remove = array(
    '/oembed/1.0',
    '/wp/v2',
    '/wp/v2/media',
    '/wp/v2/types',
    '/wp/v2/statuses',
    '/wp/v2/taxonomies',
    '/wp/v2/tags',
    '/wp/v2/users',
    '/wp/v2/comments',
    '/wp/v2/settings',
    '/wp/v2/themes',
    '/wp/v2/blocks',
    //'/wp/v2/oembed',
    '/wp/v2/posts',
    '/wp/v2/pages',
    '/wp/v2/block-renderer',
    '/wp/v2/search',
    '/wp/v2/categories',
  );

  if (!is_user_logged_in()) {
    foreach ($endpoints_to_remove as $rem_endpoint) {
      // $base_endpoint = "/wp/v2/{$rem_endpoint}";
      foreach ($endpoints as $maybe_endpoint => $object) {
        if (stripos($maybe_endpoint, $rem_endpoint) !== false) {
          unset($endpoints[$maybe_endpoint]);
        }
      }
    }
  }
  return $endpoints;
}

/** 
 * Disable xmlrpc in website
 */
add_filter('xmlrpc_enabled', '__return_false');

/** 
 * Remove <link> that is being shown in head 
 */
remove_action('wp_head', 'rsd_link'); // Remove RSD link
remove_action('wp_head', 'wlwmanifest_link'); // Remove Windows Live Writer manifest link
remove_action('wp_head', 'wp_resource_hints', 99); // Removes XFN profile link
remove_action('wp_head', 'rest_output_link_wp_head'); // Removes Rest Output Link

/**
 * Disable emoji scripts
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * Disable/Hide WordPress / WordPress version giveaway
 */
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');
add_filter('the_generator', function () {
  function remove_x_powered_by_header() {
    header_remove('X-Powered-By');
  }
  add_action('wp', 'remove_x_powered_by_header');
  return;
});

/**
 * Remove additional {v=wpVersion} after js script or css.
 */
function remove_version_from_styles_and_scripts($src) {
  if (strpos($src, 'ver=')) {
    $src = remove_query_arg('ver', $src);
  }
  return $src;
}
add_filter('style_loader_src', 'remove_version_from_styles_and_scripts', 9999);
add_filter('script_loader_src', 'remove_version_from_styles_and_scripts', 9999);

/** 
 * Disable RSS FEED 
 */
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

/**
 * Remove oEmbed discovery links from head
 */
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js'); // Remove oEmbed-specific JavaScript from frontend

/**
 * Remove various options on admin_init
 */
add_action('admin_init', 'remove_or_handle_comments', 100);
function remove_or_handle_comments() {
  // Redirect any user trying to access comments page
  global $pagenow;
  if ($pagenow === 'edit-comments.php') {
    wp_redirect(admin_url());
    exit;
  }
  /**
   * Remove comments metabox from dashboard. Disable support for comments and trackbacks in post types
   */
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
  foreach (get_post_types() as $post_type) {
    if (post_type_supports($post_type, 'comments')) {
      remove_post_type_support($post_type, 'comments');
      remove_post_type_support($post_type, 'trackbacks');
    }
  }
  /**
   * Remove wp_block_support
   */
  remove_submenu_page('themes.php', 'edit.php?post_type=wp_block');
}

/**
 * Close comments on the front and admin
 */
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);
// Remove comments page in menu
add_action('admin_menu', function () {
  remove_menu_page('edit-comments.php');
});
/**
 * Remove Comment Menu from admin bar
 */
add_action('wp_before_admin_bar_render', function () {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('comments');
});

/**
 * Remove comments links from admin bar
 */
add_action('init', function () {
  if (is_admin_bar_showing()) {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
  }
  // remove_post_type_support('page', 'editor');
  // remove_post_type_support('page', 'thumbnail');
});


/**
 * Gutenberg | Block Editor
 * Disable Gutenberg in the whole site
 * -- comment following 4 line of codes if you want guttenburg enabled --
 */
add_filter('use_block_editor_for_post', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');
add_action('wp_enqueue_scripts', 'dequeue_gutenberg_block_scripts', 20);
function dequeue_gutenberg_block_scripts() {
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('global-styles');
  wp_dequeue_style('classic-theme-styles');
}

/**
 * Disable access to ACF for safety reason 
 * Only accessible to Developer User even though user have "administrator" priviledge
 * 
 * -- Comment this function if you want it make it visible to every admin -- 
 */
function hide_acf_for_non_developers() {
  $current_user = wp_get_current_user();
  $developer_usernames = ['cubit', 'Cubit', 'nash', 'Nash', 'admin'];
  if (!in_array($current_user->user_login, $developer_usernames)) {
    remove_menu_page('edit.php?post_type=acf-field-group');
  }
}
add_action('admin_menu', 'hide_acf_for_non_developers', 99);

/**
 * Disable Auto Update for plugins to ensure compatibility.
 * 
 * -- Comment this function if you want to Auto update of plugins -- 
 */
add_filter('auto_update_plugin', '__return_false');

function disable_plugin_update_notifications($value) {
  // List of plugins to disable update notifications for
  $plugins_to_exclude = [
    'advanced-custom-fields-pro/acf.php',
    'acf-extended/acf-extended.php',
    // Add more plugins as needed
  ];

  if (isset($value) && is_object($value) && isset($value->response)) {
    foreach ($plugins_to_exclude as $plugin) {
      if (isset($value->response[$plugin])) {
        unset($value->response[$plugin]);
      }
    }
  }

  return $value;
}
add_filter('site_transient_update_plugins', 'disable_plugin_update_notifications');
