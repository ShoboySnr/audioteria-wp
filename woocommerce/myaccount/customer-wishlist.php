<?php
/**
 * My Account Wishlist
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_edit_account_form' );
?>
            
            <div class="account-content">
              <h5 class="account-content-title"><?= __('My Wishlist', 'audioteria-wp'); ?></h5>
                <?php do_action( 'woocommerce_customer_account_wishlist' ); ?>
          </div>
      </div>
  </div>
<?php
    do_action('woocommerce_after_edit_account_form');
