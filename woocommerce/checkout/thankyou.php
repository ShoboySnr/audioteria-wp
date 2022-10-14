<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="thank-you">
<div class="wrapper">
    <div class="woocommerce-order">
        
        <?php
            if ( $order ) :
            
            do_action( 'woocommerce_before_thankyou', $order->get_id() );
        ?>
        
        <?php if ( $order->has_status( 'failed' ) ) : ?>
        
        <div class="order-received">
            <?php include(AUDIOTERIA_ASSETS_ICONS_DIR. '/failed-icon.svg'); ?>
            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
                <?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'audioteria-wp' ); ?>
            </p>
            
            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay pay-now"><?php esc_html_e( 'Pay', 'audioteria-wp' ); ?></a>
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay pay-now"><?php esc_html_e( 'My account', 'audioteria-wp' ); ?></a>
                <?php endif; ?>
            </p>
        </div>
        <div>
            <?php else : ?>
            <div class="order-received">
                <?php include(AUDIOTERIA_ASSETS_ICONS_DIR. '/success-icon.svg'); ?>
                <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                    <?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'audioteria-wp' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </p>
                <p class="order-received-number">
                    <?php esc_html_e( 'Order number: ', 'audioteria-wp' );
                        echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                </p>
            </div>
            
            <div class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
                <div class="heading">
                    <h5><?= __('Payment Details', 'audioteria-wp') ?></h5>
                </div>
                <div class="woocommerce-order-overview__date date">
                    <span><?php esc_html_e( 'Date:', 'audioteria-wp' ); ?></span>
                    <span><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>
                </div>
                
                <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                    <div class="woocommerce-order-overview__email email">
                        <span><?php esc_html_e( 'Email:', 'audioteria-wp' ); ?></span>
                        <span><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="woocommerce-order-overview__total total">
                    <span><?php esc_html_e( 'Total:', 'audioteria-wp' ); ?></span>
                    <span><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>
                </div>
                
                <?php if ( $order->get_payment_method_title() ) : ?>
                    <div class="woocommerce-order-overview__payment-method method">
                        <span><?php esc_html_e( 'Payment method:', 'audioteria-wp' ); ?></span>
                        <span><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php endif; ?>
                
                <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
                <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
            </div>
            <?php else : ?>
                <div class="order-received">
                    <?php include(AUDIOTERIA_ASSETS_ICONS_DIR. '/check-icon.svg'); ?>
                    <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                        <?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'audioteria-wp' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </p>
                </div>
            <?php endif; ?>
        
        </div>
    </div>
</section>
