<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 6.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer username */ ?>
    <p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $user_login ) ); ?></p>
    <br><br>
<?php /**/ ?>
    <p><?= __( 'Your registration is confirmed. Get access to unlimited audiobooks of your choice on Audioteria.', 'woocommerce' ); ?></p>
    <br><br><br>
<?php /**/ ?>
    <p>
        <a href="<?= esc_url( wc_get_page_permalink( 'myaccount' ) ) ?>">Log in to your new account </a>

    </p>
    <br><br>
<?php /*Have any question*/ ?>
    <p><?= __( 'Have any question?', 'audioteria-wp' ) ?></p>
    <br>

    <p>Check out <a href="<?= esc_url( get_permalink( get_page_by_path( 'contact-us' ) ) ) ?>">
            knowledge base</a> to get a quick answer</p>

<?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated && $set_password_url ) : ?>
	<?php // If the password has not been set by the user during the sign up process, send them a link to set a new password ?>
    <p>
        <a href="<?php echo esc_attr( $set_password_url ); ?>"><?php printf( esc_html__( 'Click here to set your new password.', 'woocommerce' ) ); ?></a>
    </p>
<?php endif; ?>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
