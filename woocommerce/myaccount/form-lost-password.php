<?php

/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.2
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>
    <section class="reset-password">
        <h2><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Reset your password', 'audioteria-wp' ) ); ?>
        </h2><?php // @codingStandardsIgnoreLine
		?>
        <p><?= __( 'Enter your new password for your Audioteria account', 'audioteria-wp' ) ?></p>
        <form method="post" class="woocommerce-ResetPassword lost_reset_password">

            <div class="password-wrapper">


                <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login"
                       id="user_login" autocomplete="username"/>
            </div>
            <div class="clear"></div>

			<?php do_action( 'woocommerce_lostpassword_form' ); ?>

            <p class="woocommerce-form-row form-row">
                <input type="hidden" name="wc_reset_password" value="true"/>
                <button type="submit" class="woocommerce-Button button woo-reset-password"
                        value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
            </p>

			<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

        </form>
        <p class="link-sign-up"><?= __( 'Don\'t have an account?', 'audioteria-wp' ) ?>
            <a title="sign in"
               href="<?= get_permalink( get_page_by_path( 'my-account' ) ) ?>">
				<?= __( 'Sign In', 'audioteria-wp' ) ?> </a>
        </p>
    </section>
<?php
do_action( 'woocommerce_after_lost_password_form' );