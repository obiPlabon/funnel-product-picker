<?php
/**
 * Product Subscription Options Radio Prompt Template.
 *
 * Override this template by copying it to 'yourtheme/woocommerce/single-product/product-subscription-options-prompt-radio.php'.
 *
 * On occasion, this template file may need to be updated and you (the theme developer) will need to copy the new files to your theme to maintain compatibility.
 * We try to do this as little as possible, but it does happen.
 * When this occurs the version of the template file will be bumped and the readme will list any important changes.
 *
 * @version 3.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

// $base_subscription = WCS_ATT_Product_Schemes::get_base_subscription_scheme( $product );
// $available_variations = $product->get_available_variations();
// $onetime_prices = [];
// $subscription_prices = [];

// // [price] => 42.46
// // [regular_price] => 49.95
// // [sale_price] => 42.46
// // echo '<pre>';
// $_counter = 1;
// foreach ( $available_variations as $_variation ) {
// 	$prices = $base_subscription->get_prices( [
// 		'price' => $_variation['display_price']
// 	] );

// 	$onetime_prices[] = sprintf(
// 		'<span class="funnel-onetime-price-radio funnel-product-%1$s">%2$s</span>'
// 		. '<span class="funnel-onetime-price-top funnel-product-%1$s">%2$s (%3$s  / bottle)</span>',
// 		$_variation['variation_id'],
// 		wc_price( $prices['regular_price'] ),
// 		wc_price( $_variation['regular_price'] / $_counter )
// 	);

// 	$subscription_prices[] = sprintf(
// 		'<span class="funnel-subscription-price-radio funnel-product-%1$s">%2$s (Save %3$s)</span>'
// 		. '<span class="funnel-subscription-price-top funnel-product-%1$s">%2$s (%4$s  / bottle)</span>',
// 		$_variation['variation_id'],
// 		wc_price( $prices['sale_price'] ),
// 		wc_price( $prices['regular_price'] - $prices['sale_price'] ),
// 		wc_price( $_variation['sale_price'] / $_counter )
// 	);

// 	$_counter += 2;
// }

// echo esc_html( implode( "\n", $onetime_prices));

// echo $base_subscription->get_key();
//
// echo WCS_ATT_Product_Prices::get_sale_price( $product );
// echo $subscriptions->get_sale_price();
// print_r( get_class_methods( $base_subscription  ));
// echo '</pre>';


// die();
?>

<ul class="wcsatt-options-prompt-radios">
	<li class="wcsatt-options-prompt-radio">
		<label class="wcsatt-options-prompt-label wcsatt-options-prompt-label-one-time">
			<input class="wcsatt-options-prompt-action-input" type="radio" name="subscribe-to-action-input" value="no" />
			<span class="wcsatt-options-prompt-action"><?php esc_html_e( 'One-time Purchase', '@text-domain' ); ?></span>
		</label>
	</li>
	<li class="wcsatt-options-prompt-radio">
		<label class="wcsatt-options-prompt-label wcsatt-options-prompt-label-subscription">
			<input class="wcsatt-options-prompt-action-input" type="radio" name="subscribe-to-action-input" value="yes" />
			<span class="wcsatt-options-prompt-action"><?php esc_html_e( 'Subscribe & Save — 10% Off', '@text-domain' ); ?></span>
		</label>
	</li>
</ul>
