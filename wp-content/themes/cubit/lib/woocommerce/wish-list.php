<?php
add_action('rest_api_init', 'register_wishlist_api_routes');
add_shortcode('cubit_wishlist', 'display_wishlist_button_shortcode_callback');
add_shortcode('cubit_wishlists', 'display_wishlists_popup_shortcode_callback');
add_action('wp_footer', 'wishlist_custom_alpine');
add_action('woocommerce_single_product_summary', 'show_wishlist_icon_after_product_title', 5);

/**
 * Registers the REST API routes for the wishlist feature.
 *
 * This function registers the following routes:
 * - POST /wishlist/v1/toggle: Toggles a product in the user's wishlist.
 * - GET /wishlist/v1/content: Retrieves the content of the user's wishlist.
 * - GET /wishlist/v1/count: Retrieves the count of items in the user's wishlist.
 * - GET /wishlist/v1/status/{product_id}: Retrieves the wishlist status for a specific product.
 */
function register_wishlist_api_routes() {
    register_rest_route('wishlist/v1', '/toggle', array(
        'methods' => 'POST',
        'callback' => 'toggle_wishlist_item',
    ));
    register_rest_route('wishlist/v1', '/content', array(
        'methods' => 'GET',
        'callback' => 'get_wishlist_content',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('wishlist/v1', '/count', array(
        'methods' => 'GET',
        'callback' => 'get_wishlist_count',
    ));
    register_rest_route('wishlist/v1', '/status/(?P<product_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_wishlist_status',
    ));
}

/***
 * Toggles a product in the user's wishlist.
 *
 * @param WP_REST_Request $request Request to toggle a product in the user's wishlist.
 *
 * @return WP_REST_Response Response containing the status of the request.
 * The status can be either 'added' or 'removed'.
 */
function toggle_wishlist_item(WP_REST_Request $request) {
    $product_id = $request->get_param('product_id');
    $user_id = get_current_user_id();

    if (!$user_id) {
        return new WP_REST_Response('Unauthorized', 401);
    }

    // Ensure we're dealing with the product or variation ID correctly
    $product = wc_get_product($product_id);
    if (!$product) {
        return new WP_REST_Response('Invalid product', 400);
    }

    // If it's a variation, use its parent product ID for consistency
    if ($product->is_type('variation')) {
        $product_id = $product->get_parent_id();
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

/**
 * Retrieves the current user's wishlist content.
 *
 * Fetches the user's wishlist and returns HTML for displaying products 
 * with a toggle button for adding/removing items. Returns a 401 response 
 * if the user is not authenticated.
 *
 * @param WP_REST_Request $request The REST API request object.
 * 
 * @return WP_REST_Response The wishlist HTML or an empty message, or a 401 response if unauthorized.
 */
function get_wishlist_content(WP_REST_Request $request) {
    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_REST_Response('Unauthorized', 401);
    }

    $wishlist = get_user_meta($user_id, 'wishlist', true);
    ob_start(); ?>

    <?php if ($wishlist && is_array($wishlist) && count($wishlist) > 0): ?>
        <div class="relative flex flex-col gap-4">
            <?php foreach ($wishlist as $product_id): ?>
                <?php $product = wc_get_product($product_id); ?>
                <?php if ($product):
                    // If it's a variation, get the parent product
                    if ($product->is_type('variation')) {
                        $product_id = $product->get_parent_id();
                        $product = wc_get_product($product_id);
                    }
                    $is_in_wishlist = in_array($product_id, $wishlist);
                    $product_permalink = $product->get_permalink();
                    $thumbnail = $product->get_image();
                    $product_name = $product->get_description();
                    $product_attributes = '';
                    if ($product->is_type('variation')) {
                        $variation = wc_get_product($product_id);
                        $attributes = $variation->get_attributes();
                        foreach ($attributes as $attribute_name => $attribute_value) {
                            $product_attributes .= ucfirst(str_replace('pa_', '', $attribute_name)) . ': ' . $attribute_value . '<br>';
                        }
                    }
                    ?>
                    <div class="flex flex-col items-center py-4 space-x-4 md:flex-row">
                        <div class="flex-shrink-0">
                            <?php if (empty($product_permalink)): ?>
                                <?php echo $thumbnail; ?>
                            <?php else: ?>
                                <a class="block w-20 h-20" href="<?php echo esc_url($product_permalink); ?>">
                                    <?php echo $thumbnail; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col w-full">
                            <a href="<?php echo esc_url($product_permalink); ?>" class="text-blue-500 hover:text-blue-800">
                                <div><?= $product->get_name(); ?></div>
                            </a>
                            <div class="text-gray-800 line-clamp-2"><?= $product->get_description(); ?></div>
                            <?php if ($product->is_type('variation')): ?>
                                <div class="text-xs text-center text-gray-500">
                                    <?= $product_attributes; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button @click="$dispatch('toggle-wishlist', { productId: <?php echo esc_js($product_id); ?> })">
                            <div
                                class="w-8 h-8 wishlist-trigger-icon wishlist-trigger-icon-<?= $product_id; ?> <?= $is_in_wishlist ? 'text-red-600' : 'text-gray-600'; ?>">
                                <?= get_icon('heart', 'solid'); ?>
                            </div>
                        </button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>There are no wishlist right now. Try adding new products.</p>
    <?php endif; ?>

    <?php $html = ob_get_clean();

    return new WP_REST_Response(array('html' => $html), 200);
}

/**
 * Retrieves the current user's wishlist item count.
 *
 * Returns the number of items in the user's wishlist. If the user is not 
 * authenticated, a 401 response is returned.
 *
 * @param WP_REST_Request $request The REST API request object.
 * 
 * @return WP_REST_Response The wishlist item count or a 401 response if unauthorized.
 */
function get_wishlist_count(WP_REST_Request $request) {
    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_REST_Response('Unauthorized', 401);
    }

    $wishlist = get_user_meta($user_id, 'wishlist', true);
    $count = is_array($wishlist) ? count($wishlist) : 0;

    return new WP_REST_Response(array('count' => $count), 200);
}

/**
 * Checks if a product is in the user's wishlist and returns the corresponding status.
 *
 * Retrieves the product ID from the request and checks if the product is in 
 * the current user's wishlist. Returns the heart icon with the appropriate color 
 * based on whether the product is in the wishlist or not.
 * If the user is not authenticated, a 401 response is returned.
 *
 * @param WP_REST_Request $request The REST API request object containing the product ID.
 * 
 * @return WP_REST_Response The heart icon with the status or a 401 response if unauthorized.
 */
function get_wishlist_status(WP_REST_Request $request) {
    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_REST_Response('Unauthorized', 401);
    }

    $product_id = $request->get_param('product_id');
    $wishlist = get_user_meta($user_id, 'wishlist', true);
    $product = wc_get_product($product_id);

    if (!$product) {
        return new WP_REST_Response('Invalid product', 400);
    }

    // If it's a variation, use the parent product ID
    if ($product->is_type('variation')) {
        $product_id = $product->get_parent_id();
    }

    $is_in_wishlist = is_array($wishlist) && in_array($product_id, $wishlist);
    ob_start(); ?>
    <div class="w-8 h-8 <?= $is_in_wishlist ? 'text-red-600' : 'text-gray-600'; ?>">
        <?= get_icon('heart', 'solid'); ?>
    </div>
    <?php $html = ob_get_clean();

    return new WP_REST_Response(array('html' => $html), 200);
}

/**
 * Renders a wishlist button shortcode.
 *
 * Outputs a button that allows logged-in users to toggle a product's presence 
 * in their wishlist. The button displays a heart icon that is either red (if 
 * the product is in the wishlist) or gray (if not). If no product ID is 
 * provided or the user is not logged in, the shortcode returns an empty string.
 *
 * @param array $atts Shortcode attributes containing the product ID.
 * 
 * @return string The HTML for the wishlist button or an empty string if the user is not logged in or no product ID is provided.
 */
function display_wishlist_button_shortcode_callback($atts) {
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

    $product = wc_get_product($product_id);
    if ($product && $product->is_type('variation')) {
        $product_id = $product->get_parent_id();
    }

    $is_in_wishlist = in_array($product_id, $wishlist);

    ob_start(); // Start output buffering
    ?>
    <div x-init>
        <button @click="$dispatch('toggle-wishlist', { productId: <?php echo esc_js($product_id); ?> })"
            class="wishlist-button">
            <div
                class="w-8 h-8 wishlist-trigger-icon wishlist-trigger-icon-<?= $product_id; ?> <?= $is_in_wishlist ? 'text-red-600' : 'text-gray-600'; ?>">
                <?= get_icon('heart', 'solid'); ?>
            </div>
        </button>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Displays the wishlist button and popup for logged-in users.
 *
 * This function renders a clickable wishlist icon that shows the count of items 
 * in the user's wishlist. When clicked, it toggles the visibility of a popup 
 * displaying the wishlist items. The function uses Alpine.js for state management 
 * and transitions. If the user is not logged in, it returns an empty string.
 *
 * @return string HTML markup for the wishlist button and popup, or an empty string if the user is not logged in.
 */
function display_wishlists_popup_shortcode_callback() {
    if (!is_user_logged_in()) {
        return '';
    }
    $user_id = get_current_user_id();
    $wishlist = get_user_meta($user_id, 'wishlist', true);
    ?>
    <div x-data="wishlistManager({wishlist:<?php echo json_encode_alpine($wishlist); ?>})"
        x-init="$nextTick(() => window.wishlistManagerInstance = $data)" @wishlistUpdated.window="refreshWishlistFragments">
        <div class="flex flex-row-reverse p-2 px-4 truncate rounded cursor-pointer"
            @click="wishlistOpen = !wishlistOpen; document.dispatchEvent(new Event('wishlistUpdated'));">
            <div class="relative">
                <span class="sr-only"><?= __('Open Wishlist', 'translation-ready'); ?></span>
                <div class="absolute top-0 right-0 px-1 -mt-1 -mr-2 text-xs font-bold text-white bg-red-700 rounded-full wishlist-count"
                    x-text="wishlistCount">
                </div>
            </div>
            <div class="w-8 h-8">
                <?= get_icon('heart'); ?>
            </div>
        </div>
        <!-- Wishlist Popup -->
        <div class="fixed inset-y-0 right-0 z-50 w-full h-full max-w-full pt-8 overflow-x-hidden overflow-y-auto bg-white shadow-lg md:max-w-sm"
            x-cloak x-effect="document.body.classList.toggle('modal-open', wishlistOpen)" x-show="wishlistOpen" x-cloak
            x-transition:enter="transform transition ease-out duration-500"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transform transition ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
            @click.away="wishlistOpen = false">
            <h3 class="pt-8 text-xl font-semibold text-center">Wishlists</h3>
            <div class="p-4 wishlist-content wishlist-items">
            </div>
        </div>
    </div>
    <?php
}


function wishlist_custom_alpine() {
    ?>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('wishlistManager', (config) => ({
                wishlists: config.wishlist || [],
                wishlistOpen: false,
                wishlistCount: 0,
                productId: null,
                isProductInWishlist: false,
                init() {
                    this.updateWishlistCount();
                    document.addEventListener('toggle-wishlist', (event) => {
                        const productId = event.detail?.productId;
                        if (productId) {
                            this.toggleWishlist(productId);
                        }
                    });
                },
                toggleWishlist(productId) {
                    fetch('/wp-json/wishlist/v1/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>',
                        },
                        body: JSON.stringify({ product_id: productId }),
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.status) {
                                this.updateWishlistCount();
                                this.refreshWishlistTriggerIcon(productId);
                            }
                        });
                },
                updateWishlistCount() {
                    fetch('/wp-json/wishlist/v1/count', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>',
                        },
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            this.wishlistCount = data.count || 0;
                            const wishlistCountElement = document.querySelector('.wishlist-count');
                            if (wishlistCountElement) {
                                wishlistCountElement.textContent = data.count || 0;
                            }
                            document.dispatchEvent(new Event('wishlistUpdated'));
                        });
                },
                refreshWishlistTriggerIcon(productId) {
                    //TODO: Improvement : better solution than this
                    const icons = document.querySelectorAll(`.wishlist-trigger-icon-${productId}`);
                    icons.forEach(icon => {
                        if (icon.classList.contains('text-red-600')) {
                            icon.classList.remove('text-red-600');
                            icon.classList.add('text-gray-600');
                        } else {
                            icon.classList.remove('text-gray-600');
                            icon.classList.add('text-red-600');
                        }
                    });
                }
            }));
        });

        document.addEventListener('wishlistUpdated', function () {
            refreshWishlistFragments()
        });

        function refreshWishlistFragments() {
            const wishlistPopupContainer = document.querySelector('.wishlist-items');
            if (wishlistPopupContainer) {
                fetch('/wp-json/wishlist/v1/content', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        wishlistPopupContainer.innerHTML = data.html;
                    })
            }
        }
    </script>
    <?php
}





function show_wishlist_icon_after_product_title() {
    global $product;
    echo do_shortcode('[cubit_wishlist product_id="' . $product->get_id() . '"]');
}
/***
 * For Product : [cubit_wishlist product_id="123"] //Id to be replaced as required
 * For Menu : [cubit_wishlists] 
 **/