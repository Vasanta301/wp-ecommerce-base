<?php
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
add_action('pre_get_posts', 'mytheme_pre_get_posts');
add_action('wp_footer', 'product_filter_custom_alpine');
add_action('rest_api_init', 'register_filter_api_routes');

/** 
 * Filter by Category and Price
 */
function mytheme_pre_get_posts($query) {
    if (!is_admin() && $query->is_main_query() && is_shop()) {
        // Filter by Category
        if (!empty($_GET['product_cat'])) {
            $query->set('product_cat', sanitize_text_field($_GET['product_cat']));
        }

        // Filter by Price
        if (!empty($_GET['price'])) {
            $price_range = explode('-', sanitize_text_field($_GET['price']));
            if (count($price_range) === 2) {
                $query->set('meta_query', [
                    [
                        'key' => '_price',
                        'value' => $price_range,
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC',
                    ],
                ]);
            }
        }
    }
}

function register_filter_api_routes() {
    register_rest_route('filters/v1', '/filter', [
        'methods' => 'POST', // Change to POST
        'callback' => 'filter_products_callback',
        'permission_callback' => '__return_true', // Allow public access
    ]);
}
function filter_products_callback($request) {
    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];

    $params = $request->get_json_params();

    if (!empty($params['categories'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $params['categories'],
            'operator' => 'IN',
        ];
    }

    if (!empty($params['tags'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => $params['tags'],
            'operator' => 'IN',
        ];
    }

    if (!empty($params['attributes'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'pa_' . $params['attributes'],
            'field' => 'slug',
            'terms' => $params['attributes'],
            'operator' => 'IN',
        ];
    }

    if (!empty($params['price_min']) || !empty($params['price_max'])) {
        $args['meta_query'][] = [
            'key' => '_price',
            'value' => [$params['price_min'] ?: 0, $params['price_max'] ?: PHP_INT_MAX],
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ];
    }

    if (!empty($params['stock_status'])) {
        $args['meta_query'][] = [
            'key' => '_stock_status',
            'value' => $params['stock_status'],
            'compare' => 'IN',
        ];
    }

    $query = new WP_Query($args);
    $products_html = '';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            wc_get_template_part('content', 'product');
            $products_html .= ob_get_clean();
        }
    }

    wp_reset_postdata();

    return [
        'products_html' => $products_html,
    ];
}

function product_filter_custom_alpine() {
    if (is_shop()) {
        ?>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('shopFilters', () => ({
                    filters: {
                        price_min: '',  // Minimum price
                        price_max: '',  // Maximum price
                        stockStatus: 'instock', // Stock status
                        categories: [], // Selected categories
                        tags: [],       // Selected tags
                        attributes: [], // Selected attributes
                    },
                    products: '',
                    loading: false,

                    toggleFilter(type, value) {
                        // Add or remove the value from the respective array
                        if (this.filters[type].includes(value)) {
                            this.filters[type] = this.filters[type].filter(item => item !== value);
                        } else {
                            this.filters[type].push(value);
                        }
                    },

                    filterProducts() {
                        this.loading = true;

                        const payload = {
                            categories: this.filters.categories,
                            tags: this.filters.tags,
                            attributes: this.filters.attributes,
                            price_min: this.filters.price_min,
                            price_max: this.filters.price_max,
                            stockStatus: this.filters.stockStatus,
                        };

                        // Fetch filtered products via custom REST API
                        fetch('/wp-json/filters/v1/filter', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(payload),
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                document.querySelector('.products').innerHTML = data.products_html
                                this.loading = false;
                            })
                            .catch((error) => {
                                this.loading = false;
                            });
                    },
                }));
            });

        </script>
        <?php
    }
}
