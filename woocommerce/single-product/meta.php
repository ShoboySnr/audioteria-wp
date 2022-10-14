<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<p class="posted_in category">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'audioteria-wp' ) . ' ', '</p>' ); ?>

	<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<p class="tagged_as tag">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'audipteria-wp' ) . ' ', '</p>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
