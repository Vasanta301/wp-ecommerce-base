<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart'); ?>

<?php if (!WC()->cart->is_empty()): ?>

	<ul
		class="woocommerce-mini-cart cart_list product_list_widget divide-y divide-gray-200 <?php echo esc_attr($args['list_class']); ?>">
		<?php
		do_action('woocommerce_before_mini_cart_contents');

		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

			if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
				$product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
				$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
				$product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
				$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
				?>
				<li class="flex items-center py-4 space-x-4 woocommerce-mini-cart-item mini_cart_item">
					<a href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>"
						class="text-sm text-red-500 hover:text-red-700"
						aria-label="<?php echo esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), $product_name)); ?>"
						data-product_id="<?php echo esc_attr($product_id); ?>"
						data-cart_item_key="<?php echo esc_attr($cart_item_key); ?>"
						data-product_sku="<?php echo esc_attr($_product->get_sku()); ?>">
						&times;
					</a>

					<div class="flex-shrink-0">
						<?php if (empty($product_permalink)): ?>
							<?php echo $thumbnail; ?>
						<?php else: ?>
							<a href="<?php echo esc_url($product_permalink); ?>">
								<?php echo $thumbnail; ?>
							</a>
						<?php endif; ?>
					</div>

					<div class="flex-1">
						<?php if (empty($product_permalink)): ?>
							<span class="font-medium text-gray-800"><?php echo $product_name; ?></span>
						<?php else: ?>
							<a href="<?php echo esc_url($product_permalink); ?>" class="text-blue-500 hover:text-blue-700">
								<?php echo $product_name; ?>
							</a>
						<?php endif; ?>
						<div class="text-sm text-gray-500">
							<?php echo wc_get_formatted_cart_item_data($cart_item); ?>
						</div>
						<div class="text-sm text-gray-700">
							<?php echo sprintf('%s &times; %s', $cart_item['quantity'], $product_price); ?>
						</div>
					</div>
				</li>
				<?php
			}
		}

		do_action('woocommerce_mini_cart_contents');
		?>
	</ul>

	<p class="pt-4 mt-4 text-lg font-semibold border-t border-gray-300 woocommerce-mini-cart__total total">
		<?php
		/**
		 * Hook: woocommerce_widget_shopping_cart_total.
		 *
		 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
		 */
		do_action('woocommerce_widget_shopping_cart_total');
		?>
	</p>

	<?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

	<div class="flex justify-between mt-4 woocommerce-mini-cart__buttons buttons">
		<?php do_action('woocommerce_widget_shopping_cart_buttons'); ?>
	</div>

	<?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>

<?php else: ?>

	<p class="py-4 text-center text-gray-600 woocommerce-mini-cart__empty-message">
		<?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>

<?php endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>