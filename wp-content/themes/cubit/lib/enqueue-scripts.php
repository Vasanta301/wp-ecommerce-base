<?php
// Enqueue js and css files from manifest produced by webpack
add_action('wp_enqueue_scripts', 'enqueue_from_manifest');
function enqueue_from_manifest() {
  wp_enqueue_script('jquery');
  $path = get_stylesheet_directory() . '/dist/manifest.json';
  if (file_exists($path)) {
    $str = file_get_contents($path);
    $json = json_decode($str, true);
    wp_enqueue_style('main', get_template_directory_uri() . '/dist/' . $json['main.css'], [], time() . '-1.0.0', 'all');
    wp_enqueue_script('main', get_template_directory_uri() . '/dist/' . $json['main.js'], ['gsap', 'gsap-scrolltrigger'], time() . '-1.0.0', false);
  }
  wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.0/gsap.min.js', [], false, true);
  wp_enqueue_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.0/ScrollTrigger.min.js', [], false, true);
  wp_enqueue_script('gsap-scrollsmoother', get_stylesheet_directory_uri() . '/src/js/components/ScrollSmoother.min.js', [], false, true);
  wp_enqueue_script('alpine-collapse', 'https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js', ['main']);
  wp_enqueue_script('alpine', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', ['main']);
}

// Increase width of sidebar from 160px -> 180px
add_action('admin_enqueue_scripts', 'admin_style');
function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri() . '/admin.css');
}

// Use the following commented snippet on pages requiring swiper:
// add_action('wp_enqueue_scripts', 'enqueue_swiper');
function enqueue_swiper() {
  wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css');
  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js');
  global $wp_scripts;

  $script_main = $wp_scripts->query('main', 'registered');
  if ($script_main) {
    $script_main->deps[] = 'swiper';
  }

  $script_alpine = $wp_scripts->query('alpine', 'registered');
  if ($script_alpine) {
    $script_alpine->deps[] = 'swiper';
  }
}

/** 
 * Function : Defer Mode for scripts 
 * The specified scripts will load in deferred mode, improving 
 * page load performance by allowing HTML parsing to 
 * continue while the scripts are loaded in the background. 
 * Scripts will execute after the page has been fully parsed.
 */
add_filter('script_loader_tag', 'defer_custom_scripts', 10, 2);
function defer_custom_scripts($tag, $handle) {
  if (in_array($handle, ['main', 'alpine', 'alpine-collapse', 'swiper', 'gsap', 'gsap-scrolltrigger'])) {
    return str_replace(' src', ' defer src', $tag);
  } else {
    return $tag;
  }
}
