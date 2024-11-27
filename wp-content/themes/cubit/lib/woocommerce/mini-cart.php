<?php
add_shortcode('cubit_mini_cart', 'mini_cart_shortcode_callback');
add_action('wp_enqueue_scripts', 'enqueue_woocommerce_cart_fragments');
add_filter('woocommerce_add_to_cart_fragments', 'custom_add_to_cart_fragment');

//TODO: Work on functionality and design as this is just the conceptual on displaying mini-cart on hover from menu.
function mini_cart_shortcode_callback() {
    ob_start();
    ?>
    <div class="menu-item mini-cart group">
        <div x-data="{ cartOpen: false }">
            <div class="flex flex-row p-2 px-4 truncate rounded cursor-pointer">
                <div class="flex flex-row-reverse w-full ml-2">
                    <div class="relative" @click="cartOpen = !cartOpen;">
                        <span class="sr-only"><?= __('Open Cart', 'translation-ready'); ?></span>
                        <div
                            class="absolute top-0 right-0 px-1 -mt-1 -mr-2 text-xs font-bold text-white bg-red-700 rounded-full">
                            <?php echo WC()->cart->get_cart_contents_count(); ?>
                        </div>
                        <?= get_svg('cart'); ?>
                    </div>
                </div>
            </div>
            <div class="fixed inset-y-0 right-0 z-50 w-full h-full max-w-lg overflow-y-auto bg-white shadow-lg"
                x-effect="document.body.classList.toggle('modal-open', cartOpen)" x-show="cartOpen" x-cloak
                x-transition:enter="transform transition ease-out duration-500"
                x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transform transition ease-in duration-300"
                x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
                @click.away="cartOpen = false">
                <div class="!px-2 !pt-12 mini-cart-content">
                    <?php
                    woocommerce_mini_cart();
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}


function translate_transition_dropdown_menu() {
    return 'x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"';
}

function enqueue_woocommerce_cart_fragments() {
    if (function_exists('is_woocommerce') && is_woocommerce()) {
        wp_enqueue_script('wc-cart-fragments');
    }
}

function custom_add_to_cart_fragment($fragments) {
    ob_start();
    echo do_shortcode('[cubit_mini_cart]');
    $fragments['div.mini-cart'] = ob_get_clean();
    return $fragments;
}