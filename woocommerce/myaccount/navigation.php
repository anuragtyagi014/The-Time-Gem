<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation">

	<div class="nectar-cta  alignment_tablet_default alignment_phone_default display_tablet_inherit display_phone_inherit " data-color="extra-color-2" data-using-bg="true" data-style="text-reveal-wave" data-display="block" data-alignment="left" data-text-color="custom" style="margin-top: 35px; ">
		<h5 style="color: #f7f7f7;">
			<span class="link_wrap hover" style="padding-top: 15px; padding-right: 35px; padding-bottom: 15px; padding-left: 35px;">
				<a class="link_text" href="<?php echo get_site_url()?>/pricing/" style="text-decoration:none;"><span class="text">Get Time Gem</span></a>
			</span>
		</h5>
	</div>

	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
