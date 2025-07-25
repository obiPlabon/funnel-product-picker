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

	protected static $_counter = 1;

	protected static $_counter_data = 1;

	public function __construct( $product_id, $args = [] ) {
		$this->product_id = $product_id;
		$this->args = $args;
	}

	protected function maybe_update_hooks() {
		// Change form action to avoid redirect.
		add_filter( 'woocommerce_add_to_cart_form_action', '__return_empty_string' );

		add_filter( 'woocommerce_product_single_add_to_cart_text', [ $this, 'update_add_to_cart_text' ] );

		add_filter( 'wcsatt_single_add_to_cart_text', [ $this, 'update_subscription_add_to_cart_text' ] );

		add_action( 'woocommerce_single_variation', [ $this, 'add_shipping_text' ], 15 );

		add_filter( 'wc_get_template', [ $this, 'load_local_template' ], 10, 2 );

		add_filter( 'woocommerce_available_variation', array( __CLASS__, 'add_variation_data' ), 0, 3 );

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

	public static function add_variation_data( $variation_data, $variable_product, $variation_product ) {
		$base_subscription = \WCS_ATT_Product_Schemes::get_base_subscription_scheme( $variation_product );

		$prices = $base_subscription->get_prices( [
			'price' => $variation_product->get_price( 'edit' )
		] );

		$variation_data['funnel_ot_bottle_price'] = wc_price( $prices['regular_price'] / self::$_counter_data ) . ' / bottle';
		$variation_data['funnel_subs_bottle_price'] = wc_price( $prices['sale_price'] / self::$_counter_data ) . ' / bottle';

		self::$_counter_data += 2;

		return $variation_data;
	}

	public static function get_radio_options( $product ) {
		$base_subscription = \WCS_ATT_Product_Schemes::get_base_subscription_scheme( $product );

		$prices = $base_subscription->get_prices( [
			'price' => $product->get_price( 'edit' )
		] );

		$onetime_price = sprintf(
			'<span class="funnel-onetime-price-radio funnel-product-%1$s">%2$s</span>',
			$product->get_id(),
			wc_price( $prices['regular_price'] )
		);

		$subscription_price = sprintf(
			'<span class="funnel-subscription-price-radio funnel-product-%1$s">%2$s (Save %3$s)</span>',
			$product->get_id(),
			wc_price( $prices['sale_price'] ),
			wc_price( $prices['regular_price'] - $prices['sale_price'] )
		);

		ob_start();

		?>
		<ul class="wcsatt-options-prompt-radios">
			<li class="wcsatt-options-prompt-radio">
				<label class="wcsatt-options-prompt-label wcsatt-options-prompt-label-one-time">
					<input class="wcsatt-options-prompt-action-input" type="radio" name="subscribe-to-action-input" value="no" />
					<span class="wcsatt-options-prompt-action"><?php esc_html_e( 'One-time Purchase', '@text-domain' ); ?></span>
					<?php echo $onetime_price ?>
				</label>
			</li>
			<li class="wcsatt-options-prompt-radio">
				<label class="wcsatt-options-prompt-label wcsatt-options-prompt-label-subscription">
					<input class="wcsatt-options-prompt-action-input" type="radio" name="subscribe-to-action-input" value="yes" />
					<span class="wcsatt-options-prompt-action"><?php esc_html_e( 'Subscribe & Save — 10% Off', '@text-domain' ); ?></span>
					<?php echo $subscription_price ?>
				</label>
			</li>
		</ul>
		<?php

		self::$_counter += 2;
		return ob_get_clean();
	}

	public static function update_dropdown_option_price_html_args( $args ) {
		$args['hide_price']     = true;
		$args['append_price']   = false;
		$args['allow_discount'] = false;

		return $args;
	}

	public function add_shipping_text() {
		?>
		<div class="funnel-picker__shipping-text"><?php echo $this->args['shipping_content']; ?></div>
		<?php
	}

	public function update_add_to_cart_text() {
		return $this->args['onetime_button_text'];
	}

	public function update_subscription_add_to_cart_text() {
		return $this->args['subscription_button_text'];
	}

	public function load_local_template( $template, $template_name ) {
		$templates = [
			'single-product/add-to-cart/variable.php'                     => 'variable.php',
			'single-product/add-to-cart/variation-add-to-cart-button.php' => 'variation-add-to-cart-button.php',
			'single-product/product-subscription-options.php'             => 'product-subscription-options.php',
		];

		if ( ! isset( $templates[ $template_name ] ) ) {
			return $template;
		}

		$local_template = __DIR__ . '/templates/' . $templates[ $template_name ];
		if ( ! file_exists( $local_template ) ) {
			return $template;
		}

		return $local_template ;
	}

	public function render() {
		if ( empty( $this->product_id ) ) {
			echo '<div class="woocommerce-error">Product ID is missing</div>';
			return;
		}

		$args = array(
			'posts_per_page'      => 1,
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
			'p'                   => absint( $this->product_id ),
		);

		$single_product = new \WP_Query( $args );

		if ( empty( $single_product->post  ) ) {
			echo '<div class="woocommerce-info">No product found with id <b>'.$args['p'].'</b></div>';
			return;
		}

		$first_product = wc_get_product( $single_product->post );

		if ( ! $first_product->is_type( 'variable' ) ) {
			echo '<div class="woocommerce-info">Only variable product is allowed</div>';
			return;
		}

		$this->maybe_update_hooks();

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
			$this->render_single_product();
		}

		// Restore $previous_wp_query and reset post data.
		// @codingStandardsIgnoreStart
		$wp_query = $previous_wp_query;
		// @codingStandardsIgnoreEnd
		wp_reset_postdata();

		$this->maybe_restore_hooks();
	}

	protected function render_single_product() {
		global $product;

		do_action( 'woocommerce_before_single_product' );

		$attribute_key = sanitize_key( $this->args['package_key'] );
		?>
		<div data-attribute_key="<?php echo esc_attr( $attribute_key  ); ?>" id="product-<?php the_ID(); ?>" <?php wc_product_class( 'funnel-picker', $product ); ?>>
			<?php
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
					'package_key'          => 'attribute_' . $attribute_key,
					'selected_package'     => $this->args['selected_package'],
					'condition_text'       => $this->args['condition_text'],
				)
			);
			?>
		</div>
		<?php
	}
}
