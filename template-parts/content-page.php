<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Audioteria
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(!is_page( ['cart', 'about-us', 'about'] ) && !is_cart() && !is_page( 'checkout' ) && !is_checkout() && !is_product() && !is_tax() && !is_product_category() && !is_home() && !is_front_page()) {
		woocommerce_breadcrumb();
	} ?>

	<div class="entry-content">
		<?php
		the_content();
		?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
