<?php

/**
 * Repeats the elements of an array a specified number of times.
 *
 * Takes an array and an integer as arguments. 
 * Returns a new array where the elements of the input array are repeated `n` times.
 *
 * @param array $array The array of elements to be repeated.
 * @param int $n The number of times to repeat the array elements.
 * @return array The resulting array with repeated elements.
 */
function repeatArray($array, $n)
{
  $result = [];
  $eCount = count($array);
  for ($j = 0; $j < $n; $j++) {
    for ($i = 0; $i < $eCount; $i++) {
      $result[] = $array[$i];
    }
  }
  return $result;
};

/**
 * Logs a variable to the error log.
 *
 * This function checks if the `elog` function already exists. If not, it defines the `elog` function
 * which logs the provided variable to the error log using `error_log` and `print_r`.
 *
 * @param mixed $var The variable to be logged. Can be of any type.
 * @return void
 */
if (!function_exists("elog")) {
  function elog($var)
  {
    error_log(print_r($var, true));
  }
}

/**
 * Dumps a variable in a human-readable format.
 *
 * This function takes a variable and outputs its contents using `var_dump` wrapped in `<pre>` tags
 * to format the output for better readability in HTML.
 *
 * @param mixed $var The variable to be dumped. Can be of any type.
 * @return void
 */
function vdump($var)
{
  echo '<pre>'; // Formats the output for readability
  var_dump($var);
  echo '</pre>';
}

/**
 * Retrieves the contents of an SVG file.
 *
 * This function constructs the file path of an SVG based on the given name,
 * checks if the file exists, and returns its contents. The SVG files are
 * expected to be located in the 'src/img/svgs/' directory of the current theme.
 *
 * @param string $svg_name The name of the SVG file (without the .svg extension).
 * @return string|false The contents of the SVG file, or false if the file does not exist.
 */
function get_svg($svg_name)
{
  $file = get_stylesheet_directory() . '/src/img/svgs/' . $svg_name . '.svg';
  if (file_exists($file)) {
    return file_get_contents($file);
  }
}

/**
 * Retrieves the contents of an SVG icon.
 *
 * @param string $icon_name The name of the icon file (without the .svg extension).
 * @param string $type The type of the icon (default is 'outline').
 * @return string|false The contents of the SVG file, or false if the file does not exist.
 */
function get_icon($icon_name, $type = 'outline')
{
  return get_svg("heroicons/$type/$icon_name");
}

/**
 * Retrieves the contents of a social icon SVG file.
 *
 * @param string $icon_name The name of the social icon file (without the .svg extension).
 * @return string|false The contents of the SVG file, or false if the file does not exist.
 */
function get_social_icon($icon_name)
{
  return get_svg('socials/' . $icon_name);
}
/**
 * Encodes data to a JSON string with single quotes instead of double quotes.
 *
 * @param mixed $data The data to be encoded.
 * @return string The JSON-encoded string with single quotes.
 */
function json_encode_alpine($data)
{
  return str_replace('"', "'", json_encode($data));
}

/**
 * Retrieves an HTML image element for a given attachment ID with specified attributes.
 *
 * @param int $image_id The attachment ID of the image.
 * @param string $sizes The sizes attribute value for the image.
 * @param array $atts Additional attributes for the image element.
 * @return string The HTML image element.
 */
function get_img($image_id, $sizes, $atts = [])
{
  if (!isset($atts['sizes'])) {
    $atts['sizes'] = $sizes;
  }
  if (!isset($atts['class'])) {
    $atts['class'] = 'h-full w-full object-cover';
  }
  return wp_get_attachment_image($image_id, 'full', false, $atts);
}

/**
 * Retrieves the URL of an image file in the theme's src/img directory.
 *
 * @param string $img_name The name of the image file.
 * @return string The URL of the image file.
 */
function get_img_url($img_name)
{
  return get_template_directory_uri() . '/src/img/' . $img_name;
}

/**
 * Retrieves the path to a component file in the theme's components directory.
 *
 * @param string $component_path The path to the component file (without .php extension).
 * @return string The full path to the component file.
 */
function get_component_path($component_path)
{
  return get_template_directory() . "/components/$component_path.php";
}

/**
 * Retrieves the file path to a component in the theme's components directory.
 *
 * @param string $component_path The path to the component.
 * @return string The full file path to the component.
 */
function get_component_file_path($component_path)
{
  return get_template_directory() . "/components/$component_path";
}

/**
 * Retrieves the path to a page component file in the theme's components/page directory.
 *
 * @param string $component_name The name of the page component.
 * @return string The full path to the page component file.
 */
function get_page_component_path($component_name)
{
  return get_component_path("page/$component_name/$component_name");
}

/**
 * Customizes pagination links with additional arguments.
 *
 * @param string $url_append String to append to the pagination URL.
 * @param int $end_size How many numbers on either the start and the end list edges.
 * @param int $mid_size How many numbers to either side of the current pages.
 * @param bool $prev_next Whether to include the previous and next links.
 * @return array|null Array of pagination links or null if only one page.
 */
function paginate_links_custom($url_append = '', $end_size = 1, $mid_size = 2, $prev_next = true)
{
  $page_var = is_front_page() ? 'page' : 'paged';
  $big = 9999999;
  $args = array(
    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big)) . $url_append),
    'format' => '?paged=%#%',
    'total' => get_query_var('max_num_pages') ?? 1,
    'current' => max(1, get_query_var($page_var)),
    'prev_next' => $prev_next,
    'end_size' => $end_size,
    'mid_size' => $mid_size,
  );

  $total = (int) $args['total'];
  if ($total < 2) {
    return null;
  }
  $current = $args['current'];

  $page_links = array();
  $page_links['prev'] = false;
  $page_links['next'] = false;
  $page_links['numbers'] = array();
  $dots = false;

  if ($args['prev_next'] && $current && $current > 1) {
    $link = str_replace('%_%', 2 == $current ? '' : $args['format'], $args['base']);
    $link = str_replace('%#%', $current - 1, $link);
    $link = esc_url(apply_filters('paginate_links', $link));
    $page_links['prev'] = $link;
  }

  for ($n = 1; $n <= $total; $n++) {
    if ($n == $current) {
      $page_links['numbers'][] = array(
        'type' => 'num',
        'current' => true,
        'num' => number_format_i18n($n),
      );
      $dots = true;
    } else {
      if (($n <= $end_size || ($current && $n >= $current - $mid_size && $n <= $current + $mid_size) || $n > $total - $end_size)) {
        $link = str_replace('%_%', 1 == $n ? '' : $args['format'], $args['base']);
        $link = str_replace('%#%', $n, $link);
        $link = esc_url(apply_filters('paginate_links', $link));
        $page_links['numbers'][] = array(
          'type' => 'num',
          'current' => false,
          'num' => number_format_i18n($n),
          'link' => $link,
        );

        $dots = true;
      } elseif ($dots) {
        $page_links['numbers'][] = array(
          'type' => 'dots',
        );
        $dots = false;
      }
    }
  }

  if ($args['prev_next'] && $current && $current < $total) {
    $link = str_replace('%_%', $args['format'], $args['base']);
    $link = str_replace('%#%', $current + 1, $link);
    $link = esc_url(apply_filters('paginate_links', $link));
    $page_links['next'] = $link;
  }

  return $page_links;
}

/**
 * Checks if the current post is a landing page.
 *
 * @return bool True if the current post is a landing page, false otherwise.
 */
function is_landing_page(): bool
{
  return get_post_type() == 'landing-page' || isset($_GET['landing_page']);
}

/**
 * Generates a custom excerpt from content with a specified length.
 *
 * @param string $content The content to generate an excerpt from.
 * @param int $length The length of the excerpt (default is 100 characters).
 * @return string The generated excerpt.
 */
function get_custom_excerpt($content, $length = 100)
{
  // Remove all HTML tags
  $text_content = preg_replace('/<[^>]*>/', '', $content);
  // Replace multiple spaces with single space
  $text_content = preg_replace('/\s+/', ' ', $text_content);
  // Trim leading and trailing whitespace
  $excerpt = trim($text_content);

  // Check if $length is not provided or empty
  if ($length === '' || !is_numeric($length)) {
    return $text_content; // Return full content if $length is not valid
  }

  // If $length is provided and valid, truncate the excerpt if necessary
  if (strlen($excerpt) > $length) {
    $excerpt = substr($excerpt, 0, $length) . '...';
  }

  return $excerpt;
}

/**
 * Returns Alpine.js transition attributes for an element with absolute positioning.
 *
 * @return string The Alpine.js transition attributes.
 */
function absolute_transition()
{
  return 'x-transition:enter="delay-300 ease-in-out duration-300"
  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
  x-transition:leave="ease-in-out duration-300 order-first" x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"';
}
/**
 * Get Global Component Button
 * @param mixed $button
 * @param mixed $button_styles
 * @return void
 */
function get_global_component_button($button = [], $button_styles = []) {
  extract($button);
  extract($button_styles);
  include get_template_directory() . "/components/global/button/button.php";
}
