<?php

namespace AudioteriaWP\Pages;

use AudioteriaWP\Data\AbstractProducts;

class CartPage
{

  public function __construct()
  {

    add_filter( 'woocommerce_cart_item_thumbnail', [$this, 'audioteria_cart_product_image'], 10, 3 );

    remove_action( 'woocommerce_cart_collaterals',  'woocommerce_cross_sell_display', 5 );

    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
    
    remove_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display',  10);

    add_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 15);


    add_filter( 'audioteria_cart_item_description',[$this, 'audioteria_cart_item_description'], 10, 3 );

    add_filter( 'audioteria_cart_item_writer',[$this, 'audioteria_cart_item_writer'], 10, 3 );

    add_action('audioteria_add_related_products_to_empty_cart', [$this, 'audioteria_empty_cart_products']);
    
    add_filter( 'woocommerce_add_to_cart_fragments', [$this, 'audioteria_refresh_mini_cart_count']);

    add_filter( 'woocommerce_add_to_cart_fragments', [$this, 'audioteria_refresh_mini_cart_content']); 
  }

  public function audioteria_cart_product_image($cart_item_image, $cart_item, $cart_item_key) {
    $class = 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail image';
    $src = wp_get_attachment_url( $cart_item_image );
    $title = get_the_title($cart_item_image);
    // Construct your img tag.
    $cart_item_image = '<img';
    $cart_item_image .= ' src="' . $src . '"';
    $cart_item_image .= ' class="' . $class . '"';
    $cart_item_image .= ' alt="' . $title . '"';
    $cart_item_image .= ' />';

    // Output.
    return $cart_item_image;
  }

  public function audioteria_cart_item_description($product, $cart_item, $cart_item_key){
    $product_description = '';
    $products_data = AbstractProducts::get_instance()->product_returned_schema($product, AbstractProducts::get_instance()->taxonomy, AbstractProducts::get_instance()->genre, []);

    if(!empty($products_data)){
      $product_description = $products_data['short_description'];

      if (!empty($product_description)){
        $product_description = limit_character($products_data['short_description'], 100);
      }

    }

    return $product_description;
  }

  public function audioteria_cart_item_writer($product, $cart_item, $cart_item_key){

    $product_writer = '';
    $products_data = AbstractProducts::get_instance()->product_returned_schema($product, AbstractProducts::get_instance()->taxonomy, AbstractProducts::get_instance()->genre, []);

    if(!empty($products_data)){
      $product_writer = $products_data['main_written_by'];

      if(!empty($product_writer)) {
        get_custom_product_meta_html($product_writer, 'Written by: ', 'writer', 'order-text');
      }
    }
    
    return '';
  }

  public function audioteria_empty_cart_products(){
    
    $related_products = AbstractProducts::get_instance()->get_all_products(4);
  
    if ( $related_products['data'] ) {
      ob_start();
    ?>
      <section class="book-card-section">
        <h4><?= __('You might also be interested in these titles', 'audioteria-wp'); ?></h4>
        <div class="book-card-wrapper">
  
          <?php foreach ( $related_products['data'] as $related_product ) :
              $related_product_id = $related_product['id'];
              $post_object = get_post( $related_product_id );
  
              setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
  
              wc_get_template_part( 'content', 'product' );
  
          endforeach; ?>
        </div>
      </section>
    <?php ob_get_clean();
    }
  }

  public function audioteria_refresh_mini_cart_count($fragments){
    ob_start();
    ?>
    <span class="dropdown-back" id="mini-cart-dropdown">
      <svg class="nav-icons" width="21" height="24" viewBox="0 0 21 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.1427 10.1276C2.1846 9.60553 2.42161 9.11838 2.80652 8.76319C3.19144 8.408 3.69602 8.21083 4.21978 8.21094H16.7802C17.304 8.21083 17.8085 8.408 18.1935 8.76319C18.5784 9.11838 18.8154 9.60553 18.8573 10.1276L19.6937 20.5443C19.7167 20.8309 19.6802 21.1192 19.5863 21.3911C19.4924 21.6629 19.3432 21.9123 19.1482 22.1237C18.9532 22.3351 18.7165 22.5037 18.4531 22.6192C18.1897 22.7346 17.9053 22.7942 17.6177 22.7943H3.38228C3.0947 22.7942 2.81025 22.7346 2.54684 22.6192C2.28343 22.5037 2.04677 22.3351 1.85175 22.1237C1.65674 21.9123 1.50759 21.6629 1.41371 21.3911C1.31982 21.1192 1.28323 20.8309 1.30624 20.5443L2.1427 10.1276V10.1276Z" stroke="white"  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M14.6667 11.3359V6.1276C14.6667 5.02254 14.2277 3.96273 13.4463 3.18133C12.6649 2.39992 11.6051 1.96094 10.5 1.96094C9.39497 1.96094 8.33516 2.39992 7.55376 3.18133C6.77236 3.96273 6.33337 5.02254 6.33337 6.1276V11.3359" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
      <div class="basket-item-count">
        <span class="cart-items-count count">
          <?php echo WC()->cart->get_cart_contents_count(); ?>
        </span>
      </div>
    </span>

    <?php $fragments['#mini-cart-dropdown'] = ob_get_clean();

    return $fragments;
  }
  
  public function audioteria_refresh_mini_cart_content($fragments) {

    ob_start();
    ?>
	  <div class="audioteria-mini-cart" id="audioteria-mini-cart-dropdown">

        <button id="close-mini-cart">&times;</button>
        <?php woocommerce_mini_cart(); ?>
    </div>

    <?php $fragments['div.audioteria-mini-cart'] = ob_get_clean();

    return $fragments;

  } 
  /**
   * @return CartPage
   */
  public static function get_instance()
  {
    static $instance = null;

    if (is_null($instance)) {
      $instance = new self();
    }

    return $instance;
  }
}
