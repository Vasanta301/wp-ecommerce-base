<?php
function cubit_is_woocommerce_activated() {
    return class_exists('WooCommerce') ? true : false;
}

if (cubit_is_woocommerce_activated()) {
    /** Add all woo related code here */
    require_once 'woocommerce/setup.php';
    require_once 'woocommerce/enqueue.php';
    require_once 'woocommerce/mini-cart.php';
    require_once 'woocommerce/wish-list.php';
    require_once 'woocommerce/order-notification.php';
    require_once 'woocommerce/tracking-info.php';
    require_once 'woocommerce/utils.php';
}

