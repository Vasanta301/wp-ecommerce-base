<?php
add_action('wp_enqueue_scripts', 'enqueque_scripts');

function enqueque_scripts() {
    wp_dequeue_script('wc-cart-fragments');
    wp_enqueue_script('wc-cart-fragments');
    wp_localize_script('alpine', 'wc_cart_alert', [
        'nonce' => wp_create_nonce('wp_rest'),
    ]);
}
