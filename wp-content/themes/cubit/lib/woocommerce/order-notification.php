<?php

//TODO: Work on functionality and design as this is just the conceptual on displaying order notification.

// Create Notification Post Type
function create_notification_post_type()
{
    register_post_type('notification', array(
        'labels'      => array(
            'name'          => __('Notifications'),
            'singular_name' => __('Notification'),
        ),
        'public'      => true,
        'has_archive' => true,
        'supports'    => array('title', 'editor', 'custom-fields'),
    ));
}
add_action('init', 'create_notification_post_type');

function add_notification($title, $message)
{
    $notification_id = wp_insert_post(array(
        'post_title'    => $title,
        'post_content'  => $message,
        'post_status'   => 'publish',
        'post_type'     => 'notification',
    ));

    if ($notification_id) {
        update_post_meta($notification_id, 'read_status', 'unread');
    }
}

function mark_notification_as_read($notification_id)
{
    update_post_meta($notification_id, 'read_status', 'read');
}

function get_latest_notifications($limit = 5)
{
    $args = array(
        'post_type'      => 'notification',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'read_status',
                'value'   => 'unread',
                'compare' => '='
            )
        )
    );
    $query = new WP_Query($args);
    return $query->posts;
}

function reset_notifications()
{
    $args = array(
        'post_type'      => 'notification',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    );
    $query = new WP_Query($args);
    foreach ($query->posts as $notification) {
        update_post_meta($notification->ID, 'read_status', 'unread');
    }
}

function get_unread_notification_count()
{
    $args = array(
        'post_type'      => 'notification',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'read_status',
                'value'   => 'unread',
                'compare' => '='
            )
        )
    );
    $query = new WP_Query($args);
    return $query->found_posts;
}

add_action('woocommerce_thankyou', 'trigger_new_order_notification', 10, 1);
function trigger_new_order_notification($order_id)
{
    $title = 'New Order Placed';
    $message = 'A new order has been placed. Order ID: ' . $order_id;
    add_notification($title, $message);
}

add_action('woocommerce_order_status_changed', 'trigger_order_status_change_notification', 10, 3);
function trigger_order_status_change_notification($order_id, $old_status, $new_status)
{
    $title = 'Order Status Changed';
    $message = 'Order ID: ' . $order_id . ' has changed from ' . $old_status . ' to ' . $new_status;
    add_notification($title, $message);
}

add_action('woocommerce_low_stock', 'trigger_low_stock_notification');
function trigger_low_stock_notification($product)
{
    $title = 'Low Stock Alert';
    $message = 'Product ' . $product->get_name() . ' is low in stock.';
    add_notification($title, $message);
}

add_action('wp_ajax_mark_notification_as_read', 'mark_notification_as_read_ajax');
function mark_notification_as_read_ajax()
{
    if (isset($_POST['notification_id'])) {
        $notification_id = intval($_POST['notification_id']);
        mark_notification_as_read($notification_id);
    }
    wp_die(); // Required to terminate immediately and return a proper response
}

add_action('wp_ajax_get_unread_notification_count', 'get_unread_notification_count_ajax');
function get_unread_notification_count_ajax()
{
    echo get_unread_notification_count(); // Output the count of unread notifications
    wp_die(); // Required to terminate immediately and return a proper response
}

function cubit_order_notification_callback()
{
    ob_start();
    $notifications = get_latest_notifications();
?>
    <div class="relative flex-1 menu-item notifications group" x-data="{ notifOpen: false }">
        <div class="flex justify-center">
            <div class="relative ">
                <div class="flex flex-row p-2 px-4 truncate rounded cursor-pointer">
                    <div class="flex justify-center w-full ml-2" @click="notifOpen = !notifOpen; if (!notifOpen) $dispatch('close-notif-dropdown')">
                        <span class="sr-only"><?= __('Open Notification', 'translation-ready'); ?></span>
                        <div class="absolute top-0 right-0 px-1 -mt-1 -mr-2 text-xs font-bold text-white bg-red-700 rounded-full"><?php echo $notifications ? count($notifications) : '' ?></div>
                        <span class="block h-7 w-7"><?= get_svg('notification'); ?></span>
                    </div>
                    <div class="absolute z-10 w-full border-t-0 rounded-b" <?= translate_transition_dropdown_menu() ?> x-show="notifOpen" x-cloak @click.away="notifOpen = false">
                        <div class="w-full lg:max-w-md">
                            <div class="bg-white border-2 shadow-xl w-max">
                                <?php

                                if ($notifications) : ?>
                                    <ul class="space-y-2">
                                        <?php foreach ($notifications as $notification) :
                                            $content = $notification->post_content;
                                            preg_match('/Order ID:\s*(\d+)/', $content, $matches);
                                            $order_id = isset($matches[1]) ? $matches[1] : '';

                                            $order_url = esc_url(wc_get_endpoint_url('view-order', $order_id, wc_get_page_permalink('myaccount')));
                                        ?>
                                            <li class="p-2 border-b border-gray-200" id="notification-<?php echo esc_attr($notification->ID); ?>">
                                                <a href="<?php echo $order_url; ?>" class="text-black">
                                                    <?php echo wp_kses_post($notification->post_title); ?>
                                                </a>
                                                <p class="text-sm text-gray-600"><?php echo wp_kses_post($content); ?></p>
                                                <a href="#" class="text-black" onclick="markAsRead(<?php echo esc_js($notification->ID); ?>, this.parentElement); return false;">Mark as read</a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else : ?>
                                    <ul class="space-y-2">
                                        <li class="p-2 border-b border-gray-200">
                                            <p class="text-gray-600">No new notifications</p>
                                        </li>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('cubit_order_notification', 'cubit_order_notification_callback');
