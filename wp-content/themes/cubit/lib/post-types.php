<?php

// Makes urls with custom post type pagination, e.g. /resources/page/2
// query a page and not the post type (e.g. resource)
add_action('init', function () {
  add_rewrite_rule(
    '(.?.+?)/page/?([0-9]{1,})/?$',
    'index.php?pagename=$matches[1]&paged=$matches[2]',
    'top'
  );
});

add_action('init', 'register_custom_post_types');
function register_custom_post_types() {
}