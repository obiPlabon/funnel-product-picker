<?php
/**
 * Product picker widget class
 *
 * @package Funnel_Product_Picker
 */
namespace Funnel_Product_Picker;

use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class Widget_Product_Picker extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'funnel_product_picker';
	}

	/**
	 * Get widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Product Picker', '@text-domain' );
	}

	/**
	 * Get widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-share';
	}

	public function register_controls() {

	}

	/**
	 * Render widget contents
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->add_style_depends( 'funnel-product-picker' );
		$this->add_script_depends( 'funnel-product-picker' );
		// echo do_shortcode( '[product_page id="2784"]');
		$view = new View( 2784, [ 'widget_instance' => $this ] );
		$view->render();
	}
}
