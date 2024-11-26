<?php
add_action('wp_enqueue_scripts', 'enqueque_scripts');

function enqueque_scripts()
{
    wp_dequeue_script('wc-cart-fragments');
    wp_enqueue_script('wc-cart-fragments');
}
