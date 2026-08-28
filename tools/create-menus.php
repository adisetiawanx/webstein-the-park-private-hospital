<?php
/**
 * Build the primary and legal menus and assign them to their theme locations.
 *
 * The theme renders a hard-coded fallback when no menu exists, so the site
 * looks right on a fresh install. This turns that fallback into real menu
 * objects the client can edit in wp-admin.
 *
 * Idempotent: existing menus are left alone.
 */

/*
 * Run through wp-cli or `wp eval-file`, never over HTTP. A migration tool that
 * copies wp-content verbatim will put this on a public server, where without
 * this line it is a reachable endpoint that rewrites page content.
 */
defined( 'ABSPATH' ) || exit;

function tpph_menu_page_id( $path ) {
	$page = get_page_by_path( $path );
	return $page ? $page->ID : 0;
}

function tpph_add_page_item( $menu_id, $path, $label = '', $parent = 0, $order = 0 ) {
	$page_id = tpph_menu_page_id( $path );

	if ( ! $page_id ) {
		echo "  missing page: {$path}\n";
		return 0;
	}

	return wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-object-id' => $page_id,
			'menu-item-object'    => 'page',
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-title'     => $label,
			'menu-item-parent-id' => $parent,
			'menu-item-position'  => $order,
		)
	);
}

function tpph_add_custom_item( $menu_id, $url, $label, $parent = 0, $order = 0, $target = '' ) {
	return wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-url'       => $url,
			'menu-item-title'     => $label,
			'menu-item-type'      => 'custom',
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent,
			'menu-item-position'  => $order,
			'menu-item-target'    => $target,
		)
	);
}

$locations = get_theme_mod( 'nav_menu_locations', array() );

/* ---------------------------------------------------------------- Primary */

if ( ! wp_get_nav_menu_object( 'Primary Navigation' ) ) {
	$primary = wp_create_nav_menu( 'Primary Navigation' );
	echo "created menu: Primary Navigation (#{$primary})\n";

	tpph_add_custom_item( $primary, home_url( '/' ), 'Home', 0, 1 );

	$about = tpph_add_page_item( $primary, 'about', 'About', 0, 2 );
	tpph_add_custom_item( $primary, home_url( '/about/our-team/#executive-team' ), 'Our Executive Team', $about, 3 );
	tpph_add_custom_item( $primary, home_url( '/about/our-team/#our-doctors' ), 'Our Doctors', $about, 4 );

	$patients = tpph_add_page_item( $primary, 'for-patients-visitors', 'For Patients & Visitors', 0, 5 );
	tpph_add_page_item( $primary, 'for-patients-visitors', 'Visitors', $patients, 6 );
	tpph_add_custom_item( $primary, 'https://www.preadmit.com.au/Patient/parkprivatehospital', 'Online Admission', $patients, 7, '_blank' );
	tpph_add_page_item( $primary, 'for-patients-visitors/fees-charges-insurance', 'Fees, Charges & Insurance', $patients, 8 );
	tpph_add_page_item( $primary, 'for-patients-visitors/preparing-for-your-admission', 'Preparing for your Admission', $patients, 9 );
	tpph_add_page_item( $primary, 'for-patients-visitors/post-operative-care', 'Post Operative Care', $patients, 10 );
	tpph_add_page_item( $primary, 'for-patients-visitors/patient-rights-responsibilities', 'Patient Rights & Responsibilities', $patients, 11 );

	$doctors = tpph_add_page_item( $primary, 'for-doctors', 'For Doctors', 0, 12 );
	tpph_add_page_item( $primary, 'for-doctors', 'Credentialing', $doctors, 13 );

	tpph_add_custom_item( $primary, 'https://www.preadmit.com.au/Patient/parkprivatehospital', 'Online Admissions', 0, 14, '_blank' );
	tpph_add_page_item( $primary, 'safety-and-quality', 'Safety & Quality', 0, 15 );
	tpph_add_page_item( $primary, 'careers', 'Careers', 0, 16 );
	tpph_add_page_item( $primary, 'contact-us', 'Contact Us', 0, 17 );

	$locations['primary'] = $primary;
} else {
	echo "menu already exists: Primary Navigation\n";
}

/* ------------------------------------------------------------------ Legal */

if ( ! wp_get_nav_menu_object( 'Footer Legal' ) ) {
	$legal = wp_create_nav_menu( 'Footer Legal' );
	echo "created menu: Footer Legal (#{$legal})\n";

	tpph_add_page_item( $legal, 'privacy-policy', 'Privacy Policy', 0, 1 );
	tpph_add_page_item( $legal, 'disclaimer', 'Disclaimer', 0, 2 );

	$locations['legal'] = $legal;
} else {
	echo "menu already exists: Footer Legal\n";
}

set_theme_mod( 'nav_menu_locations', $locations );

echo "\nlocations assigned\n";
