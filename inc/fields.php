<?php
/**
 * Secure Custom Fields wiring.
 *
 * Field groups are stored as local JSON in acf-json/. That makes the content
 * model part of the repository: it is diffable in review, it survives a
 * database reset, and a fresh clone comes up with the same fields rather than
 * an empty edit screen.
 *
 * SCF (the WordPress-maintained fork of ACF) includes Repeater, Flexible
 * Content, Gallery, Clone and Options Pages in the free plugin, so nothing here
 * needs an ACF Pro licence.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

/**
 * Write new and edited field groups into the theme instead of only the
 * database.
 *
 * @param string $path Default save path.
 * @return string
 */
function tpph_acf_json_save_point( $path ) {
	return TPPH_DIR . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'tpph_acf_json_save_point' );

/**
 * Load field groups from the theme.
 *
 * Replaces the default path rather than appending to it, so there is exactly
 * one place field definitions can come from.
 *
 * @param array $paths Default load paths.
 * @return array
 */
function tpph_acf_json_load_point( $paths ) {
	return array( TPPH_DIR . '/acf-json' );
}
add_filter( 'acf/settings/load_json', 'tpph_acf_json_load_point' );

/**
 * Register the site-wide options page.
 *
 * Holds the handful of values that appear on every template — the header call
 * to action, the fallback hero, and the Google Maps key handling — so they are
 * not duplicated onto each page.
 */
function tpph_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Site Settings', 'tpph' ),
			'menu_title' => __( 'Site Settings', 'tpph' ),
			'menu_slug'  => 'tpph-settings',
			'capability' => 'edit_theme_options',
			'position'   => 59,
			'icon_url'   => 'dashicons-admin-settings',
			'redirect'   => false,
			'autoload'   => true, // One query for values read on every request.
		)
	);
}
add_action( 'acf/init', 'tpph_register_options_page' );

/**
 * Point the SCF Google Map field at the key stored in wp-config.php.
 *
 * The key never enters the repository. Define TPPH_GOOGLE_MAPS_KEY in
 * wp-config.php on each environment.
 */
function tpph_acf_google_maps_key() {
	if ( defined( 'TPPH_GOOGLE_MAPS_KEY' ) && TPPH_GOOGLE_MAPS_KEY ) {
		acf_update_setting( 'google_api_key', TPPH_GOOGLE_MAPS_KEY );
	}
}
add_action( 'acf/init', 'tpph_acf_google_maps_key' );

/**
 * Warn on the plugins screen if SCF is missing, rather than letting the site
 * quietly render empty sections.
 */
function tpph_scf_missing_notice() {
	if ( function_exists( 'get_field' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-error"><p><strong>%s</strong> %s</p></div>',
		esc_html__( 'The Park Private Hospital theme:', 'tpph' ),
		esc_html__( 'Secure Custom Fields is not active. Page content will not render until it is.', 'tpph' )
	);
}
add_action( 'admin_notices', 'tpph_scf_missing_notice' );
