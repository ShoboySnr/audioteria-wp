<?php
/**
 * Single Product Share
 *
 * Sharing plugins can hook into here or you can add your own code directly.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/share.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */
global $product;

$url = get_permalink();
$title = $product->get_title();
$content = substr(get_the_title(), 0, 100);
$twitter_link = "https://twitter.com/share?text=$content&url=$url";
$facebook_link = "https://www.facebook.com/sharer/sharer.php?u=$url&t=$title";
$linkedin_link = "https://www.linkedin.com/shareArticle?mini=true&url=$url";
$mail_content = get_the_excerpt();
$mail_link = "?subject=$title&body=$mail_content";

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// do_action( 'woocommerce_share' ); // Sharing plugins can hook into here.?>
	<button id="share-toggle">
		<span><?= __('Share', 'audioteria-wp') ?></span>
		<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path
				d="M7.80908 5.25004H11.7061C11.9199 5.2501 12.1256 5.33131 12.2818 5.47726C12.4381 5.6232 12.533 5.823 12.5476 6.03628C12.5622 6.24956 12.4953 6.46042 12.3603 6.62625C12.2254 6.79208 12.0326 6.90052 11.8208 6.92966L11.7061 6.93754H7.80908C7.16787 6.93744 6.55052 7.18069 6.08172 7.61817C5.61293 8.05564 5.32763 8.65472 5.28346 9.29441L5.27783 9.46879V20.1563C5.27804 20.7975 5.52158 21.4147 5.95926 21.8833C6.39694 22.3519 6.99614 22.6369 7.63583 22.6808L7.80908 22.6875H18.4966C19.138 22.6876 19.7555 22.4442 20.2243 22.0065C20.6931 21.5688 20.9783 20.9694 21.0222 20.3295L21.0278 20.1563V19.596C21.0256 19.3807 21.1058 19.1727 21.252 19.0147C21.3982 18.8566 21.5993 18.7603 21.8141 18.7457C22.0289 18.731 22.2412 18.799 22.4075 18.9358C22.5738 19.0725 22.6815 19.2677 22.7086 19.4813L22.7153 19.596V20.1563C22.7151 21.2361 22.3009 22.2747 21.5579 23.0583C20.815 23.8418 19.7999 24.3107 18.7216 24.3683L18.4966 24.375H7.80908C6.7289 24.3751 5.68984 23.9609 4.906 23.2176C4.12217 22.4744 3.65328 21.4588 3.59596 20.3802L3.59033 20.1552V9.46766C3.59052 8.38786 4.00475 7.34924 4.74772 6.56567C5.49068 5.7821 6.50582 5.31325 7.58408 5.25566L7.80908 5.25004H11.7061H7.80908ZM16.5312 8.08504V4.96879C16.5311 4.81359 16.5738 4.66137 16.6546 4.52888C16.7355 4.39638 16.8513 4.28875 16.9893 4.21781C17.1273 4.14687 17.2823 4.11538 17.437 4.1268C17.5918 4.13822 17.7405 4.19211 17.8666 4.28254L17.9588 4.35904L24.7021 10.8278C25.0171 11.1304 25.0463 11.6153 24.7887 11.9505L24.7021 12.0462L17.9588 18.5172C17.8469 18.6247 17.7074 18.6993 17.5557 18.7326C17.4041 18.766 17.2462 18.7569 17.0994 18.7063C16.9526 18.6557 16.8227 18.5656 16.7238 18.4459C16.6249 18.3262 16.561 18.1815 16.5391 18.0278L16.5312 17.9074V14.8418L16.1442 14.8755C13.4442 15.1568 10.8567 16.3729 8.36596 18.543C7.78096 19.0527 6.87758 18.5712 6.97433 17.8028C7.72133 11.8178 10.8511 8.52041 16.1937 8.10641L16.5312 8.08391V4.96879V8.08504ZM18.2187 6.94541V8.90629C18.2187 9.13006 18.1298 9.34467 17.9716 9.50291C17.8133 9.66114 17.5987 9.75004 17.375 9.75004C13.0167 9.75004 10.3167 11.6355 9.14896 15.5517L9.06008 15.8644L9.45608 15.5978C11.975 13.9542 14.6165 13.125 17.3761 13.125C17.5801 13.1251 17.7773 13.1991 17.931 13.3333C18.0847 13.4676 18.1845 13.653 18.212 13.8552L18.2198 13.9688V15.9297L22.8987 11.4375L18.2187 6.94654V6.94541Z"
				fill="#CB5715" />
		</svg>
	</button>
	<div class="social-shares" id="share-box">
		<button class="close-sharebox" id="close-share">&times;</button>
		<div class="social-shares-box">
				<a href="<?= esc_url($facebook_link) ?>"
						target="_blank">
						<?php include(AUDIOTERIA_ASSETS_DIR . '/icons/facebook-black.svg') ?>
				</a>
				<a href="<?= esc_url($twitter_link) ?>"
						target="_blank">
						<?php include(AUDIOTERIA_ASSETS_DIR .  '/icons/twitter-black.svg') ?>
				</a>
				<a href="<?= esc_url($linkedin_link) ?> "
						target="_blank">
						<?php include(AUDIOTERIA_ASSETS_DIR . '/icons/linkedin-black.svg') ?>
				</a>
				<a href="<?= esc_url($mail_link) ?>"
						target="_blank">
						<?php include(AUDIOTERIA_ASSETS_DIR .  '/icons/email-black.svg') ?>
				</a>
		</div>
	</div>
</div>
<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
