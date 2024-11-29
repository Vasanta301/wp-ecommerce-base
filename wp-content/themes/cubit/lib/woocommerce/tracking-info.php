<?php
add_action('woocommerce_admin_order_data_after_shipping_address', 'update_notification_status_for_shipment_tracking', 10, 1);
add_action('woocommerce_process_shop_order_meta', 'woocommerce_process_shop_order_meta_to_update_tracking_info', 10, 1);
add_action('woocommerce_email_order_meta', 'woocommerce_email_order_meta_add_shipment_tracking_info', 10, 3);
add_filter('woocommerce_account_menu_items', 'woocommerce_account_menu_items_shipment_status', 10, 1);
add_action('woocommerce_account_shipment-status_endpoint', 'woocommerce_account_shipment_status_endpoint');

/**
 * UPdate notification status for shipment tracking
 *
 * @param [type] $order
 * @return void
 */
function update_notification_status_for_shipment_tracking($order) {
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
}

/**
 * Process shop order meta to update tracking info
 *
 * @param [type] $post_id
 * @return void
 */
function woocommerce_process_shop_order_meta_to_update_tracking_info($post_id) {
    // Verify nonce
    if (!isset($_POST['tracking_info_nonce']) || !wp_verify_nonce($_POST['tracking_info_nonce'], 'save_tracking_info')) {
        return;
    }

    // Retrieve the current tracking information
    $current_tracking_number = get_post_meta($post_id, '_tracking_number', true);
    $current_tracking_status = get_post_meta($post_id, '_tracking_status', true);

    // Get new values from the form
    $new_tracking_number = isset($_POST['tracking_number']) ? sanitize_text_field($_POST['tracking_number']) : '';
    $new_tracking_status = isset($_POST['tracking_status']) ? sanitize_text_field($_POST['tracking_status']) : '';

    // Check for changes and update meta only if the value is different
    $tracking_updated = false;

    if ($new_tracking_number !== $current_tracking_number) {
        update_post_meta($post_id, '_tracking_number', $new_tracking_number);
        $tracking_updated = true;
    }

    if ($new_tracking_status !== $current_tracking_status) {
        update_post_meta($post_id, '_tracking_status', $new_tracking_status);
        $tracking_updated = true;
    }

    // Add an admin note and notification only if there's a change
    if ($tracking_updated) {
        $order = wc_get_order($post_id);

        if ($order && function_exists('save_user_notification')) {
            // Set user ID for the notification
            $user_id = $order->get_user_id();

            // URL source for order tracking
            $source = get_tracking_dashboard_url($user_id);

            // Build the notification message
            $message = __('Tracking information updated:', 'your-textdomain') . "\n";
            if (!empty($new_tracking_number)) {
                $message .= __('Tracking Number:', 'your-textdomain') . ' ' . $new_tracking_number . "\n";
            }
            $message .= __('Tracking Status:', 'your-textdomain') . ' ' . ucwords(str_replace('_', ' ', $new_tracking_status));

            // Add an admin note to the order
            $order->add_order_note($message);

            // Trigger the notification
            save_user_notification($user_id, $source, $message);
        }
    }
}

/**
 * Add Shipment Tracking info in order email
 *
 * @param [type] $order
 * @param [type] $sent_to_admin
 * @param [type] $plain_text
 * @param [type] $email
 * @return void
 */
function woocommerce_email_order_meta_add_shipment_tracking_info($order, $sent_to_admin, $plain_text, $email) {
    $tracking_number = get_post_meta($order->get_id(), '_tracking_number', true);
    $tracking_status = get_post_meta($order->get_id(), '_tracking_status', true);

    if ($tracking_number) {
        echo '<p><strong>' . __('Tracking Number:', 'your-textdomain') . '</strong> ' . esc_html($tracking_number) . '</p>';
    }
    if ($tracking_status) {
        echo '<p><strong>' . __('Tracking Status:', 'your-textdomain') . '</strong> ' . esc_html(ucwords(str_replace('_', ' ', $tracking_status))) . '</p>';
    }
}

/**
 * Add Shipment Tracking info in User Dashboardaccount menu
 *
 * @param [type] $items
 * @return void
 */
function woocommerce_account_menu_items_shipment_status($items) {
    // Add the new "Tracking" tab after "Orders".
    $new_items = [];
    foreach ($items as $key => $value) {
        $new_items[$key] = $value;
        if ($key === 'orders') {
            $new_items['shipment-status'] = __('Shipment Status', 'your-textdomain');
        }
    }
    return $new_items;
}

/**
 * Add account Dashboard tab to display shipment status
 *
 * @return void
 */
//TODO : Add design to display step - (icon checkbox) and horizontal line - upto delivery. 
function woocommerce_account_shipment_status_endpoint() {
    $customer_orders = wc_get_orders([
        'customer_id' => get_current_user_id(),
        'limit' => -1,
    ]);

    if ($customer_orders):
        ?>
        <h2><?= __('Shipment Status', 'your-textdomain') ?></h2>
        <div class="flex flex-col woocommerce-table woocommerce-table--shipment-status gap-y-4">
            <?php foreach ($customer_orders as $order): ?>
                <?php
                $tracking_number = get_post_meta($order->get_id(), '_tracking_number', true);
                $tracking_status = get_post_meta($order->get_id(), '_tracking_status', true);
                $statuses = ['shipped', 'in_transit', 'delivered'];
                $base_class = 'w-16 h-16';
                $current_status_index = array_search($tracking_status, $statuses);
                if ($tracking_number || $tracking_status): ?>
                    <div class="w-full p-4 shadow-md gap-y-4">
                        <div class="flex flex-col gap-4 pb-8 ">
                            <div class="flex gap-4">
                                <span class="text-lg font-bold"><?php _e('Order Number', 'your-textdomain'); ?>
                                    <?php echo '#' . esc_html($order->get_order_number()); ?>:</span>
                                <a href="<?php echo esc_url($order->get_view_order_url()); ?>" title="Click to go to full order detail">
                                    <?php _e('View Order', 'your-textdomain'); ?>
                                </a>
                            </div>
                            <?php if ($tracking_number): ?>
                                <div class="flex gap-4">
                                    <span class="text-lg font-bold"><?php _e('Tracking Number:', 'your-textdomain'); ?></span>
                                    <span><?php echo esc_html($tracking_number ?: __('N/A', 'your-textdomain')); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($tracking_status): ?>
                                <div class="flex gap-4">
                                    <span class="text-lg font-bold"><?php _e('Delivery Status:', 'your-textdomain'); ?></span>
                                    <span><?php echo esc_html(ucwords(str_replace('_', ' ', $tracking_status ?: __('N/A', 'your-textdomain')))); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex justify-center gap-8">
                            <?php
                            foreach ($statuses as $index => $status): ?>
                                <?php
                                $is_completed = $index <= $current_status_index;
                                $status_class = $is_completed ? 'text-green-400' : 'text-gray-400';
                                $line_class = $is_completed ? 'border-green-400' : 'border-gray-400';
                                ?>
                                <div class="flex flex-col items-center">
                                    <div class="<?= esc_attr($base_class . ' ' . $status_class); ?>">
                                        <?= get_svg($status); ?>
                                    </div>
                                    <?php if ($is_completed): ?>
                                        <span class="block w-6 h-6 mt-1 text-green-400"><?= get_icon('check'); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($index < count($statuses) - 1): ?>
                                    <hr class="w-12 border-t-2 <?= esc_attr($line_class); ?>" />
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div>
            <p> <?php echo __('No tracking information found.', 'your-textdomain'); ?></p>
        </div>
    <?php endif ?>
<?php
}

/**
 * reqwrite endpoints to make new custom tab work in User Dashboard
 */
add_action('init', function () {
    add_rewrite_endpoint('shipment-status', EP_ROOT | EP_PAGES);
});
