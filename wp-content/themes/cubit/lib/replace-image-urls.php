<?php

if (defined('REPLACE_IMAGE_URL')) {
  function get_replace_image_url($url, $attachment_id) {
    $path = get_attached_file($attachment_id);
    if (file_exists($path)) {
      return $url;
    } else {
      return str_replace(get_site_url(), constant('REPLACE_IMAGE_URL'), $url);
    }
  }

  // Needed for media library
  add_filter('wp_get_attachment_url', function ($url, $attachment_id) {
    return get_replace_image_url($url, $attachment_id);
  }, 10, 2);


  // Needed for front-end
  add_filter('wp_get_attachment_image_src', 'replace_image_urls', 10, 2);
  function replace_image_urls($image, $attachment_id) {
    $image[0] = get_replace_image_url($image[0], $attachment_id);
    return $image;
  }

  // Needed for front-end
  add_filter('wp_calculate_image_srcset', 'replace_image_urls_srcset', 10, 5);
  function replace_image_urls_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    $path = get_attached_file($attachment_id);
    if (!file_exists($path)) {
      foreach ($sources as &$source) {
        $source['url'] = str_replace(get_site_url(), constant('REPLACE_IMAGE_URL'), $source['url']);
      }
    }
    return $sources;
  }
}
