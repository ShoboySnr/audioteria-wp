<?php

//define some path
define('AUDIOTERIA_ASSETS_URI', get_template_directory_uri() . '/assets');
define('AUDIOTERIA_ASSETS_DIR', get_template_directory() . '/assets');
define('AUDIOTERIA_ASSETS_ICONS_URI', AUDIOTERIA_ASSETS_URI . '/icons');
define('AUDIOTERIA_ASSETS_ICONS_DIR', AUDIOTERIA_ASSETS_DIR . '/icons');
define('AUDIOTERIA_ASSETS_IMAGES_URI', AUDIOTERIA_ASSETS_URI . '/images');
define('AUDIOTERIA_ASSETS_FONTS_URI', AUDIOTERIA_ASSETS_URI . '/fonts');
define('AUDIOTERIA_PARTIAL_VIEWS', get_template_directory() . '/partials');
define('AUDIOTERIA_WEB_COMPONENTS', get_template_directory() . '/components');
define('AUDIOTERIA_PRELOADER_SVG', AUDIOTERIA_ASSETS_IMAGES_URI . '/preloader.svg');
define('AUDIOTERIA_PLACEHOLDER_IMG', AUDIOTERIA_ASSETS_IMAGES_URI . '/placeholder.png');
define('AUDIOTERIA_API_BASE_ROUTE', 'audioteria-wp/v1');
define('AUDIOTERIA_API_BASE', get_home_url() . '/wp-json/' . AUDIOTERIA_API_BASE_ROUTE);
define('AUDIOTERIA_WOOCOMMERCE_TEMPLATE_DIR', get_template_directory() . '/woocommerce');
define('AUDIOTERIA_FRONTEND_PUBLIC_DIR', get_template_directory_uri() . '/audioteria-frontend/public');
define('AUDIOTERIA_CUSTOM_LOGO', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/assets/logo.png');
define('AUDIOTERIA_CUSTOM_MOBILE_LOGO', AUDIOTERIA_FRONTEND_PUBLIC_DIR . '/assets/mobile-logo.png');
