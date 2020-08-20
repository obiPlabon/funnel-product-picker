<?php
/**
 * Plugin Name: Funnel Product Picker
 * Description: Product picker for a click funnel specially developed for BarkNutrition
 * Author: obiPlabon
 * Version: 1.0.0
 * Author URI: https://obiplabon.im
 * License: GPL2+
 * Text Domain: funnel-product-picker
 * Requires at least: 5.4
 * Requires PHP: 5.6
 *
 * @package Funnel_Product_Picker
 */

defined( 'ABSPATH' ) || exit;

class Funnel_Product_Picker {

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function init() {
		$this->add_hooks();
		include_once __DIR__ . '/class-elementor.php';
	}

	public function add_hooks() {
		add_filter( 'option_bvbadge', '__return_false' );
		add_action('wp_footer', [ $this, 'add_update_malcare_footer' ] );

		add_filter( 'woocommerce_subscription_period_interval_strings', [ $this, 'add_subscription_period_interval'] );
	}

	public function add_update_malcare_footer() {
		?>
		<div style="max-width:150px;min-height:70px;margin:0 auto;text-align:center;position:relative;">
			<a href="https://malcare.com?src=8E4D8F&amp;utm_source=mcbadge&amp;utm_medium=usersite&amp;utm_campaign=badge" target="_blank">
				<img width="100" src="<?php echo plugins_url( 'assets/img/secured-by-malcare.svg', __FILE__ ); ?>" alt="Malcare WordPress Security">
			</a>
		</div>
		<?php
	}

	public function add_subscription_period_interval( $intervals ) {
		$intervals[7] = sprintf( __( 'every %s', '@text-domain' ), WC_Subscriptions::append_numeral_suffix( 7 ) );
		$intervals[8] = sprintf( __( 'every %s', '@text-domain' ), WC_Subscriptions::append_numeral_suffix( 8 ) );
		$intervals[9] = sprintf( __( 'every %s', '@text-domain' ), WC_Subscriptions::append_numeral_suffix( 9 ) );
		$intervals[10] = sprintf( __( 'every %s', '@text-domain' ), WC_Subscriptions::append_numeral_suffix( 10 ) );
		$intervals[11] = sprintf( __( 'every %s', '@text-domain' ), WC_Subscriptions::append_numeral_suffix( 11 ) );
		return $intervals;
	}

}

new Funnel_Product_Picker();
