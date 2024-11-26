<?php
$GLOBALS['has_padding'] = true;
get_header();

/**
 * If you are using component approach
 * add section via get_page_component_path as included in content.php
 */

include(get_template_directory() . '/content.php');

/**
 * If you are using non component approach
 * add section via get_template_part
 */
//get_template_part('components/generic/page/default-content');

/**
 * If you are not ACF for default page content, use following path
 */
//get_template_part('components/generic/page/acf-content');

get_footer();
