<?php

/**
 * Recurring cart subtotals totals
 *
 * @author  WooCommerce
 * @package WooCommerce Subscriptions/Templates
 * @version 3.1.0
 */

defined('ABSPATH') || exit;
$display_heading = true;

foreach ($recurring_carts as $recurring_cart_key => $recurring_cart) {
	$isLifetime = false;
	foreach ($recurring_cart->cart_contents as $k => $v) {
		if (!empty($v['variation_id'])) {
			if (trim($v['variation_id']) == 1789) {
				$isLifetime = true;
			}
		}
	}
?>

	<tr class="order-total recurring-total">
		<?php if (!$isLifetime) {
			if ($display_heading) { ?>
				<?php $display_heading = false; ?>
				<th rowspan="<?php echo esc_attr(count($recurring_carts)); ?>"><?php esc_html_e('Recurring total', 'woocommerce-subscriptions'); ?></th>
				<td data-title="<?php esc_attr_e('Recurring total', 'woocommerce-subscriptions'); ?>"><?php wcs_cart_totals_order_total_html($recurring_cart); ?></td>
			<?php } else { ?>
				<td><?php wcs_cart_totals_order_total_html($recurring_cart); ?></td>
			<?php } ?>
	</tr> <?php
		}
	}
