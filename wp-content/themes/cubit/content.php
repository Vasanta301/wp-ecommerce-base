<?php
if (is_home()) {
  $blog_page_id = get_option('page_for_posts');
  $page_components = get_field('page_components', $blog_page_id);
} elseif (is_front_page()) {
  $front_page_id = get_option('page_on_front');
  $page_components = get_field('page_components', $front_page_id);
} else {
  $page_components = get_field('page_components');
}

if (is_string($page_components)) {
  $page_components = json_decode($page_components, true);
}

if (!is_array($page_components)) {
  $page_components = array();
}

if (!empty($page_components)) {
  $layouts = array_map(function ($page_component) {
    return is_array($page_component) ? ($page_component['acf_fc_layout'] ?? '') : '';
  }, $page_components);

  /**
   * Enqueue swiper only if component named "Reviews" is added.
   * Please change it as you require and uncomment following code to active code
   */

  // if (count(array_intersect(['reviews'], $layouts))) {
  //   add_action('wp_enqueue_scripts', 'enqueue_swiper');
  // }
}
if (isset($page_components) && is_array($page_components) && count($page_components) > 0) {
  foreach ($page_components as $page_component) {
    $component_name = str_replace('_', '-', $page_component['acf_fc_layout'] ?? 'unknown');
    $fields = isset($page_component[$page_component['acf_fc_layout']]) ? $page_component[$page_component['acf_fc_layout']] : [];
    $path = get_page_component_path($component_name);
    if (file_exists($path)) {
      include $path;
    } else {
      echo "<section id='$component_name'>
      <div class='px-4 py-20 my-10 bg-gray-200 '>
        <div class='container text-2xl text-red-500'>
          <h2>Component not found: $component_name</h2>
        </div>
      </div>
    </section>";
    }
  }
} else {
  get_template_part('components/generic/page/default-content');
}