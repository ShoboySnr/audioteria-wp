<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;



if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<div class="wrapper">
<?php
/*
 * @hooked wc_empty_cart_message - 10
 */
do_action( 'woocommerce_cart_is_empty' );

	?>
		<section class="shopping">
			<section class="shopping-bag">
				<div class="header">
					<p class="title"><?= __('My Shopping bag', 'audioteria-wp') ?></p>
					<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" title="Continue shopping"><?= __('Continue shopping', 'audioteria-wp') ?></a>
				</div>
				<hr>
				<div class="bag-items empty">
					<svg width="84" height="83" viewBox="0 0 84 83" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd"
							d="M54.9688 18.1562C54.9688 14.7167 53.6024 11.4181 51.1703 8.98596C48.7382 6.55385 45.4395 5.1875 42 5.1875C38.5605 5.1875 35.2618 6.55385 32.8297 8.98596C30.3976 11.4181 29.0312 14.7167 29.0312 18.1562V20.75H54.9688V18.1562ZM60.1562 18.1562V20.75H78.3125V72.625C78.3125 75.3766 77.2194 78.0155 75.2737 79.9612C73.328 81.9069 70.6891 83 67.9375 83H16.0625C13.3109 83 10.672 81.9069 8.72627 79.9612C6.78058 78.0155 5.6875 75.3766 5.6875 72.625V20.75H23.8438V18.1562C23.8438 13.3409 25.7566 8.7228 29.1616 5.31784C32.5666 1.91288 37.1847 0 42 0C46.8153 0 51.4334 1.91288 54.8384 5.31784C58.2434 8.7228 60.1562 13.3409 60.1562 18.1562ZM36.0551 42.2574C35.814 42.0162 35.5277 41.8249 35.2126 41.6944C34.8975 41.5639 34.5598 41.4967 34.2188 41.4967C33.8777 41.4967 33.54 41.5639 33.2249 41.6944C32.9098 41.8249 32.6235 42.0162 32.3824 42.2574C32.1412 42.4985 31.9499 42.7848 31.8194 43.0999C31.6889 43.415 31.6217 43.7527 31.6217 44.0938C31.6217 44.4348 31.6889 44.7725 31.8194 45.0876C31.9499 45.4027 32.1412 45.689 32.3824 45.9301L38.3324 51.875L32.3824 57.8199C31.8953 58.3069 31.6217 58.9675 31.6217 59.6562C31.6217 60.345 31.8953 61.0056 32.3824 61.4926C32.8694 61.9797 33.53 62.2533 34.2188 62.2533C34.9075 62.2533 35.5681 61.9797 36.0551 61.4926L42 55.5426L47.9449 61.4926C48.4319 61.9797 49.0925 62.2533 49.7812 62.2533C50.47 62.2533 51.1306 61.9797 51.6176 61.4926C52.1047 61.0056 52.3783 60.345 52.3783 59.6562C52.3783 58.9675 52.1047 58.3069 51.6176 57.8199L45.6676 51.875L51.6176 45.9301C52.1047 45.4431 52.3783 44.7825 52.3783 44.0938C52.3783 43.405 52.1047 42.7444 51.6176 42.2574C51.1306 41.7703 50.47 41.4967 49.7812 41.4967C49.0925 41.4967 48.4319 41.7703 47.9449 42.2574L42 48.2074L36.0551 42.2574Z"
							fill="#C4C4C4" />
					</svg>
					<p><?= __('Looks like your shopping bag is empty', 'audioteria-wp') ?></p>
				</div>
			</section>
		</section>
		<section class="book-card-section">
			<h4><?= __('You might also be interested in these titles', 'audioteria-wp') ?></h4>
			<div class="book-card-wrapper">
			<?php
				$related_products = \AudioteriaWP\Data\AbstractProducts::get_instance()->get_all_products(4);
  
				if ( $related_products['data'] ) {
					foreach ( $related_products['data'] as $related_product ) :

						$related_product_id = $related_product['id'];

						$post_object = get_post( $related_product_id );

						setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

						wc_get_template_part( 'content', 'product' );

					endforeach;
				} ?>

			</div>
		</section>
	</div>

<?php endif; ?>
