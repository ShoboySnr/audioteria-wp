<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
    return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>
    
    <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

  <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
      <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
      
      <?php
          do_action( 'woocommerce_before_add_to_cart_quantity' );
          
          woocommerce_quantity_input(
              array(
                  'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                  'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                  'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
              )
          );
          
          do_action( 'woocommerce_after_add_to_cart_quantity' );
      ?>

    <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt bag">
      <svg width="25" height="26" viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M4.14294 10.75C4.18484 10.228 4.42185 9.74082 4.80677 9.38563C5.19169 9.03044 5.69627 8.83327 6.22002 8.83337H18.7804C19.3042 8.83327 19.8088 9.03044 20.1937 9.38563C20.5786 9.74082 20.8156 10.228 20.8575 10.75L21.694 21.1667C21.717 21.4534 21.6804 21.7417 21.5865 22.0135C21.4926 22.2853 21.3435 22.5348 21.1485 22.7461C20.9534 22.9575 20.7168 23.1262 20.4534 23.2416C20.19 23.357 19.9055 23.4166 19.6179 23.4167H5.38252C5.09494 23.4166 4.81049 23.357 4.54709 23.2416C4.28368 23.1262 4.04701 22.9575 3.852 22.7461C3.65698 22.5348 3.50784 22.2853 3.41395 22.0135C3.32007 21.7417 3.28348 21.4534 3.30648 21.1667L4.14294 10.75V10.75Z"
            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path
            d="M16.6668 11.9584V6.75004C16.6668 5.64497 16.2278 4.58516 15.4464 3.80376C14.665 3.02236 13.6052 2.58337 12.5002 2.58337C11.3951 2.58337 10.3353 3.02236 9.55388 3.80376C8.77248 4.58516 8.3335 5.64497 8.3335 6.75004V11.9584"
            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
        <span>
          <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
        </span>
    </button>
      
      <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
  </form>
    
    <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
