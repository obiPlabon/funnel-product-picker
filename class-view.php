<?php
/**
 * Product view class
 *
 * @package Funnel_Product_Picker
 */
namespace Funnel_Product_Picker;

defined( 'ABSPATH' ) || exit;

class View {

	protected $product_id;

	public static $attribute_key;

	public static $selected_value;

	public function __construct( $product_id, $args = [] ) {
		$this->product_id = $product_id;
		$this->args = $args;

		self::$attribute_key = 'attribute_package';
		self::$selected_value = 'Buy 1';
	}

	public static function get_radio_options( $options = [] ) {
		ob_start();
		include_once __DIR__ . '/templates/product-subscription-options-prompt-radio.php';
		return ob_get_clean();
	}

	public static function update_dropdown_option_price_html_args( $args ) {
		$args['hide_price'] = true;
		$args['append_price'] = false;
		$args['allow_discount'] = false;

		return $args;
	}

	public function add_shipping_text() {
		?>
		<div class="funnel-picker__shipping-text">We ship anywhere in the USA for free!</div>
		<?php
	}

	public function update_add_to_cart_text() {
		return __( 'Buy Now', '@text-domain' );
	}

	public function update_subscription_add_to_cart_text() {
		return __( 'Subscribe Now', '@text-domain' );
	}

	public function load_local_template( $template, $template_name ) {
		$templates = [
			'single-product/add-to-cart/variable.php' => 'variable.php',
			'single-product/add-to-cart/variation-add-to-cart-button.php' => 'variation-add-to-cart-button.php',
			'single-product/product-subscription-options.php' => 'product-subscription-options.php',
		];

		if ( isset( $templates[ $template_name ] ) && is_readable( __DIR__ . '/templates/' . $templates[ $template_name ] ) ) {
			return __DIR__ . '/templates/' . $templates[ $template_name ];
		}

		return $template;
	}

	protected function maybe_update_hooks() {
		// Change form action to avoid redirect.
		add_filter( 'woocommerce_add_to_cart_form_action', '__return_empty_string' );

		add_filter( 'woocommerce_product_single_add_to_cart_text', [ $this, 'update_add_to_cart_text' ] );

		add_filter( 'wcsatt_single_add_to_cart_text', [ $this, 'update_subscription_add_to_cart_text' ] );

		add_action( 'woocommerce_single_variation', [ $this, 'add_shipping_text' ], 15 );

		add_filter( 'wc_get_template', [ $this, 'load_local_template' ], 10, 2 );

		add_filter( 'wcsatt_single_product_subscription_dropdown_option_price_html_args', [ __CLASS__, 'update_dropdown_option_price_html_args' ] );
	}

	protected function maybe_restore_hooks() {
		remove_filter( 'woocommerce_add_to_cart_form_action', '__return_empty_string' );

		remove_filter( 'woocommerce_product_single_add_to_cart_text', [ $this, 'update_add_to_cart_text' ] );

		remove_filter( 'wcsatt_single_add_to_cart_text', [ $this, 'update_subscription_add_to_cart_text' ] );

		remove_action( 'woocommerce_single_variation', [ $this, 'add_shipping_text' ], 15 );

		remove_filter( 'wc_get_template', [ $this, 'load_local_template' ], 10, 2 );

		remove_filter( 'wcsatt_single_product_subscription_dropdown_option_price_html_args', [ __CLASS__, 'update_dropdown_option_price_html_args' ] );
	}

	public function render() {
		if ( empty( $this->product_id ) ) {
			return;
		}

		$args = array(
			'posts_per_page'      => 1,
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
		);

		if ( $this->product_id ) {
			$args['p'] = absint( $this->product_id );
		}

		$this->maybe_update_hooks();

		$single_product = new \WP_Query( $args );

		// For "is_single" to always make load comments_template() for reviews.
		$single_product->is_single = true;

		global $wp_query;

		// Backup query object so following loops think this is a product page.
		$previous_wp_query = $wp_query;
		// @codingStandardsIgnoreStart
		$wp_query          = $single_product;
		// @codingStandardsIgnoreEnd

		wp_enqueue_script( 'wc-single-product' );

		while ( $single_product->have_posts() ) {
			$single_product->the_post();
			$this->render_product();
		}

		// Restore $previous_wp_query and reset post data.
		// @codingStandardsIgnoreStart
		$wp_query = $previous_wp_query;
		// @codingStandardsIgnoreEnd
		wp_reset_postdata();

		$this->maybe_restore_hooks();
	}

	protected function render_product() {
		?>
		<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'funnel-picker', $product ); ?>>
			<?php $this->get_product_template(); ?>
		</div>
		<?php
	}

	protected function get_product_template() {
		global $product;

		// Enqueue variation scripts.
		wp_enqueue_script( 'wc-add-to-cart-variation' );

		// Get Available variations?
		$get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

		// Load the template.
		wc_get_template(
			'single-product/add-to-cart/variable.php',
			array(
				'available_variations' => $get_variations ? $product->get_available_variations() : false,
				'attributes'           => $product->get_variation_attributes(),
				'selected_attributes'  => $product->get_default_attributes(),
				// 'elementor_widget'	   => $this->args['widget_instance'],
			)
		);
	}
}
