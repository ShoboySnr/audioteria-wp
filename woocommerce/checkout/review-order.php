<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="bag-items shop_table woocommerce-checkout-review-order-table">
    <?php
        do_action( 'woocommerce_review_order_before_cart_contents' );
    
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
    
          if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
    
              $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
              
              ?>
              <article class="bag-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                  <?php
                      $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image_id(), $cart_item, $cart_item_key );
                      echo $thumbnail;
                  ?>
                <article class="content">
                  <div>
                    <h3 data-title="<?php esc_attr_e( 'Product', 'audioteria-wp' ); ?>">
                        <?php
                            if ( ! $product_permalink ) {
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                            } else {
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '%s', $_product->get_name() ), $cart_item, $cart_item_key ) );
                            }
                        ?>
                    </h3>
                    <span class="price">
									<?php
                      echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                  ?>
								</span>
                  </div>
                  <p class="description">
                      <?php echo apply_filters( 'audioteria_cart_item_description', $_product, $cart_item, $cart_item_key) ?></p>
                  <p class="writer"><?= __('Written By: ', 'audioteria-wp') ?>
                      <?php echo apply_filters( 'audioteria_cart_item_writer', $_product, $cart_item, $cart_item_key) ?>
                  </p>
                  <div>
								<span class="price">
									<?php
                      echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                  ?>
								</span>
                  </div>
                </article>
              </article>
              <?php
          }
          
        }
    ?>

</div>
