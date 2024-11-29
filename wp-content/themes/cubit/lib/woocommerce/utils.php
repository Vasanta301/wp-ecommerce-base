<?php

/**
 * Utility Function : Get Tracking Dashboard URL
 */
function get_tracking_dashboard_url($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id(); // Defaults to the current user.
    }
    return esc_url(add_query_arg('shipment-status', '', wc_get_page_permalink('myaccount')));
}