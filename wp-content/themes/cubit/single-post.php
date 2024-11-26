<?php

/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
$GLOBALS['has_padding'] = true;
get_header(); ?>

<?php get_template_part('components/generic/post/content'); ?>

<?php get_footer(); ?>