<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Audioteria
 */

get_header();
?>

	<main id="primary" class="site-main error-page">

		<h2><?= __('404', 'audioteria-wp') ?></h2>
		<p><?= __('Not Found', 'audioteria-wp') ?></p>
		<p><?= __('Sorry, we are unable to find that page', 'audioteria-wp') ?></p>

	</main><!-- #main -->

<?php
get_footer();
