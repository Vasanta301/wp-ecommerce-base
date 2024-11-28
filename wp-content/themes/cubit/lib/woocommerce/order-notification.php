<?php
add_action('after_switch_theme', 'create_notification_table_on_theme_activation');
add_action('rest_api_init', 'register_order_notification_api_routes');
add_action('wp_footer', 'order_notification_custom_alpine');
add_action('woocommerce_new_order', 'notify_user_on_order_event', 10, 1);
add_action('woocommerce_order_status_changed', 'notify_user_on_order_event', 10, 3);
add_shortcode('cubit_order_notification', 'cubit_order_notification_shortcode_callback');

function create_notification_table_on_theme_activation() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_notifications';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        message TEXT NOT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function register_order_notification_api_routes() {
    register_rest_route('notification/v1', '/count', array(
        'methods' => 'GET',
        'callback' => 'get_notification_count',
    ));
    register_rest_route('notification/v1', '/mark-read', [
        'methods' => 'POST',
        'callback' => 'mark_notification_as_read',
        'permission_callback' => function () {
            return is_user_logged_in(); // Ensure user is logged in
        },
    ]);
    register_rest_route('notification/v1', '/content', array(
        'methods' => 'GET',
        'callback' => 'get_notifications_content',
        'permission_callback' => '__return_true',
    ));
}

function get_notification_count(WP_REST_Request $request) {
    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_REST_Response('Unauthorized', 401);
    }
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'user_notifications';

    $notification_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND is_read = 0",
        $user_id
    ));
    return new WP_REST_Response(array('count' => $notification_count), 200);
}
function mark_notification_as_read(WP_REST_Request $request) {
    $notification_id = intval($request->get_param('notification_id'));
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_notifications';

    $updated = $wpdb->update(
        $table_name,
        ['is_read' => 1],
        ['id' => $notification_id]
    );

    if ($updated === false) {
        return new WP_Error('db_error', 'Failed to mark notification as read.', ['status' => 500]);
    }

    return rest_ensure_response([
        'success' => true,
        'message' => 'Notification marked as read.',
    ]);
}

function get_notifications_content(WP_REST_Request $request) {
    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_REST_Response('Unauthorized', 401);
    }
    ob_start(); ?>
    <?php
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'user_notifications';

    // Fetch notifications for the logged-in user
    $notifications = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE user_id = %d ORDER BY created_at DESC LIMIT 10",
        $user_id
    ));

    // Get the count of unread notifications
    $notification_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND is_read = 0",
        $user_id
    ));
    ?>
    <?php if ($notifications): ?>
        <ul class="flex flex-col gap-2">
            <?php foreach ($notifications as $notification):
                $message = $notification->message;
                preg_match('/#(\d+)/', $message, $matches);
                $order_id = isset($matches[1]) ? $matches[1] : '';
                $order_url = esc_url(wc_get_endpoint_url('view-order', $order_id, wc_get_page_permalink('myaccount')));
                ?>
                <li class="flex flex-col p-2 border-b <?= $notification->is_read == 1 ? 'text-gray-500' : 'text-black'; ?>"
                    id="notification-<?php echo esc_attr($notification->id); ?>">
                    <!-- TODO : Define notice origin either order, shipment status to determine permalink -->
                    <a href="<?php echo $order_url; ?>">
                        <?php echo wp_kses_post($message); ?>
                    </a>
                    <small class="text-sm text-gray-600"><?php echo esc_html($notification->created_at); ?></small>
                    <?php if ($notification->is_read == 0): ?>
                        <a href="#" @click.prevent="markNotificationAsRead(<?php echo esc_js($notification->id); ?>)">Mark
                            as read</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <ul class="space-y-2">
            <li class="p-2 border-b border-gray-200">
                <p class="text-gray-600">No new notifications</p>
            </li>
        </ul>
    <?php endif; ?>
    <?php $html = ob_get_clean();
    return new WP_REST_Response(array('html' => $html), 200);
}
function notify_user_on_order_event($order_id, $old_status = '', $new_status = '') {
    if (!function_exists('wc_get_order'))
        return;

    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();

    if (!$user_id)
        return; // Skip if no user is associated with the order

    $message = '';
    if ($old_status === '') {
        $message = "Your order #{$order_id} has been placed successfully.";
    } else {
        $message = "The status of your order #{$order_id} has changed to {$new_status}.";
    }

    save_user_notification($user_id, $message);
}

function save_user_notification($user_id, $message) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_notifications';

    $wpdb->insert($table_name, [
        'user_id' => $user_id,
        'message' => $message,
        'is_read' => 0,
        'created_at' => current_time('mysql'),
    ]);
}

function cubit_order_notification_shortcode_callback() {
    ob_start();

    if (!is_user_logged_in()) {
        return '';
    }

    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'user_notifications';

    // Fetch notifications for the logged-in user
    $notifications = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE user_id = %d ORDER BY created_at DESC LIMIT 10",
        $user_id
    ));
    ?>
    <div x-data="notificationsManager({wishlist:<?php echo json_encode_alpine($notifications); ?>})"
        x-init="$nextTick(() => window.notificationsManagerInstance = $data)"
        @wishlistUpdated.window="refreshnotificationsFragments">
        <div class="relative flex-1 menu-item notifications group">
            <div class="flex flex-row-reverse p-2 px-4 truncate rounded cursor-pointer"
                @click="notifOpen = !notifOpen; document.dispatchEvent(new Event('NotificationsUpdated'));">
                <div class="relative">
                    <span class="sr-only"><?= __('Open Notification', 'translation-ready'); ?></span>
                    <div class="absolute top-0 right-0 px-1 -mt-1 -mr-2 text-xs font-bold text-white bg-red-700 rounded-full notification-count"
                        x-text="notifCount">
                    </div>
                </div>
                <div class="w-8 h-8">
                    <?= get_svg('notification'); ?>
                </div>
            </div>
            <div class="fixed inset-y-0 right-0 z-50 w-full h-full max-w-full pt-8 overflow-y-auto bg-white shadow-md md:max-w-sm"
                x-show="notifOpen" x-cloak x-effect="document.body.classList.toggle('modal-open', notifOpen)"
                x-transition:enter="transform transition ease-out duration-500"
                x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transform transition ease-in duration-300"
                x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
                @click.away="notifOpen = false">
                <h3 class="pt-8 text-xl font-semibold text-center">Notifications</h3>
                <div class="p-4 notifications-content notification-items">
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
}



function order_notification_custom_alpine() {
    ?>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('notificationsManager', (config) => ({
                    notifications: config.notifications || [],
                    notifOpen: false,
                    notifCount: 0,
                    orderId: null,
                    init() {
                        this.updatenotificationCount();
                    },
                    markNotificationAsRead(notificationId) {
                        fetch('/wp-json/notification/v1/mark-read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>',
                            },
                            body: JSON.stringify({ notification_id: notificationId }),
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                if (data.success) {
                                    // Remove the notification from the list
                                    this.notifications = this.notifications.filter(
                                        (notification) => notification.id !== notificationId
                                    );
                                    // Update notification count
                                    this.updatenotificationCount();
                                } else {
                                    console.error(data.message || 'Failed to mark notification as read.');
                                }
                            })
                            .catch((error) => console.error('Error:', error));
                    },
                    updatenotificationCount() {
                        fetch('/wp-json/notification/v1/count', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>',
                            },
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                this.notifCount = data.count || 0;
                                const notifCount = document.querySelector('.notification-count');
                                if (notifCount) {
                                    notifCount.textContent = data.count || 0;
                                }
                                document.dispatchEvent(new Event('NotificationsUpdated'));
                            });
                    },
                }));
            });

            document.addEventListener('NotificationsUpdated', function () {
                refreshNotificationsFragments()
            });

            function refreshNotificationsFragments() {
                const wishlistPopupContainer = document.querySelector('.notification-items');
                if (wishlistPopupContainer) {
                    fetch('/wp-json/notification/v1/content', {
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
