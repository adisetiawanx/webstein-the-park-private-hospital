<?php
/**
 * Theme supports, menus and image sizes.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and navigation menus.
 */
function tpph_setup() {
	load_theme_textdomain( 'tpph', TPPH_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'tpph' ),
			'legal'   => __( 'Footer Legal', 'tpph' ),
		)
	);

	/*
	 * Image sizes are pinned to the layout breakpoints so WordPress emits a
	 * srcset the browser can actually choose from. Without these it falls back
	 * to its defaults (150/300/768/1024) which do not match any column width we
	 * use, and Lighthouse flags "properly size images" on every page.
	 */
	add_image_size( 'tpph-hero', 2560, 1100, true );      // Full-bleed page headers.
	add_image_size( 'tpph-hero-mobile', 900, 700, true ); // Same, portrait viewports.
	add_image_size( 'tpph-wide', 1600, 0, false );        // Full-width content bands.
	add_image_size( 'tpph-content', 1000, 0, false );     // Half-column content images.
	add_image_size( 'tpph-card', 700, 0, false );         // Three-up card images.
	add_image_size( 'tpph-portrait', 600, 640, true );    // Doctor / executive headshots.
}
add_action( 'after_setup_theme', 'tpph_setup' );

/**
 * Offer our custom sizes to editors in the media modal.
 *
 * @param array $sizes Existing selectable sizes.
 * @return array
 */
function tpph_custom_image_size_names( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'tpph-portrait' => __( 'Portrait (600×640)', 'tpph' ),
			'tpph-card'     => __( 'Card (700px wide)', 'tpph' ),
			'tpph-content'  => __( 'Content (1000px wide)', 'tpph' ),
			'tpph-wide'     => __( 'Wide band (1600px wide)', 'tpph' ),
		)
	);
}
add_filter( 'image_size_names_choose', 'tpph_custom_image_size_names' );

/**
 * Content width used by embeds and wide blocks.
 */
function tpph_content_width() {
	$GLOBALS['content_width'] = 1610;
}
add_action( 'after_setup_theme', 'tpph_content_width', 0 );
