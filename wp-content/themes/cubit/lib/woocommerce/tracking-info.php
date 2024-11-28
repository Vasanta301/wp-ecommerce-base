<?php

add_action('woocommerce_admin_order_data_after_shipping_address', function ($order) {
    $tracking_number = get_post_meta($order->get_id(), '_tracking_number', true);
    $tracking_status = get_post_meta($order->get_id(), '_tracking_status', true);
    wp_nonce_field('save_tracking_info', 'tracking_info_nonce');
    ?>
    <h3>Shipment Tracking</h3>
    <div class="form-field form-field-wide">
        <label for="tracking_number"><?php _e('Tracking Number', 'your-textdomain'); ?></label>
        <input type="text" id="tracking_number" name="tracking_number" value="<?php echo esc_attr($tracking_number); ?>"
            style="width:100%;" />
    </div>
    <div class="form-field form-field-wide">
        <label for="tracking_status"><?php _e('Tracking Status', 'your-textdomain'); ?></label>
        <select id="tracking_status" name="tracking_status" style="width:100%;">
            <option value=""><?php _e('-- Select Status --', 'your-textdomain'); ?></option>
            <option value="shipped" <?php selected($tracking_status, 'shipped'); ?>>
                <?php _e('Shipped', 'your-textdomain'); ?>
            </option>
            <option value="in_transit" <?php selected($tracking_status, 'in_transit'); ?>>
                <?php _e('In Transit', 'your-textdomain'); ?>
            </option>
            <option value="delivered" <?php selected($tracking_status, 'delivered'); ?>>
                <?php _e('Delivered', 'your-textdomain'); ?>
            </option>
        </select>
    </div>
    <?php
});

add_action('woocommerce_process_shop_order_meta', function ($post_id) {
    // Verify nonce
    if (!isset($_POST['tracking_info_nonce']) || !wp_verify_nonce($_POST['tracking_info_nonce'], 'save_tracking_info')) {
        return;
    }

    // Save tracking number
    if (isset($_POST['tracking_number'])) {
        update_post_meta($post_id, '_tracking_number', sanitize_text_field($_POST['tracking_number']));
    }

    // Save tracking status
    if (isset($_POST['tracking_status'])) {
        update_post_meta($post_id, '_tracking_status', sanitize_text_field($_POST['tracking_status']));
    }

    // Add an admin note for tracking updates
    $order = wc_get_order($post_id);
    if ($order) {
        $message = __('Tracking information updated:', 'your-textdomain') . "\n";
        $message .= __('Tracking Number:', 'your-textdomain') . ' ' . sanitize_text_field($_POST['tracking_number']) . "\n";
        $message .= __('Tracking Status:', 'your-textdomain') . ' ' . ucwords(str_replace('_', ' ', sanitize_text_field($_POST['tracking_status'])));
        $order->add_order_note($message);

        if (function_exists('save_user_notification')) {
            $order = wc_get_order($post_id);
            $user_id = $order->get_user_id();
            save_user_notification($user_id, $message);
        }
    }
});

add_action('woocommerce_email_order_meta', function ($order, $sent_to_admin, $plain_text, $email) {
    $tracking_number = get_post_meta($order->get_id(), '_tracking_number', true);
    $tracking_status = get_post_meta($order->get_id(), '_tracking_status', true);

    if ($tracking_number) {
        echo '<p><strong>' . __('Tracking Number:', 'your-textdomain') . '</strong> ' . esc_html($tracking_number) . '</p>';
    }
    if ($tracking_status) {
        echo '<p><strong>' . __('Tracking Status:', 'your-textdomain') . '</strong> ' . esc_html(ucwords(str_replace('_', ' ', $tracking_status))) . '</p>';
    }
}, 10, 4);

add_filter('woocommerce_account_menu_items', function ($items) {
    // Add the new "Tracking" tab after "Orders".
    $new_items = [];
    foreach ($items as $key => $value) {
        $new_items[$key] = $value;
        if ($key === 'orders') {
            $new_items['shipment-status'] = __('Shipment Status', 'your-textdomain');
        }
    }
    return $new_items;
});

add_action('init', function () {
    add_rewrite_endpoint('shipment-status', EP_ROOT | EP_PAGES);
});

add_action('woocommerce_account_shipment-status_endpoint', function () {
    $customer_orders = wc_get_orders([
        'customer_id' => get_current_user_id(),
        'limit' => -1,
    ]);

    if ($customer_orders) {
        echo '<h2>' . __('Shipment Status', 'your-textdomain') . '</h2>';
        echo '<table class="woocommerce-table woocommerce-table--shipment-status shop_table shop_table_responsive">';
        echo '<thead><tr>';
        echo '<th>' . __('Order', 'your-textdomain') . '</th>';
        echo '<th>' . __('Tracking Number', 'your-textdomain') . '</th>';
        echo '<th>' . __('Status', 'your-textdomain') . '</th>';
        echo '</tr></thead>';
        echo '<tbody>';
        ?>
        <?php foreach ($customer_orders as $order): ?>
            <?php
            $tracking_number = get_post_meta($order->get_id(), '_tracking_number', true);
            $tracking_status = get_post_meta($order->get_id(), '_tracking_status', true);
            if ($tracking_status === 'shipped') {
                $status_class = 'text-blue-400'; // Color for shipped
            } elseif ($tracking_status === 'in_transit') {
                $status_class = 'text-yellow-400'; // Color for in transit
            } elseif ($tracking_status === 'delivered') {
                $status_class = 'text-green-400'; // Color for delivered
            }
            if ($tracking_number || $tracking_status): ?>
                <tr>
                    <td>
                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>" title="Click to go to full order detail">
                            <?php echo 'Order No. #' . esc_html($order->get_order_number()); ?>
                        </a>
                    </td>
                    <td>
                        <?php echo esc_html($tracking_number ?: __('N/A', 'your-textdomain')); ?>
                    </td>
                    <td>
                        <?php echo esc_html(ucwords(str_replace('_', ' ', $tracking_status ?: __('N/A', 'your-textdomain')))); ?>
                    </td>
                </tr>
                <div class="flex justify-center gap-8">
                    <div class="w-16 h-16 text-red-400 <?php echo $status_class; ?>">
                        <?= get_svg('shipped'); ?>
                    </div>
                    <div class="w-16 h-16 <?php echo $status_class; ?>">
                        <?= get_svg('in-transit'); ?>
                    </div>
                    <div class="w-16 h-16 <?php echo $status_class; ?>">
                        <?= get_svg('delivered'); ?>
                    </div>
                </div>

            <?php endif; ?>
        <?php endforeach; ?>
        <?php
        echo '</tbody></table>';
    } else {
        echo '<p>' . __('No tracking information found.', 'your-textdomain') . '</p>';
    }
});