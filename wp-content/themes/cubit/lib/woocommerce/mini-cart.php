<?php
add_shortcode('cubit_mini_cart', 'mini_cart_shortcode_callback');
add_action('wp_enqueue_scripts', 'enqueue_woocommerce_cart_fragments');
add_filter('woocommerce_add_to_cart_fragments', 'custom_add_to_cart_fragment');
add_action('wp_footer', 'add_popup_bottom_right_after_cart_updated');


/**
 * Shortcode : Mini Cart Shortcode Callback
 *
 * @return void
 */
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
            <!-- Overlay -->
            <div x-show="cartOpen" x-cloak @click="cartOpen = false"
                class="fixed inset-0 z-40 transition-opacity duration-300 bg-black bg-opacity-50"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
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

/**
 * Summary of translate_transition_dropdown_menu
 * @return string
 */
function translate_transition_dropdown_menu() {
    return 'x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"';
}

/**
 * Summary of enqueue_woocommerce_cart_fragments
 * @return void
 */
function enqueue_woocommerce_cart_fragments() {
    if (function_exists('is_woocommerce') && is_woocommerce()) {
        wp_enqueue_script('wc-cart-fragments');
    }
}

/**
 * Summary of custom_add_to_cart_fragment
 * @param mixed $fragments
 * @return mixed
 */
function custom_add_to_cart_fragment($fragments) {
    ob_start();
    echo do_shortcode('[cubit_mini_cart]');
    $fragments['div.mini-cart'] = ob_get_clean();
    return $fragments;
}

/**
 * Summary of add_popup_bottom_right_after_cart_updated
 * @return void
 */
function add_popup_bottom_right_after_cart_updated() {
    ?>
    <div x-data="CartAlertManager({ cartAlertOpen: false, cartAlertMessage: '' })" x-cloak>
        <!-- Notification Popup -->
        <div x-show="cartAlertOpen" x-transition
            class="fixed content-center justify-center hidden max-w-sm px-4 py-3 bg-green-400 rounded shadow-lg w-60 md:flex bottom-5 right-5 gap-x-2">
            <div class="self-center block w-16 h-auto">
                <img src="<?= get_template_directory_uri() . '/src/img/yay.gif'; ?>" />
            </div>
            <div class="self-center text-sm text-white" x-text="cartAlertMessage">
            </div>
        </div>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('CartAlertManager', () => ({
                    cartAlertOpen: false,
                    cartAlertMessage: '',
                    init() {
                        // Listen for the WooCommerce "added_to_cart" event
                        jQuery(document.body).on('added_to_cart', (event, fragments, cart_hash, $button) => {
                            const productId = $button.data('product_id'); // Get product ID from button
                            console.log(`Product ID: ${productId}`); // Debugging log
                            this.showAlert(productId); // Call showAlert with productId
                        });
                    },
                    showAlert(productId) {
                        // Fetch product details from REST API
                        fetch(`/wp-json/wc/v3/products/${productId}`, {
                            method: 'GET', // Use GET for fetching data
                            headers: {
                                'Content-Type': 'application/json',
                                'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>', // Ensure nonce is valid
                            },
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                if (data && data.name) {
                                    console.log(data); // Debugging log
                                    this.cartAlertMessage = `${data.name} has been added to your cart!`;
                                    this.cartAlertOpen = true;

                                    // Automatically close the alert after 3 seconds
                                    setTimeout(() => {
                                        this.cartAlertOpen = false;
                                    }, 3000);
                                }
                            })
                            .catch((error) => console.error('Error fetching product details:', error)); // Log errors
                    },
                }));
            });
        </script>
        <?php
}