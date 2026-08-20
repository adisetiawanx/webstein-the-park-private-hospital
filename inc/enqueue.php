<?php
/**
 * Front-end assets.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cache-bust off file modification time so a deploy never serves a stale
 * stylesheet, without us having to remember to bump TPPH_VERSION.
 *
 * @param string $relative_path Path relative to the theme root.
 * @return string
 */
function tpph_asset_version( $relative_path ) {
	$file = TPPH_DIR . '/' . ltrim( $relative_path, '/' );

	return file_exists( $file ) ? (string) filemtime( $file ) : TPPH_VERSION;
}

/**
 * Enqueue styles and scripts.
 */
function tpph_enqueue_assets() {
	wp_enqueue_style( 'tpph-style', get_stylesheet_uri(), array(), tpph_asset_version( 'style.css' ) );

	wp_enqueue_script(
		'tpph-navigation',
		TPPH_URI . '/js/navigation.js',
		array(),
		tpph_asset_version( 'js/navigation.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'tpph_enqueue_assets' );

/**
 * Preload the two variable font files.
 *
 * Both families appear above the fold on every template — Noto Serif in the
 * page heading, Noto Sans in the navigation — so the browser should not have to
 * discover them by parsing the stylesheet first. This is worth roughly 200ms of
 * LCP on a throttled mobile connection.
 */
function tpph_preload_fonts() {
	$fonts = array(
		'assets/fonts/noto-serif-variable.woff2',
		'assets/fonts/noto-sans-variable.woff2',
	);

	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( TPPH_URI . '/' . $font )
		);
	}
}
add_action( 'wp_head', 'tpph_preload_fonts', 1 );
