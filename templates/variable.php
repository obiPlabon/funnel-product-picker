<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_key = $package_key;
$selected_value = $selected_package;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

$first_variation = current( $available_variations );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>
	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock v"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>
		<p class="funnel-picker__top-price">
			<span class="funnel-picker__top-price-label">Price:</span>
			<span class="funnel-picker__top-price-base"><?php echo wc_price( $first_variation['display_price'] ); ?></span>
			<span class="funnel-picker__top-price-bottle">(<?php echo wc_price( $first_variation['display_price'] ); ?> / bottle)</span>
		</p>
		<div class="funnel-picker__options-section">
			<h3 class="funnel-picker__options-heading"><?php esc_html_e( 'Select Quantity:', '@text-domain' ); ?></h3>
			<ul class="funnel-picker__options">
				<?php
				$_counter = 1;
				foreach ( $available_variations as $_variation ) :
					if ( empty( $_variation['image'] ) || ! is_array( $_variation['image'] ) ) {
						continue;
					}

					if ( ! isset( $_variation['attributes'][ $attribute_key ] ) ) {
						continue;
					}

					$_image = $_variation['image'];
					$_value = $_variation['attributes'][ $attribute_key ];
					$_value_key = sanitize_key( 'funnel-option-' . $_value );

					$_price = $_variation['display_price'] / $_counter;
					?>
					<li class="funnel-picker__options-item">
						<input data-vid="<?php echo $_variation['variation_id']; ?>" <?php checked( $_value, $selected_value ); ?> class="funnel-picker__options-input" id="<?php echo $_value_key; ?>" type="radio" name="funnel-picker__options" value="<?php echo esc_attr( $_value ); ?>">
						<label class="funnel-picker__options-label" for="<?php echo $_value_key; ?>">
							<span class="funnel-picker__options-img"><img src="<?php echo esc_url( $_image['thumb_src'] ); ?>" srcset="<?php echo esc_attr( $_image['srcset'] ); ?>" sizes="<?php echo esc_attr( $_image['sizes'] ); ?>" alt="<?php echo esc_attr( $_value ); ?>"></span>
							<h4 class="funnel-picker__options-title"><?php echo esc_html( $_value ); ?></h4>
						</label>
						<div class="funnel-picker__options-price"><?php echo wc_price( $_price ); ?> / bottle</div>
					</li>
				<?php
				$_counter += 2;
				endforeach;
				?>
			</ul>
			<p class="funnel-picker__options-tagline"><?php echo $condition_text; ?></p>
		</div>
		<table style="display:none" class="variations" cellspacing="0">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></td>
						<td class="value">
							<?php
								wc_dropdown_variation_attribute_options(
									array(
										'options'   => $options,
										'attribute' => $attribute_name,
										'product'   => $product,
										'selected'  => $selected_value
									)
								);
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="single_variation_wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_variation.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
				 *
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * Hook: woocommerce_after_single_variation.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
