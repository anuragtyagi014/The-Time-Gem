<?php

/**
 * My Subscriptions section on the My Account page
 *
 * @author   Prospress
 * @category WooCommerce Subscriptions/Templates
 * @version  2.6.4
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
?>
<div class="woocommerce_account_subscriptions">

	<?php if (!empty($subscriptions)) : ?>
		<table class="my_account_subscriptions my_account_orders woocommerce-orders-table woocommerce-MyAccount-subscriptions shop_table shop_table_responsive woocommerce-orders-table--subscriptions">

			<thead>
				<tr>
					<th class="subscription-id order-number woocommerce-orders-table__header woocommerce-orders-table__header-order-number woocommerce-orders-table__header-subscription-id"><span class="nobr"><?php esc_html_e('Subscription', 'woocommerce-subscriptions'); ?></span></th>
					<th class="subscription-status order-status woocommerce-orders-table__header woocommerce-orders-table__header-order-status woocommerce-orders-table__header-subscription-status"><span class="nobr"><?php esc_html_e('Status', 'woocommerce-subscriptions'); ?></span></th>
					<th class="subscription-next-payment order-date woocommerce-orders-table__header woocommerce-orders-table__header-order-date woocommerce-orders-table__header-subscription-next-payment"><span class="nobr"><?php echo esc_html_x('Next payment', 'table heading', 'woocommerce-subscriptions'); ?></span></th>
					<th class="subscription-total order-total woocommerce-orders-table__header woocommerce-orders-table__header-order-total woocommerce-orders-table__header-subscription-total"><span class="nobr"><?php echo esc_html_x('Total', 'table heading', 'woocommerce-subscriptions'); ?></span></th>
					<th class="subscription-actions order-actions woocommerce-orders-table__header woocommerce-orders-table__header-order-actions woocommerce-orders-table__header-subscription-actions">&nbsp;</th>
				</tr>
			</thead>

			<tbody>
				<?php /** @var WC_Subscription $subscription */ ?>
				<?php foreach ($subscriptions as $subscription_id => $subscription) : ?>
					<tr class="order woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr($subscription->get_status()); ?>">
						<td class="subscription-id order-number woocommerce-orders-table__cell woocommerce-orders-table__cell-subscription-id woocommerce-orders-table__cell-order-number" data-title="<?php esc_attr_e('ID', 'woocommerce-subscriptions'); ?>">
							<a href="<?php echo esc_url($subscription->get_view_order_url()); ?>"><?php echo esc_html(sprintf(_x('#%s', 'hash before order number', 'woocommerce-subscriptions'), $subscription->get_order_number())); ?></a>
							<?php do_action('woocommerce_my_subscriptions_after_subscription_id', $subscription); ?>
						</td>
						<td class="subscription-status order-status woocommerce-orders-table__cell woocommerce-orders-table__cell-subscription-status woocommerce-orders-table__cell-order-status" data-title="<?php esc_attr_e('Status', 'woocommerce-subscriptions'); ?>">
							<?php echo esc_attr(wcs_get_subscription_status_name($subscription->get_status())); ?>
						</td>
						<td class="subscription-next-payment order-date woocommerce-orders-table__cell woocommerce-orders-table__cell-subscription-next-payment woocommerce-orders-table__cell-order-date" data-title="<?php echo esc_attr_x('Next Payment', 'table heading', 'woocommerce-subscriptions'); ?>">
							<?php
							if (stripos($subscription->get_date_to_display('next_payment'), ', 21') !== false) {
							} else {
								echo esc_attr($subscription->get_date_to_display('next_payment'));
							} ?>
							<?php if (!$subscription->is_manual() && $subscription->has_status('active') && $subscription->get_time('next_payment') > 0) : ?>
								<br /><small><?php echo esc_attr($subscription->get_payment_method_to_display('customer')); ?></small>
							<?php endif; ?>
						</td>
						<td class="subscription-total order-total woocommerce-orders-table__cell woocommerce-orders-table__cell-subscription-total woocommerce-orders-table__cell-order-total" data-title="<?php echo esc_attr_x('Total', 'Used in data attribute. Escaped', 'woocommerce-subscriptions'); ?>">
							<?php echo preg_replace('/ every \d+ years/', '', wp_kses_post($subscription->get_formatted_order_total())); ?>
						</td>
						<td class="subscription-actions order-actions woocommerce-orders-table__cell woocommerce-orders-table__cell-subscription-actions woocommerce-orders-table__cell-order-actions">
							<a href="<?php echo esc_url($subscription->get_view_order_url()) ?>" class="woocommerce-button button view"><?php echo esc_html_x('View', 'view a subscription', 'woocommerce-subscriptions'); ?></a>
							<?php do_action('woocommerce_my_subscriptions_actions', $subscription); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>

		</table>
		<?php if (1 < $max_num_pages) : ?>
			<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
				<?php if (1 !== $current_page) : ?>
					<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url(wc_get_endpoint_url('subscriptions', $current_page - 1)); ?>"><?php esc_html_e('Previous', 'woocommerce-subscriptions'); ?></a>
				<?php endif; ?>

				<?php if (intval($max_num_pages) !== $current_page) : ?>
					<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url(wc_get_endpoint_url('subscriptions', $current_page + 1)); ?>"><?php esc_html_e('Next', 'woocommerce-subscriptions'); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<?php
		if (1 < $current_page) {
			echo '<p class="no_subscriptions woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">';
			printf(esc_html__('You have reached the end of subscriptions. Go to the %sfirst page%s.', 'woocommerce-subscriptions'), '<a href="' . esc_url(wc_get_endpoint_url('subscriptions', 1)) . '">', '</a>');
			echo '</p>';
		} else {

			wc_print_notice(esc_html__('You have no active subscriptions.', 'woocommerce') . ' <a class="woocommerce-Button button" href="' . get_site_url() . '/pricing/">Get Time Gem</a>', 'notice');
		}
		?>

	<?php endif; ?>

</div>