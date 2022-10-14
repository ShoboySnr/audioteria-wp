<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>


</div>
<footer>
    <p style="text-align: center !important; padding: 34px !important;">
		<?php echo wp_kses_post( wpautop( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
    </p>

    <center style="margin-top: 24px;">
        <a style="height: 20px;width: 20px;display: inline-block;margin-right:10px;"
           href="<?= esc_url( get_theme_mod( 'audioteria-wp-facebook', 'https://www.facebook.com/Audioteria' ) ) ?>">
            <img src="https://audioteria-wp.s14staging.uk/wp-content/uploads/2022/05/facebook.png" style="width: 100%;"
                 alt="facebook">
        </a>
        <a style="height: 20px;width: 20px;display: inline-block;margin-right:10px;"
           href="<?= esc_url( get_theme_mod( 'audioteria-wp-twitter', 'https://www.twitter.com/audioteria' ) ) ?>">
            <img src="https://audioteria-wp.s14staging.uk/wp-content/themes/audioteria-wp/audioteria-frontend/public/assets/twitter.png"
                 style="width: 100%;" alt="twitter">
        </a>
        <a style="height: 20px;width: 20px;display: inline-block;margin-right:10px;"
           href="<?= esc_url( get_theme_mod( 'audioteria-wp-instagram', 'https://www.instagram.com/audioteria/' ) ) ?>">
            <img src="https://audioteria-wp.s14staging.uk/wp-content/uploads/2022/05/instagram.png" style="width: 100%;"
                 alt="instagram">
        </a>
        <a style="height: 20px;width: 20px;display: inline-block;margin-right:10px;"
           href="<?= esc_url( get_theme_mod( 'audioteria-wp-cloud', 'https://www.icloud.com/audioteria/' ) ) ?>">
            <img src="https://audioteria-wp.s14staging.uk/wp-content/uploads/2022/05/icloud.png" style="width: 100%;"
                 alt="icloud"></a>
        <a style="height: 20px;width: 20px;display: inline-block;"
           href="<?= esc_url( get_theme_mod( 'audioteria-wp-youtube', 'https://www.youtube.com/audioteria/' ) ); ?>">
            <img src="https://audioteria-wp.s14staging.uk/wp-content/uploads/2022/05/youtube.png" style="width: 100%;"
                 alt="youtube">
        </a>
    </center>
</footer>
</div>
</center>
</body>

</html>
