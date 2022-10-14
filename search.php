<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Audioteria
 */

$search_parameters = \AudioteriaWP\Data\AbstractProducts::get_instance()->get_custom_categories();
$get_genre         = ! empty( $_GET['genres'] ) ? explode( ',', sanitize_text_field( $_GET['genres'] ) ) : [];
$get_categories    = ! empty( $_GET['categories'] ) ? explode( ',', sanitize_text_field( $_GET['categories'] ) ) : [];
$get_ratings       = ! empty( $_GET['ratings'] ) ? explode( ',', sanitize_text_field( $_GET['ratings'] ) ) : [];
$order_by          = ! empty( $_GET['order_by'] ) ? sanitize_text_field( $_GET['order_by'] ) : '';

get_header();

?>

    <main id="primary" class="search-main site-main">
        <ul class="search-nav">
            <li>
                <a title="Home" href="<?= home_url() ?>"><?= __( 'Home', 'audioteria-wp' ) ?></a>
            </li>
            <li class="seperator">|</li>
            <li class="active">
                <a title="<?php printf( __( 'Search Results for %s', 'audioteria-wp' ), get_search_query() ) ?>"
                   href="#"><?php printf( __( 'Search Results for %s', 'audioteria-wp' ), get_search_query() ) ?></a>
            </li>
        </ul>

		<?php get_product_search_form() ?>

        <div class="search-container">
            <div class="search-results-heading">
                <p class="search-filter-heading"><?= __( 'Filter by', 'audioteria-wp' ) ?></p>
                <p class="search-results-number"><?php printf( __( 'Showing <strong> %s </strong> search results for <strong> %s </strong>', 'audioteria-wp' ), $wp_query->found_posts, get_search_query() ) ?></p>
                <button class="mobile-filter"><?= __( 'Filters', 'audioteria-wp' ) ?>
                    <svg width="18" height="11" viewBox="0 0 18 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M17 3H6M4 3H1M12 8H1M17 8H14M5 1C5.26522 1 5.51957 1.10536 5.70711 1.29289C5.89464 1.48043 6 1.73478 6 2V4C6 4.26522 5.89464 4.51957 5.70711 4.70711C5.51957 4.89464 5.26522 5 5 5C4.73478 5 4.48043 4.89464 4.29289 4.70711C4.10536 4.51957 4 4.26522 4 4V2C4 1.73478 4.10536 1.48043 4.29289 1.29289C4.48043 1.10536 4.73478 1 5 1ZM13 6C13.2652 6 13.5196 6.10536 13.7071 6.29289C13.8946 6.48043 14 6.73478 14 7V9C14 9.26522 13.8946 9.51957 13.7071 9.70711C13.5196 9.89464 13.2652 10 13 10C12.7348 10 12.4804 9.89464 12.2929 9.70711C12.1054 9.51957 12 9.26522 12 9V7C12 6.73478 12.1054 6.48043 12.2929 6.29289C12.4804 6.10536 12.7348 6 13 6Z"
                                stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="search-results-sort">
                    <select name="order_by" id="">
                        <option value="" <?php selected( $order_by, '' ) ?>><?= __( 'Sort By: Default', 'audioteria-wp' ) ?> </option>
                        <option value="popularity" <?php selected( $order_by, 'popularity' ) ?>><?= __( 'Sort By: Popular', 'audioteria-wp' ) ?> </option>
                        <option value="date" <?php selected( $order_by, 'date' ) ?>><?= __( 'Sort By: Latest', 'audioteria-wp' ) ?> </option>
                        <option value="a-z" <?php selected( $order_by, 'a-z' ) ?>><?= __( 'Sort By: A to Z', 'audioteria-wp' ) ?></option>
                        <option value=z-a" <?php selected( $order_by, 'z-a' ) ?>><?= __( 'Sort By: Z to Z', 'audioteria-wp' ) ?></option>
                    </select>
                    <svg width="15" height="9" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.5 0.5L7.5 5.5L12.5 0.5L14.5 1.5L7.5 8.5L0.5 1.5L2.5 0.5Z" fill="#222222"/>
                    </svg>
                </div>
            </div>

            <div class="search-wrapper">
                <div class="search-filter">
                    <div class="search-filter-box">
                        <div class="search-filter-wrap">
                            <div class="mobile-filter-heading">
                                <p>Filters</p>
                                <button class="mobile-filter-heading-button">
									<?= file_get_contents( AUDIOTERIA_ASSETS_ICONS_DIR . '/filter-mobile.svg' ); ?>
                                </button>
                            </div>
							<?php
							if ( ! empty( $search_parameters['genres'] ) ) {
								?>
                                <p class="search-filter-title"><?= __( 'Genres', 'audioteria-wp' ) ?></p>
								<?php
								foreach ( $search_parameters['genres'] as $genre ) {
									$checked = in_array( $genre['slug'], $get_genre ) ? 'checked="checked"' : '';
									?>
                                    <div>
                                        <input type="checkbox" value="<?= $genre['slug'] ?>" name="genres"
                                               id="<?= 'genre-' . $genre['slug'] ?>" <?= $checked ?>>
                                        <label for="<?= 'genre-' . $genre['slug'] ?>"><?= $genre['name'] ?>
                                            <span class="checkbox"></span>
                                            <span class="checkbox-tick"></span>
                                        </label>
                                    </div>
								<?php }
							}
							?>
                        </div>

                        <div class="search-filter-wrap">
							<?php
							if ( ! empty( $search_parameters['genres'] ) ) {
								?>
                                <p class="search-filter-title"><?= __( 'Categories', 'audioteria-wp' ) ?></p>
								<?php
								foreach ( $search_parameters['categories'] as $category ) {
									$checked = in_array( $category['slug'], $get_categories ) ? 'checked="checked"' : '';
									?>
                                    <div>
                                        <input type="checkbox" value="<?= $category['slug'] ?>" name="categories"
                                               id="<?= 'categories-' . $category['slug'] ?>" <?= $checked ?>>
                                        <label for="<?= 'categories-' . $category['slug'] ?>"><?= $category['name'] ?>
                                            <span class="checkbox"></span>
                                            <span class="checkbox-tick"></span>
                                        </label>
                                    </div>
								<?php }
							}
							?>
                            <button class="see-all"><?= __( 'See All', 'audioteria-wp' ) ?>
                                <svg width="8" height="13" viewBox="0 0 8 13" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                            d="M0.961987 12.4662C0.786233 12.2904 0.6875 12.052 0.6875 11.8034C0.6875 11.5548 0.786233 11.3164 0.961987 11.1406L5.60261 6.49998L0.961987 1.85935C0.791214 1.68254 0.696719 1.44572 0.698855 1.19992C0.700991 0.954106 0.799587 0.718969 0.973407 0.545148C1.14723 0.371328 1.38236 0.272733 1.62817 0.270597C1.87398 0.268461 2.1108 0.362956 2.28761 0.533729L7.59105 5.83717C7.7668 6.01298 7.86554 6.25139 7.86554 6.49998C7.86554 6.74857 7.7668 6.98699 7.59105 7.16279L2.28761 12.4662C2.1118 12.642 1.87339 12.7407 1.6248 12.7407C1.37621 12.7407 1.13779 12.642 0.961987 12.4662V12.4662Z"
                                            fill="#CB5715"/>
                                </svg>
                            </button>
                        </div>

                        <div class="search-filter-wrap">
                            <p class="search-filter-title"><?= __( 'Ratings', 'audioteria-wp' ) ?></p>
                            <div>
								<?php $rating_checked = in_array( '5', $get_ratings ) ? 'checked="checked"' : ''; ?>
                                <input type="checkbox" value="5" name="ratings" id="five-star" <?= $rating_checked ?>>
                                <label for="five-star">
									<?= file_get_contents( AUDIOTERIA_ASSETS_ICONS_DIR . '/five-star.svg' ); ?>

                                    <span class="checkbox"></span>
                                    <span class="checkbox-tick"></span>
                                </label>
                            </div>
                            <div>
								<?php $rating_checked = in_array( '4', $get_ratings ) ? 'checked="checked"' : ''; ?>
                                <input type="checkbox" value="4" name="ratings" id="four-star" <?= $rating_checked ?>>
                                <label for="four-star">
									<?= file_get_contents( AUDIOTERIA_ASSETS_ICONS_DIR . '/four-star.svg' ); ?>
                                    <span class="checkbox"></span>
                                    <span class="checkbox-tick"></span>
                                </label>
                            </div>
                            <div>
								<?php $rating_checked = in_array( '3', $get_ratings ) ? 'checked="checked"' : ''; ?>
                                <input type="checkbox" value="3" name="ratings" id="three-stars" <?= $rating_checked ?>>
                                <label for="three-stars">
									<?= file_get_contents( AUDIOTERIA_ASSETS_ICONS_DIR . '/three-star.svg' ); ?>
                                    <span class="checkbox"></span>
                                    <span class="checkbox-tick"></span>
                                </label>
                            </div>
                            <div>
								<?php $rating_checked = in_array( '2', $get_ratings ) ? 'checked="checked"' : ''; ?>
                                <input type="checkbox" value="2" name="ratings" id="two-stars" <?= $rating_checked ?>>
                                <label for="two-stars">
									<?= file_get_contents( AUDIOTERIA_ASSETS_ICONS_DIR . '/two-star.svg' ); ?>


                                    <span class="checkbox"></span>
                                    <span class="checkbox-tick"></span>
                                </label>
                            </div>
                            <div>
								<?php $rating_checked = in_array( '1', $get_ratings ) ? 'checked="checked"' : ''; ?>
                                <input type="checkbox" value="1" name="ratings" id="one-stars" <?= $rating_checked ?>>
                                <label for="one-stars">
									<?= file_get_contents( AUDIOTERIA_ASSETS_ICONS_DIR . '/one-star.svg' ); ?>


                                    <span class="checkbox"></span>
                                    <span class="checkbox-tick"></span>
                                </label>
                            </div>
                        </div>
                        <button class="mobile-filter-search"><?= __( 'Search', 'audioteria-wp' ) ?></button>
                    </div>
                </div>
                <div class="search-results">
					<?php
					if ( have_posts() ) :
						?>
                        <div class="search-results-wrapper">
							<?php
							while ( have_posts() ) {
								the_post();

								/**
								 * Hook: woocommerce_shop_loop.
								 */
								do_action( 'woocommerce_shop_loop' );

								wc_get_template_part( 'content', 'product' );
							}
							?>
                        </div>
						<?php
						$args = [
							'total'   => $wp_query->max_num_pages,
							'current' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
						];

						wc_get_template( 'loop/pagination.php', $args );

					else:

						get_template_part( 'template-parts/content', 'search-none' );

					endif;

					/**
					 * Hook: woocommerce_after_shop_loop.
					 *
					 * @hooked woocommerce_pagination - 10
					 */
					do_action( 'woocommerce_after_shop_loop' );

					?>
                </div>
            </div>
        </div>
    </main><!-- #main -->

<?php
get_footer();
