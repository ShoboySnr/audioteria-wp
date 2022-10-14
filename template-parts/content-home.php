<main class="main">
    <div class="main-wrapper">
        <div class="home-download"
             style="background:linear-gradient(0deg, rgba(17, 17, 17, 0.8), rgba(17, 17, 17, 0.8)), url('<?= get_theme_mod( 'audioteria-wp-download-bg',  AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/assets/download_now_sample-up.png' ); ?>'); background-size: cover;">
            <p class="home-download-text"><?= __( 'Download our app', 'audioteria-wp' ) ?> </p>
            <div class="home-download-links">
                <a href="<?= get_theme_mod( 'audioteria-wp-google-play-link-input', 'https://play.google.com/store/apps' ); ?>"
                   target="_blank" title="<?= __( 'Google play', 'audioteria-wp' ) ?>">
                    <img src="<?= get_theme_mod( 'audioteria-wp-google-play-link-img', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/assets/google_play.png' ) ?>"
                         alt="<?= __( 'Google Play', 'audioteria-wp' ) ?>"/>
                </a>
                <a href="<?= get_theme_mod( 'audioteria-wp-apple-store-link', 'https://www.apple.com/store' ); ?>"
                   target="_blank" title="<?= __( 'App store', 'audioteria-wp' ) ?>">
                    <img src="<?= get_theme_mod( 'audioteria-wp-apple-store-img', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/assets/app_store.png' ) ?>"
                         alt="<?= __( 'App store', 'audioteria-wp' ) ?>"></a>
            </div>
        </div>

        <!-- Premier Release section -->
		<?php do_action( 'audioteria_wp_premier_release' ); ?>
        <!-- Premier release ends -->


        <!-- Homepage Categories -->
		<?php do_action( 'audioteria_wp_front_page_categories' ); ?>
        <!-- Homepage Categories -->

    </div>
</main>