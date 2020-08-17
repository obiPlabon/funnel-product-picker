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
		include_once __DIR__ . '/class-elementor.php';
	}
}

new Funnel_Product_Picker();
