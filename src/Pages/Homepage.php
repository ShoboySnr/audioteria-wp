<?php

namespace AudioteriaWP\Pages;

use AudioteriaWP\Data\AbstractProducts as Product;

class Homepage {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
		add_action( 'audioteria_wp_before_close_header', [ $this, 'add_home_banner' ] );
		add_action( 'audioteria_wp_premier_release', [ $this, 'premier_release_section' ] );
		add_action( 'audioteria_wp_front_page_categories', [ $this, 'front_page_categories_section' ] );
	}

	public function frontend_scripts() {
		wp_enqueue_script( 'audioteria-wp-book-card', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/components/book_card.js', array(), AUDIOTERIA_WP_THEME_VERSION, false );
		wp_enqueue_script( 'audioteria-wp-index', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/scripts/index.js', array(), AUDIOTERIA_WP_THEME_VERSION, true );
		wp_enqueue_script( 'audioteria-wp-download-now-section', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/components/download_now_section.js', array(), AUDIOTERIA_WP_THEME_VERSION, true );
	}

	public function add_home_banner() {
		if ( is_home() || is_front_page() ) {
			$featured_banners = Product::get_instance()->get_home_banners();
			if ( ! empty( $featured_banners ) ) {
				ob_start();
				?>
        <div class="home-heading-wrapper">
					<?php foreach ( $featured_banners as $featured_banner ) { ?>
						<div class="home-heading">
							<div class="home-heading-bg"></div>
							<img class="home-heading-img" src="<?= $featured_banner['thumbnails']['full']; ?>" alt="<?= $featured_banner['thumbnails']['full']; ?>">
							<div class="home-heading-text-wrapper">
								<h2 class="home-heading-title"><?= $featured_banner['name'] ?></h2>
								<p class="home-heading-text"><?= limit_character( strip_tags( $featured_banner['short_description'] ), 110 ) ?></p>
								<?php if ( $featured_banner['view_url'] ): ?>
									<a href="<?= $featured_banner['view_url'] ?>" title="view more" class="home-heading-link"><?= __( 'View more', 'audioteria-wp' ) ?></a>
								<?php endif; ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="home-heading-pointer-wrapper">
					<div class="home-heading-pointer">
						<?php foreach ( $featured_banners as $featured_banner ) { ?>
							<span id="dotted-<?= $featured_banner['id'] ?>"></span>
						<?php } ?>
					</div>
				</div>
			<?php }

			echo ob_get_clean();
		}
	}

	/**
	 * @return Homepage
	 */
	public static function get_instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}

	public function premier_release_section() {
		$premier_tag = get_term_by( 'slug', Product::get_instance()->premiere_releases, Product::get_instance()->product_tag );
		$premier_releases = Product::get_instance()->get_premier_release();

		if ( ! empty( $premier_releases ) ) {
			$permalink = get_term_link( $premier_tag->term_id );
			ob_start();
			?>
        <section class="premier-releases">
            <div class="premier-releases-text">
                <p><?= __( 'Audioteria Exclusive', 'audioteria-wp' ) ?></p>
                <a href="<?= $permalink ?>">
                    <?= __( 'View more', 'audioteria-wp' ) ?>
                    <?php include_once( AUDIOTERIA_ASSETS_ICONS_DIR . '/view-more-arrow.svg' ) ?>
                </a>
            </div>
            <div class="premier-releases-container premier-releases-container-after">
                <div class="premier-releases-wrapper">
                    <?php foreach ( $premier_releases as $premier_release ) { ?>
                        <a class="premier-releases-wrapper-item" href="<?= $premier_release['view_url'] ?>" title="<?= $premier_release['name'] ?>">
                            <img src="<?= $premier_release['thumbnails']['medium'] ?>" alt="<?= $premier_release['name'] ?>">
                        </a>
                    <?php } ?>
                </div>
                <div class="arrows">
                    <div class="left-arrow">
                        <?php include( AUDIOTERIA_ASSETS_ICONS_DIR . '/left-arrow.svg' ) ?>
                    </div>
                    <div class="right-arrow">
                        <?php include( AUDIOTERIA_ASSETS_ICONS_DIR . '/right-arrow.svg' ) ?>
                    </div>
                </div>
            </div>

        </section>
        <?php echo ob_get_clean();
		}
	}

	public function front_page_categories_section() {
		$categories = Product::get_instance()->get_homepage_category();
		if ( ! empty( $categories ) ) {
			ob_start();
			foreach ( $categories as $category ) {?>
				<section class="category <?= $category['slug'] ?>">
					<div class="category-text">
						<p><?= $category['name'] ?></p>
						<a href="<?= $category['view_url'] ?>" title="<?= $category['name'] ?>">
							<?= __( 'View more', 'audioteria-wp' ) ?>
							<?php include( AUDIOTERIA_ASSETS_ICONS_DIR . '/view-more-arrow.svg' ) ?>
						</a>
					</div>
					<?php if ( ! empty( $category['products'] ) ) {
                        $single_item_style = "";
                        $count_categories = count($category['products']);
                        if($count_categories <= 5 ){
                            $single_item_style = "few-items";
                        }
                        ?>
						<div class="category-container category-container-after">
							<div class="category-wrapper <?= $single_item_style; ?>">
								<?php foreach ( $category['products'] as $product ) {

									$post_object = get_post( $product['id'] );
									setup_postdata( $GLOBALS['post'] =& $post_object ); ?>
									<div class="category-wrapper-item">
										<?php wc_get_template_part( 'content', 'product' ); ?>
									</div>
								<?php } ?>
							</div>
							<div class="arrows">
								<div class="left-arrow">
									<?php include( AUDIOTERIA_ASSETS_ICONS_DIR . '/left-arrow.svg' ) ?>
								</div>
								<div class="right-arrow">
									<?php include( AUDIOTERIA_ASSETS_ICONS_DIR . '/right-arrow.svg' ) ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</section>
			<?php }

			echo ob_get_clean();
		}
	}
}
