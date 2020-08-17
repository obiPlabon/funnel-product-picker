<?php
/**
 * Elementor integration class
 *
 * @package Funnel_Product_Picker
 */

namespace Funnel_Product_Picker;

use Elementor\Plugin;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class Elementor {

	const MINIMUM_ELEMENTOR_VERSION = '2.6.0';

	public function __construct() {
		// Check if Elementor installed and activated
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
			return;
		}

		// Check for required Elementor version
		if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
			return;
		}

		// Add Plugin actions
		add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
	}

	public function admin_notice_missing_main_plugin()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-extension'),
			'<strong>' . esc_html__('Funnel Product Picker', 'funnel-product-picker') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'funnel-product-picker') . '</strong>'
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}

	public function admin_notice_minimum_elementor_version()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'funnel-product-picker'),
			'<strong>' . esc_html__('Funnel Product Picker Extension', 'funnel-product-picker') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'funnel-product-picker') . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}

	public function register_widgets() {
		require_once __DIR__ . '/class-widget-product-picker.php';
		require_once __DIR__ . '/class-view.php';
		Plugin::instance()->widgets_manager->register_widget_type( new Widget_Product_Picker() );
	}
}

return new Elementor();
