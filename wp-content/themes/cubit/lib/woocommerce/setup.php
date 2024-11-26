<?php

/**
 * This ensure support of Woocommerce in website 
 * @return void
 */
function add_woocommerce_support_current_theme()
{
    add_theme_support('woocommerce');
    wp_enqueue_script( 'wc-cart-fragments' );
}
add_action('after_setup_theme', 'add_woocommerce_support_current_theme');

function cubit_is_product_archive() {
	if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
		return true;
	} else {
		return false;
	}
}