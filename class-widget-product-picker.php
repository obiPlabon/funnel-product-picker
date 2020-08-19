<?php
/**
 * Product picker widget class
 *
 * @package Funnel_Product_Picker
 */
namespace Funnel_Product_Picker;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

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

		/**
		 * Style controls
		 */
		$this->start_controls_section(
			'_section_style_content',
			[
				'label' => __( 'Package', '@text-domain' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_heading_top_price',
			[
				'label' => __( 'Top Price', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'price_bottom_spacing',
			[
				'label' => __( 'Bottom Spacing', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .funnel-picker__top-price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .funnel-picker__top-price',
			]
		);

		$this->add_control(
			'_heading_package',
			[
				'label' => __( 'Package Heading', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'package_heading_bottom_spacing',
			[
				'label' => __( 'Bottom Spacing', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .funnel-picker__options-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'package_heading_typography',
				'selector' => '{{WRAPPER}} .funnel-picker__options-heading',
			]
		);

		$this->add_control(
			'_heading_package_title',
			[
				'label' => __( 'Package', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'package_title_bottom_spacing',
			[
				'label' => __( 'Title Bottom Spacing', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .funnel-picker__options-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'package_title_typography',
				'selector' => '{{WRAPPER}} .funnel-picker__options-title',
			]
		);

		$this->add_control(
			'_heading_package_price',
			[
				'label' => __( 'Price', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'package_price_typography',
				'selector' => '{{WRAPPER}} .funnel-picker__options-price',
			]
		);

		$this->add_control(
			'_heading_condition_text',
			[
				'label' => __( 'Condition Text', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'conditional_text_typography',
				'selector' => '{{WRAPPER}} .funnel-picker__options-tagline',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_bottom',
			[
				'label' => __( 'Bottom', '@text-domain' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_heading_options',
			[
				'label' => __( 'Options', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'options_typography',
				'selector' => '{{WRAPPER}} .wcsatt-options-prompt-label span',
			]
		);

		$this->add_control(
			'_heading_shipping_text',
			[
				'label' => __( 'Shipping Text', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'options_typography',
				'selector' => '{{WRAPPER}} .funnel-picker__shipping-text',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_button',
			[
				'label' => __( 'Button', '@text-domain' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', '@text-domain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .single_add_to_cart_button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .single_add_to_cart_button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', '@text-domain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .single_add_to_cart_button',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( '_tabs_button' );

		$this->start_controls_tab(
			'_tab_button_normal',
			[
				'label' => __( 'Normal', '@text-domain' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => __( 'Text Color', '@text-domain' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .single_add_to_cart_button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', '@text-domain' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single_add_to_cart_button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_button_hover',
			[
				'label' => __( 'Hover', '@text-domain' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', '@text-domain' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', '@text-domain' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', '@text-domain' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

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

		$this->add_render_attribute( '_wrapper', 'class', 'woocommerce' );

		$this->add_style_depends( 'funnel-product-picker' );
		$this->add_script_depends( 'funnel-product-picker' );

		ob_start();
		\Elementor\Icons_Manager::render_icon( $settings[ 'shipping_icon' ], [] );
		$shipping_content = ob_get_clean();

		$view = new View( $settings['product_id'], [
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
