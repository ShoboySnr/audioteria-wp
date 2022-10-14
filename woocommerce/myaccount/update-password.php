<?php
/**
 * Customer Update Password
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

  <h5 class="password-title"><?= __('Change Password', 'audioteria-wp'); ?></h5>
  <p class="password-text"><?= __('Update your password so your account stays secure', 'audioteria-wp'); ?></p>
    
    <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

  <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <input type="password" class="woocommerce-Input woocommerce-Input--email input-text password-input" name="password_current" id="password_current"  autocomplete="off" placeholder="<?= __('Current password', 'audioteria-wp') ?>" />
  </p>
  <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first ">
    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text password-input" name="password_1" id="password_1" autocomplete="off" placeholder="<?= __('New password', 'audioteria-wp') ?>" />
  </p>
  <p class="password-validation">Must be 8 or more characters and contains at least 1 number</p>
  <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text password-input" name="password_2" id="password_2" autocomplete="off" placeholder="<?= __('Confirm Password', 'audioteria-wp') ?>" />
  </p>
  <input type="hidden" name="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
  <input type="hidden" name="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
  <input type="hidden" name="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
  <input type="hidden" name="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
  <input type="hidden" name="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />

  <p>
      <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
    <button type="submit" class="woocommerce-Button password-button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Update', 'woocommerce' ); ?></button>
    <input type="hidden" name="action" value="save_account_details" />
  </p>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>