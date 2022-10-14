<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account password-container" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<h5 class="password-title"><?= __('Account Details', 'audioteria-wp'); ?></h5>
  <p class="password-text"><?= __('Edit any of your details below to make sure your account is up to date.', 'audioteria-wp'); ?></p>

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

  <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <input type="email" class="woocommerce-Input woocommerce-Input--email input-text password-input" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" readonly />
  </p>
	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first ">
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text password-input" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text password-input" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>
  <input type="hidden" name="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="woocommerce-Button password-button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Update', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
