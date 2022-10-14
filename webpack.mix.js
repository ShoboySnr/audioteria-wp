// eslint-disable-next-line no-undef
const mix = require( 'laravel-mix' );
// eslint-disable-next-line no-undef
const tailwindcss = require( 'tailwindcss' );

mix.sass( 'sass/style.scss', 'style.css' )
	.options( {
		processCssUrls: false,
		postCss: [ tailwindcss( 'tailwind.config.js' ) ],
	} );
 