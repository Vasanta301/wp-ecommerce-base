<?php

//TODO: Work on functionality and design as this is just the conceptual on displaying wishlist on product and page to all wishlists from menu.
function wishlist_button_shortcode($atts) {
    // Attributes
    $atts = shortcode_atts(
        array(
            'product_id' => 0,
        ),
        $atts,
        'cubit_wishlist'
    );

    $product_id = intval($atts['product_id']);

    if (!$product_id || !is_user_logged_in()) {
        return ''; // Return nothing if no product ID or user not logged in
    }

    $user_id = get_current_user_id();
    $wishlist = get_user_meta($user_id, 'wishlist', true);
    if (!$wishlist) {
        $wishlist = array();
    }

    $is_in_wishlist = in_array($product_id, $wishlist);

    ob_start(); // Start output buffering
    ?>
    <button class="toggle-wishlist" data-product-id="<?php echo $product_id; ?>">
        <?php if ($is_in_wishlist): ?>
            <div class="w-8 h-8">
                <?= get_icon('heart', 'solid'); ?>
            </div>
        <?php else: ?>
            <div class="w-8 h-8">
                <?= get_icon('heart', ); ?>
            </div>
        <?php endif; ?>
    </button>
    <?php
    return ob_get_clean();
}
add_shortcode('cubit_wishlist', 'wishlist_button_shortcode');

add_action('woocommerce_single_product_summary', 'add_custom_text_after_product_title', 5);

function add_custom_text_after_product_title() {
    global $product;
    echo do_shortcode('[cubit_wishlist product_id="' . $product->get_id() . '"]');
}
function register_wishlist_api_routes() {
    register_rest_route('wishlist/v1', '/toggle', array(
        'methods' => 'POST',
        'callback' => 'toggle_wishlist_item',
    ));
}
add_action('rest_api_init', 'register_wishlist_api_routes');

function toggle_wishlist_item(WP_REST_Request $request) {
    $product_id = $request->get_param('product_id');
    $user_id = get_current_user_id();

    if (!$user_id) {
        return new WP_REST_Response('Unauthorized', 401);
    }

    $wishlist = get_user_meta($user_id, 'wishlist', true);
    if (!$wishlist) {
        $wishlist = array();
    }

    if (in_array($product_id, $wishlist)) {
        $wishlist = array_diff($wishlist, array($product_id));
        $status = 'removed';
    } else {
        $wishlist[] = $product_id;
        $status = 'added';
    }

    update_user_meta($user_id, 'wishlist', $wishlist);

    return new WP_REST_Response(array('status' => $status), 200);
}

function display_wishlists() {
    if (!is_user_logged_in()) {
        return '';
    }
    $user_id = get_current_user_id();
    $wishlist = get_user_meta($user_id, 'wishlist', true);
    $wishlist_count = is_array($wishlist) ? count($wishlist) : 0;
    ?>
    <div x-data="{ wishlistOpen: false }">
        <div class="flex flex-row px-4 pt-2 truncate rounded cursor-pointer">
            <div class="flex flex-row-reverse w-full ml-2" @click="wishlistOpen = !wishlistOpen;">
                <div class="relative">
                    <span class="sr-only"><?= __('Open Wishlist', 'translation-ready'); ?></span>
                    <div
                        class="absolute top-0 right-0 px-1 -mt-1 -mr-2 text-xs font-bold text-white bg-red-700 rounded-full wishlist-count">
                        <?= $wishlist_count; ?>
                    </div>
                </div>
                <span class="flex w-8 h-8"> <?= get_icon('heart'); ?></span>
            </div>
        </div>
        <div class="fixed inset-y-0 right-0 z-50 w-full h-full max-w-full pt-8 overflow-y-auto bg-white shadow-lg md:max-w-sm "
            x-show="wishlistOpen" x-cloak x-transition:enter="transform transition ease-out duration-500"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transform transition ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
            @click.away="wishlistOpen = false">
            <div class="p-4 mini-cart-content">
                <?php
                if ($wishlist && is_array($wishlist) && count($wishlist) > 0): ?>
                    <ul class="flex flex-col gap-4 wishlist-items">
                        <?php
                        foreach ($wishlist as $product_id):
                            $product = wc_get_product($product_id);
                            $is_in_wishlist = in_array($product_id, $wishlist);
                            $product_permalink = $product->get_permalink();
                            $thumbnail = $product->get_image();
                            $product_name = $product->get_name();
                            ?>
                            <?php if ($product): ?>
                                <li
                                    class="flex flex-col items-center py-4 space-x-4 md:flex-row woocommerce-mini-cart-item mini_cart_item">
                                    <button class="toggle-wishlist" data-product-id="<?php echo $product_id; ?>">
                                        <?php if ($is_in_wishlist): ?>
                                            <div class="w-8 h-8">
                                                <?= get_icon('heart', 'solid'); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="w-8 h-8">
                                                <?= get_icon('heart', ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </button>

                                    <div class="flex-shrink-0">
                                        <?php if (empty($product_permalink)): ?>
                                            <?php echo $thumbnail; ?>
                                        <?php else: ?>
                                            <a class="block w-20 h-20" href="<?php echo esc_url($product_permalink); ?>">
                                                <?php echo $thumbnail; ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex-1">
                                        <a href="<?php echo esc_url($product_permalink); ?>" class="text-blue-500 hover:text-blue-700">
                                            <?php echo $product_name; ?>
                                        </a>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Wishlist is empty.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
add_shortcode('cubit_wishlists', 'display_wishlists');

add_action('wp_footer', 'wishlist_custom_css');

function wishlist_custom_css() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wishlistCountElement = document.querySelector('.wishlist-count'); // Add this element to display the count
            const wishlistUpdatedEvent = new Event('wishlistUpdated'); // Custom event

            document.querySelectorAll('.toggle-wishlist').forEach(function (button) {
                button.addEventListener('click', function () {
                    const productId = button.getAttribute('data-product-id');

                    fetch('/wp-json/wishlist/v1/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>',
                        },
                        body: JSON.stringify({
                            product_id: productId
                        }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'added') {
                                button.innerHTML = 'Added';
                            } else {
                                button.innerHTML = 'Removed';
                            }

                            // Update wishlist count
                            updateWishlistCount();

                            // Dispatch custom event
                            document.dispatchEvent(wishlistUpdatedEvent);
                        });
                });
            });

            // Function to update wishlist count
            function updateWishlistCount() {
                fetch('/wp-json/wishlist/v1/count', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (wishlistCountElement) {
                            wishlistCountElement.textContent = data.count || 0;
                        }
                    });
            }

            // Listen for the custom wishlistUpdated event
            document.addEventListener('wishlistUpdated', function () {
                console.log('Wishlist was updated!');
            });
        });
    </script>
    <?php
}

function get_wishlist_count(WP_REST_Request $request) {
    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_REST_Response('Unauthorized', 401);
    }

    $wishlist = get_user_meta($user_id, 'wishlist', true);
    $count = is_array($wishlist) ? count($wishlist) : 0;

    return new WP_REST_Response(array('count' => $count), 200);
}

add_action('rest_api_init', function () {
    register_rest_route('wishlist/v1', '/count', array(
        'methods' => 'GET',
        'callback' => 'get_wishlist_count',
    ));
});

/***
 * For Product : [cubit_wishlist product_id="123"] //Id to be replaced as required
 * For Menu : [cubit_wishlists] 
 **/