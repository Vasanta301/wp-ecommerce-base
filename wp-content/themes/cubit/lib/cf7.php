<?php
// Stop form elements being wrapped in paragraph tags
add_filter('wpcf7_autop_or_not', '__return_false');

// Add ability to put autocomplete attribute on CF7 forms
add_filter('wpcf7_form_tag', function ($tag) {
  $data = [];
  $allowed_attributes = ['autocomplete', 'aria-'];
  foreach ((array) $tag['options'] as $option) {
    foreach ($allowed_attributes as $allowed_attribute) {
      if (strpos($option, $allowed_attribute) === 0) {
        $att = explode(':', $option, 2);
        $data[$att[0]] = apply_filters('wpcf7_option_value', $att[1], $att[0]);
      }
    }
  }
  if (!empty($data)) {
    add_filter('wpcf7_form_elements', function ($content) use ($tag, $data) {
      $data_attrs = wpcf7_format_atts($data);
      $name = $tag['name'];
      $content_plus_data_attrs = str_replace("name=\"$name\"", "name=\"$name\" " . $data_attrs, $content);

      return $content_plus_data_attrs;
    });
  }
  return $tag;
});


function index_array($string, $array) {
  $cursor = $array;
  foreach (explode(".", $string) as $key) {
    $cursor = $cursor[$key];
  }
  return $cursor;
}

// Replace all contact forms with a template file
add_action('wpcf7_contact_form', 'replace_cf7_template');
function replace_cf7_template($form) {
  $components_with_forms = [
    // [
    //   'global_field_name' => 'footer',
    //   'index' => 'mailing_list.form_shortcode',
    //   'path' => 'components/global/email-subscription-form/email-subscription-form',
    // ],
  ];
  $shortcodes = array_merge(...array_map(function ($component) {
    if (!isset($component['index'])) {
      $component['index'] = 'form_shortcode';
    }
    $shortcode = index_array($component['index'], get_field($component['global_field_name'], 'option'));
    return [
      $shortcode => $component['path'],
    ];
  }, $components_with_forms));

  $shortcode = $form->shortcode();
  if (isset($shortcodes[$shortcode])) {
    ob_start();
    get_template_part($shortcodes[$shortcode]);
    $the_form = ob_get_clean();
    $form->set_properties(['form' => $the_form]);
  }
}

remove_action('wpcf7_init', 'wpcf7_add_form_tag_submit');
add_action('wpcf7_init', 'my_wpcf7_add_form_tag_submit', 10, 0);
function my_wpcf7_add_form_tag_submit() {
  wpcf7_add_form_tag('submit', 'my_wpcf7_submit_form_tag_handler');
}

function my_wpcf7_submit_form_tag_handler($tag) {
  $class = wpcf7_form_controls_class($tag->type, 'has-spinner');

  $atts = array();

  $atts['class'] = $tag->get_class_option($class);
  $atts['id'] = $tag->get_id_option();
  $atts['tabindex'] = $tag->get_option('tabindex', 'signed_int', true);

  $value = isset($tag->values[0]) ? $tag->values[0] : '';

  if (empty($value)) {
    $value = __('Send', 'contact-form-7');
  }

  $atts['type'] = 'submit';

  $atts = wpcf7_format_atts($atts);

  $html = sprintf('<button %1$s><span class="text">' . $value . '</span><div class="loading">' . get_svg('loading') . '</div></button>', $atts);

  return $html;
}


add_filter('dnd_cf7_data_options', 'replace_upload_file_message');
function replace_upload_file_message($vars) {
  $contact_us_page_components = get_field('page_components', 636);
  if (is_array($contact_us_page_components)) {
    $layouts = array_map(function ($page_component) {
      return $page_component['acf_fc_layout'];
    }, $contact_us_page_components);
    $index = array_search('contact_us', $layouts);
    if ($index !== false) {
      $contact_us_fields = $contact_us_page_components[$index];
      $message = $contact_us_fields['upload_file_message'];
      $vars['or_separator'] = $message;
    }
  }
  return $vars;
}
