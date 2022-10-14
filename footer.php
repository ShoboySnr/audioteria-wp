<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Audioteria
 */

?>


<?php if ( ! is_page_template( 'page-signup.php' ) && ! is_page_template( 'page-signin.php' ) && ! is_page( 'my-account' ) && ! is_page_template( 'page-contact-faq.php' ) ) { ?>

    <section class="download-now-wrapper">
        <div>
            <div class="download-now"
                 style="background-image: url('<?= get_theme_mod( 'audioteria-wp-download-bg', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/assets/download_now_sm_sample.png' ); ?>');">
                <div class="download-now-content">
                    <?php if( !empty(get_theme_mod( 'audioteria-wp-download-content-image'))){ ?>
                        <img src="<?= get_theme_mod( 'audioteria-wp-download-content-image' ); ?>">
                    <?php } ?>
                    <h3 class="title"><?= __( 'Download Now', 'audioteria-wp' ) ?></h3>
                    <p class="content">
						<?= get_theme_mod( 'audioteria-wp-download-content', 'Lorem ipsum dolor sit amet, consectetur, Lorem ipsum dolor sit amet, consectetur' ); ?>
                    </p>
                </div>
                <div class="buttons">
                    <a title="<?= __( 'Download from Our Google Play', 'audioteria-wp' ) ?>"
                       href="<?= get_theme_mod( 'audioteria-wp-google-play-link', 'https://play.google.com/' ); ?>"
                       target="_blank">
                        <img src="<?= get_theme_mod( 'audioteria-wp-google-play-link-img', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/assets/google_play.png' ); ?>"
                             alt="<?= __( 'Download from Google Play', 'audioteria-wp' ) ?>"/>
                    </a>
                    <a title="<?= __( 'Download from Our Apple Store', 'audioteria-wp' ) ?>"
                       href="<?= get_theme_mod( 'audioteria-wp-apple-store-link', 'https://www.apple.com/store' ); ?>">
                        <img src="<?= get_theme_mod( 'audioteria-wp-apple-store-img', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/assets/app_store.png' ); ?>"
                             alt="<?= __( 'Download from Apple Store', 'audioteria-wp' ) ?>"/>
                    </a>
                </div>
            </div>
        </div>
    </section>


<?php } ?>

<footer>
    <div class="footer">
        <div class="footer-contact">
            <h5 class="footer-heading"><?= __( 'Contact Us', 'audioteria-wp' ) ?>
            </h5>
            <div class="address-wrapper">
                <span class="footer-subtitle"><?= __( 'e-mail', 'audioteria_wp' ) ?></span>
                <p>
                    <a href="mailto: <?php get_theme_mod( 'audioteria-wp-contact-email', 'hello@audioteria.com' ); ?>"
                       target="_blank">
						<?= get_theme_mod( 'audioteria-wp-contact-email', 'hello@audioteria.com' ); ?>
                    </a>
                </p>

                <span class="footer-subtitle"><?= __( 'address', 'audioteria-wp' ) ?></span>
                <p><?= get_theme_mod( 'audioteria-wp-contact-address', 'Unit 2, Whitegates Berries Road Cookham, Berkshire SL6 9SD' ); ?>
                    <br><br>
					<?= get_theme_mod( 'audioteria-wp-company-registration', 'UK Company Reg 4352509' ) ?>
                </p>
            </div>
        </div>
        <div class="footer-links">
            <h5 class="footer-heading"><?= __( 'Quick Links', 'audioteria-wp' ) ?></h5>
            <div>
				<?php dynamic_sidebar( 'links-1' ); ?>
            </div>
            <a href="<?= wc_get_page_permalink( 'shop' ); ?>" class="footer-payment-link">
                <img src="<?= get_theme_mod( 'audioteria-wp-payment-logo', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/assets/footer-payment.png' ) ?>"
                     alt="<?= __( 'Audioteria payment link', 'audioteria-wp' ) ?>"/>
            </a>
        </div>
        <div class="footer-socials">
            <h5 class="footer-heading"><?= __( 'Socials', 'audioteria-wp' ) ?></h5>
            <div class="footer-socials-box">
                <a href="<?= esc_url( get_theme_mod( 'audioteria-wp-facebook', 'https://www.facebook.com/Audioteria' ) ) ?>"
                   target="_blank">
					<?php include( AUDIOTERIA_ASSETS_DIR . '/icons/facebook.svg' ) ?>
                </a>
                <a href="<?= esc_url( get_theme_mod( 'audioteria-wp-instagram', 'https://www.instagram.com/audioteria/' ) ) ?> "
                   target="_blank">
					<?php include( AUDIOTERIA_ASSETS_DIR . '/icons/instagram.svg' ) ?>
                </a>
                <a href="<?= esc_url( get_theme_mod( 'audioteria-wp-youtube', 'https://www.youtube.com/audioteria/' ) ); ?>"
                   target="_blank">
					<?php include( AUDIOTERIA_ASSETS_DIR . '/icons/youtube.svg' ) ?>
                </a>
            </div>
            <div class="footer-socials-box">
                <a href="<?= esc_url( get_theme_mod( 'audioteria-wp-twitter', 'https://www.twitter.com/audioteria' ) ) ?>"
                   target="_blank">
                    <?php include( AUDIOTERIA_ASSETS_DIR . '/icons/twitter.svg' ) ?>
                </a>
                <a href="<?= esc_url( get_theme_mod( 'audioteria-wp-cloud', 'https://www.icloud.com/audioteria/' ) ) ?> ">
					<?php include( AUDIOTERIA_ASSETS_DIR . '/icons/cloud.svg' ) ?>
                </a>
            </div>
        </div>
        <div class="footer-update">
            <h5 class="footer-heading">
				<?= __( 'Stay Updated', 'audioteria-wp' ) ?>
            </h5>
			<?php

			if ( get_theme_mod( 'audioteria-wp-mailchimp-shortcode' ) ) {
				$form_shortcode = get_theme_mod( 'audioteria-wp-mailchimp-shortcode' );
				echo do_shortcode( $form_shortcode );
			}
			?>
        </div>
    </div>
    <div class="footer-footnote">
        <div>
            <span><?= __( get_theme_mod( 'audioteria-wp-copyright', '&copy; 2022 b7 Enterprise Ltd - All rights reserved.' ), 'audioteria-wp' ); ?>
            </span>
			<?php
			wp_nav_menu(
				[
					'theme_location'   => 'footer-menu',
					'menu_id'          => 'footer-menu',
					'container'        => false,
					'items_wrap'       => '%3$s',
					'add_anchor_class' => 'footer-footnote-link',
					'fallback_cb'      => false,
				]
			);
			?>
        </div>
        <p><?php echo sprintf( "Designed and Developed by %s Studio14 %s", "<a  href='http://studio14online.co.uk' target='_blank' title='Studio14 Online'><strong>", "</strong></a>" ); ?>
        </p>

    </div>
</footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>