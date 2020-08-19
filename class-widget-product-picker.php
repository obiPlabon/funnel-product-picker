<?php
/**
 * Product picker widget class
 *
 * @package Funnel_Product_Picker
 */
namespace Funnel_Product_Picker;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

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

	public function _register_controls() {
		$this->start_controls_section(
			'_section_product',
			[
				'label' => __( 'Product', 'teachground' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'product_id',
			[
				'label' => __( 'Product Id', '@text-domain' ),
				'description' => __( 'WooCommerce variable product id', '@text-domain' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'package_key',
			[
				'label' => __( 'Attribute Name', '@text-domain' ),
				'description' => __( 'WooCommerce product attribute name in lowercase', '@text-domain' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'package',
			]
		);

		$this->add_control(
			'selected_package',
			[
				'label' => __( 'Selected Package', '@text-domain' ),
				'description' => __( 'WooCommerce product attribute value to set as default package', '@text-domain' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Buy 1'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_content',
			[
				'label' => __( 'Content', '@text-domain' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'condition_text',
			[
				'label' => __( 'Condition Text', '@text-domain' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => '*Depending on size of dog',
				'separator' => 'after'
			]
		);

		$this->add_control(
			'shipping_icon',
			[
				'label' => __( 'Shipping Icon', '@text-domain' ),
				'type' => Controls_Manager::ICONS,
			]
		);

		$this->add_control(
			'shipping_text',
			[
				'label' => __( 'Shipping Text', '@text-domain' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => 'We ship anywhere in the USA for free!',
				'separator' => 'after'
			]
		);

		$this->add_control(
			'onetime_button_text',
			[
				'label' => __( 'Buy Button Text', '@text-domain' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => 'Buy Now',
			]
		);

		$this->add_control(
			'subscription_button_text',
			[
				'label' => __( 'Subscription Button Text', '@text-domain' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => 'Subscribe Now',
			]
		);

		$this->end_controls_section();
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

		ob_start();
		\Elementor\Icons_Manager::render_icon( $settings[ 'shipping_icon' ], [] );
		$shipping_content = ob_get_clean();

		$view = new View( 2784, [
			'package_key'              => $settings['package_key'],
			'selected_package'         => $settings['selected_package'],
			'condition_text'           => $settings['condition_text'],
			'shipping_content'         => $shipping_content . $settings['shipping_text'],
			'onetime_button_text'      => $settings['onetime_button_text'],
			'subscription_button_text' => $settings['subscription_button_text'],
		] );
		$view->render();
	}
}
