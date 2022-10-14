<?php

namespace AudioteriaWP\Pages;

use AudioteriaWP\Data\AbstractProducts;

class CheckoutPage {

  public function __construct() {
  
    add_filter ('woocommerce_checkout_cart_item_quantity', [$this, 'remove_quantity_text'], 10, 2 );

  //   remove_action( 'woocommerce_before_checkout_form',  'woocommerce_checkout_coupon_form' );

   remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

    //   add_action( 'audioteria_woocommerce_custom_checkout_field', 'woocommerce_checkout_coupon_form', 20);

   add_filter( 'woocommerce_checkout_fields' , [$this, 'audioteria_override_checkout_fields'], 20 );

   add_action( 'audioteria_woocommerce_custom_checkout_field', [$this, 'audioteria_add_total_and_voucher'], 30);

   add_action( 'audioteria_woocommerce_custom_checkout_field', 'woocommerce_checkout_payment', 40 );

    add_filter( 'woocommerce_order_button_html', [$this, 'audioteria_complete_order_button_html']);
    
    add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );
    
    add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
  }
  
  public function enqueue_scripts() {
    
    if(is_checkout()) {
        wp_enqueue_script('audioteria-wp-checkout', get_template_directory_uri() . '/js/checkout.js', array(), AUDIOTERIA_WP_THEME_VERSION, true);
    }
  }

  public function remove_quantity_text( $cart_item, $cart_item_key ) {
    $product_quantity= '';
    return $product_quantity;
  }

  public function audioteria_override_checkout_fields( $fields ) {

    unset($fields['billing']['order_comments'], $fields['billing']['billing_company'], $fields['billing']['billing_city'], $fields['billing']['billing_country'], $fields['billing']['billing_address_2'], $fields['billing']['billing_state'], $fields['billing']['billing_email'], $fields['billing']['billing_phone'], $fields['order']['order_comments']);

    $fields['billing']['billing_first_name'] =  [
      'type'        => 'text',
      'label'       => '',
      'placeholder' => __('First Name', 'audioteria-wp'),
      'class'       => ['billing-control'],
      'priority'    => 5,
      'clear'       => true
    ];

    $fields['billing']['billing_last_name'] =  [
      'type'        => 'text',
      'label'       => '',
      'placeholder' => __('Last Name', 'audioteria-wp'),
      'class'       => ['billing-control'],
      'priority'    => 10,
      'clear'       => true
    ];

    $fields['billing']['billing_email'] = [
      'type'        => 'email',
      'label'       => '',
      'placeholder' => __('Email', 'audioteria-wp'),
      'class'       => ['billing-control'],
      'priority'    => 15,
      'clear'       => true

    ];

    $fields['billing']['billing_postcode'] =  [
      'type'        => 'text',
      'label'       => '',
      'placeholder' => __('Post Code', 'audioteria-wp'),
      'class'       => ['billing-control'],
      'priority'    => 20,
      'clear'       => true
    ];
  
    $fields['billing']['billing_address_1'] =  [
      'type'        => 'textarea',
      'label'       => '',
      'placeholder' => __('Address', 'audioteria-wp'),
      'priority'    => 25,
      'class'       => ['billing-control'],
      'id'          => 'address',
      'clear'       => true
    ];

    return $fields;
  }

  public function audioteria_add_total_and_voucher(){
    ob_start(); ?>
    <div class="voucher">
      <div class="checkout_coupon woocommerce-form-coupon" method="post" style="display:block !important;">

        <p class="voucher-head"><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'audioteria-wp' ); ?></p>
        <p><?php esc_html_e( 'Add a promo code', 'audioteria-wp' ); ?></p>
        <input type="text" name="coupon_code" id="abstract_coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'audioteria-wp' ); ?>" id="coupon_code" value="" />

        <button type="button" class="button voucher-button pay-now" name="abstract_apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'audioteria-wp' ); ?>"><?php esc_html_e( 'Apply coupon', 'audioteria-wp' ); ?></button>

        <div class="clear"></div>
      </div>
    </div>
      <div class="checkout">
				<p class="checkout-head"><?php esc_html_e( 'Total', 'audioteria-wp' ); ?></p>
        <div class="sub-total cart-subtotal">
          <span><?php esc_html_e( 'Subtotal', 'audioteria-wp' ); ?></span>
          <span><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>
      

        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
          <div class="sub-total cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
            <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
            <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
          </div>
        <?php endforeach; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
          <div class="sub-total fee">
            <span><?php echo esc_html( $fee->name ); ?></span>
            <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
          </div>
        <?php endforeach; ?>

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
          <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
            <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
              <div class="sub-total tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                <span><?php echo esc_html( $tax->label ); ?></span>
                <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
            <div class="sub-total tax-total">
              <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
              <span><?php wc_cart_totals_taxes_total_html(); ?></span>
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

        <div class="sub-total order-total">
          <span><?php esc_html_e( 'Total', 'audioteria-wp' ); ?></span>
          <span><?php wc_cart_totals_order_total_html(); ?></span>
        </div>
        <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
    
      <?php echo ob_get_clean();
  }

  public function audioteria_complete_order_button_html( $button ) {

      // The text of the button
      $order_button_text = __('Pay Now', 'woocommerce');

      $button = '<button type="submit" class="button alt pay-now" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">'. esc_html( $order_button_text ) . '</button>';

      return $button;
  }

  /**
   * @return CheckoutPage
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