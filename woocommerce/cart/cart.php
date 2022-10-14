<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;
$exclude_ids = array();
$shop_url = esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) );
?>
<div class="wrapper">
	<?php do_action( 'woocommerce_before_cart' ); ?>

	<section class="shopping">
		<form class="woocommerce-cart-form shopping-bag" action="<?php echo $shop_url; ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>
				<div class="header">
					<?php
						$cart_items = WC()->cart->get_cart();
						$no_of_items = count($cart_items);
					?>
					<p class="title"><?= __('My Shopping Bag (', 'audioteria-wp') ?><?= $no_of_items ?><?= __(' Items)', 'audioteria-wp') ?></p>
					
					<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" title="Continue shopping"><?= __('Continue shopping', 'audioteria-wp') ?></a>
				</div>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>
				<hr>
				<div class="bag-items">
					<?php
					foreach ( $cart_items as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$exclude_ids[] = $cart_item['product_id'];

							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>

							<article class="bag-item">
								<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image_id(), $cart_item, $cart_item_key );
									printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
								?>
								<article class="content">
									<div>
										<a href="<?= $product_permalink ?>"><h3 data-title="<?php esc_attr_e( 'Product', 'audioteria-wp' ); ?>">
											<?php
												if ( ! $product_permalink ) {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
												} else {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '%s', $_product->get_name() ), $cart_item, $cart_item_key ) );
												}
											?>
                      </h3></a>
										<span class="price">
											<?php
												echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
											?>
										</span>
									</div>
									<p class="description">
										<?php echo apply_filters( 'audioteria_cart_item_description', $_product, $cart_item, $cart_item_key) ?></p>
									<?php $product_writer = apply_filters( 'audioteria_cart_item_writer', $_product, $cart_item, $cart_item_key);
									if($product_writer) : ?>
										<p class="writer"><?= __('Written By: ', 'audioteria-wp') ?>
											<?= $product_writer ?>
										</p>
									<?php endif; ?>
									<div >
									<span class="price">
										<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
										?>
									</span>
									<?= apply_filters(
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s%s</a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'audioteria-wp' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() ),
												file_get_contents(AUDIOTERIA_ASSETS_ICONS_DIR."/remove-icon.svg"),
												esc_html__( ' Remove this item', 'audioteria-wp' )
											),
											$cart_item_key
										);
									?>
									</div>
								</article>
							</article>

							<?php
						}
					}
					?>

					<?php do_action( 'woocommerce_cart_contents' ); ?>
				</div>
		</form>

		<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

		<section class="checkout">
			<?php
				/**
				 * Cart collaterals hook.
				 *
				 * @hooked woocommerce_cross_sell_display
				 * @hooked woocommerce_cart_totals - 10
				 */
				do_action( 'woocommerce_cart_collaterals' );

			?>

			<button type="button" class="button shopping-button" onclick="window.location.href='<?= $shop_url; ?>'"><?php esc_html_e( 'Continue Shopping', 'audioteria-wp' ); ?></button>
 
			<?php do_action( 'woocommerce_cart_actions' ); ?>

			<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			
			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</section>
	</section>

	<?php

	$relateproduct_id = $exclude_ids[0];
	$related_products = wc_get_related_products( $relateproduct_id, 4, $exclude_ids);

	if ( $related_products ) : ?>
	
		<section class="book-card-section">
			<h4><?= __('You might also be interested in these titles', 'audioteria-wp'); ?></h4>
			<div class="book-card-wrapper">

				<?php foreach ( $related_products as $related_product ) :

						$post_object = get_post( $related_product );

						setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

						wc_get_template_part( 'content', 'product' );

				endforeach;
				
				do_action( 'woocommerce_after_cart' ); ?>
			</div>
		</section>
	<?php endif; ?>
</div>

