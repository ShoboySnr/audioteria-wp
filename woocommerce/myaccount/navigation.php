<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?> 
  <h2 class="account-header"><?= __('My Account', 'audioteria-wp'); ?></h2>
  <div class="account-container"> 
    <div class="account-links">
      <button class="account-menu">
        <?= __('Menu', 'audioteria-wp'); ?>
        <?php include(AUDIOTERIA_ASSETS_ICONS_DIR. '/acct-menu-icon.svg'); ?>
    </button>
    <div class="account-links-wrapper">
      <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
        <a class="account-link <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>" href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" title="<?php echo esc_html( $label ); ?>">
          <?php add_icon_according_to_label($endpoint); ?>
          <span> <?php echo esc_html( $label ); ?><span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>


<?php do_action( 'woocommerce_after_account_navigation' ); ?>
