<?php
global $user_ID;
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Audioteria
 */
$main_class = 'site-main';
if (is_page(['about-us', 'about'])) {
	$main_class = 'about-main';
} elseif (is_page(['my-account', 'account'])) {
	$main_class = 'account-main';
} elseif (!is_user_logged_in() && is_page(['sign-in', 'sign-up', 'my-account'])) {
	$main_class = 'sign-in';
} elseif (is_page_template('page-contact-faq.php')) {
	$main_class = 'contact-main';
} else if(is_checkout()) {
  $main_class .= ' wrapper';
}

get_header();

if (!is_user_logged_in() && is_page(['my-account', 'account'])) {
	$main_class = 'sign-in';
}


?>

<main id="primary" class="<?= $main_class ?> ">

    <?php
	while (have_posts()) :
		the_post();

		get_template_part('template-parts/content', 'page');

		// If comments are open or we have at least one comment, load up the comment template.
		if (comments_open() || get_comments_number()) :
			comments_template();
		endif;

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php

get_footer();