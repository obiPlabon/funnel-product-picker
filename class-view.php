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

	public function __construct( $product_id ) {
		$this->product_id = $product_id;

		self::$attribute_key = 'attribute_package';
		self::$selected_value = 'Buy 1';
	}

	protected function maybe_update_hooks() {
		// Change form action to avoid redirect.
		add_filter( 'woocommerce_add_to_cart_form_action', '__return_empty_string' );

		add_filter( 'wc_get_template', [ $this, 'load_local_variable_template' ], 10, 2 );
	}

	public function load_local_variable_template( $template, $template_name ) {
		if ( $template_name !== 'single-product/add-to-cart/variable.php' ) {
			return $template;
		}

		$_template = __DIR__ . '/templates/variable.php';

		if ( is_readable( $_template ) ) {
			$template = $_template;
		}

		return $template;
	}

	protected function maybe_restore_hooks() {
		remove_filter( 'woocommerce_add_to_cart_form_action', '__return_empty_string' );

		remove_filter( 'wc_get_template', [ $this, 'load_local_variable_template' ], 10, 2 );
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
			<?php woocommerce_variable_add_to_cart(); ?>
		</div>
		<?php
	}
}
