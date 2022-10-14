<?php

/**
 * Audioteria functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Audioteria
 */

if ( ! defined( 'JWT_AUTH_SECRET_KEY' ) ) {
	define( 'JWT_AUTH_SECRET_KEY', 'h~|m3<5-VP4<?`Nm]D[qmkegvuzMyGJ2tOJz{D;@RzmjTWwpvk~+Y`}Q#&8-%h+@' );
}

if ( ! defined( 'JWT_AUTH_CORS_ENABLE' ) ) {
	define( 'JWT_AUTH_CORS_ENABLE', true );
}


if ( ! defined( 'AUDIOTERIA_WP_THEME_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'AUDIOTERIA_WP_THEME_VERSION', '1.0.0' );
}

/**
 *
 * Define Paths to directories and files in the theme
 */
require get_template_directory() . '/inc/define-paths.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function audioteria_wp_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Audioteria, use a find and replace
		* to change 'audioteria-wp' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'audioteria-wp', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'priamry-menu' => esc_html__( 'Primary Menu', 'audioteria-wp' ),
			'footer-menu'  => esc_html__( 'Footer Menu ', 'audioteria-wp' ),
			'top-menu'     => esc_html__( 'Top Menu ', 'audioteria-wp' ),
			'mobile-menu'  => esc_html__( 'Mobile Menu ', 'audioteria-wp' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'audioteria_wp_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}

add_action( 'after_setup_theme', 'audioteria_wp_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function audioteria_wp_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'audioteria_wp_content_width', 640 );
}

add_action( 'after_setup_theme', 'audioteria_wp_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function audioteria_wp_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'audioteria-wp' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'audioteria-wp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Custom Quick Links', 'audioteria-wp' ),
			'id'            => 'links-1',
			'description'   => esc_html__( 'Add widgets here.', 'audioteria-wp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}

add_action( 'widgets_init', 'audioteria_wp_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function audioteria_wp_scripts() {


	wp_enqueue_style( 'audioteria-wp-min-style', get_template_directory_uri() . '/style.min.css', array(), AUDIOTERIA_WP_THEME_VERSION );
	wp_style_add_data( 'audioteria-wp-style', 'rtl', 'replace' );

	wp_enqueue_script( 'audioteria-wp-contact-js', get_template_directory_uri() . '/audioteria-frontend/public/scripts/contact.js', array(), AUDIOTERIA_WP_THEME_VERSION, true );
	wp_enqueue_script( 'audioteria-wp-navigation', get_template_directory_uri() . '/js/navigation.js', array(), AUDIOTERIA_WP_THEME_VERSION, true );
	wp_enqueue_script( 'audioteria-wp-profile-tabs', get_template_directory_uri() . '/js/profile-tabs.js', array(), AUDIOTERIA_WP_THEME_VERSION, true );
	wp_enqueue_script( 'audioteria-wp-mini-cart-toggle', get_template_directory_uri() . '/js/mini-cart.js', array(), AUDIOTERIA_WP_THEME_VERSION, true );
	wp_enqueue_script( 'audioteria-wp-custom-js', get_template_directory_uri() . '/js/custom.js', array(), AUDIOTERIA_WP_THEME_VERSION, true );
	$ajax_info = [
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'wp-audioteria-search-form-nonce' ),
	];
	wp_localize_script( 'audioteria-wp-search-js', 'wp_ajax_info', $ajax_info );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'audioteria_wp_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
