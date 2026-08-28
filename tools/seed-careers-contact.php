<?php
/**
 * Populate Careers and Contact Us, and create the three placeholder vacancies
 * drawn in the artboard.
 *
 * The Careers intro and all three vacancies are lorem ipsum and dummy titles in
 * the design. Reproduced as such.
 */

/*
 * Run through wp-cli or `wp eval-file`, never over HTTP. A migration tool that
 * copies wp-content verbatim will put this on a public server, where without
 * this line it is a reachable endpoint that rewrites page content.
 */
defined( 'ABSPATH' ) || exit;

function tpph_media( $slug ) {
	$map = get_option( 'tpph_media_map', array() );
	return isset( $map[ $slug ] ) ? (int) $map[ $slug ] : 0;
}

const TPPH_LOREM = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

/* ------------------------------------------------------------------ Careers */

$careers = get_page_by_path( 'careers' );

if ( $careers ) {
	update_post_meta( $careers->ID, '_wp_page_template', 'page-templates/careers.php' );

	update_field( 'hero_title', 'Careers', $careers->ID );
	update_field( 'hero_image', tpph_media( '5-careers-header' ), $careers->ID );

	update_field( 'intro_heading', 'Join our team', $careers->ID );
	update_field( 'intro_text', TPPH_LOREM . ' ' . TPPH_LOREM . "\n" . TPPH_LOREM, $careers->ID );
	update_field(
		'intro_images',
		array(
			tpph_media( '5-careers-image-22' ),
			tpph_media( '5-careers-image-27' ),
			tpph_media( '5-careers-image-44' ),
		),
		$careers->ID
	);

	update_field( 'vacancies_heading', 'Current Vacancies', $careers->ID );
	update_field( 'register_heading', 'Register your interest', $careers->ID );
	update_field(
		'register_text',
		"We encourage you to register your interest in joining The Park Private Hospital team by emailing your CV and a cover letter to careers@tpph.com.au\n\nWe will keep your application on file for future reference should vacancies arise.",
		$careers->ID
	);
	update_field( 'closing_image', tpph_media( '5-careers-image-12' ), $careers->ID );

	echo "careers populated (#{$careers->ID})\n";
}

/* --------------------------------------------------------------- Vacancies */

$vacancies = array( 'Job One', 'Job Two', 'Job Three' );

foreach ( $vacancies as $order => $title ) {
	$existing = get_posts(
		array(
			'post_type'      => 'vacancy',
			'title'          => $title,
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		)
	);

	$id = $existing
		? $existing[0]
		: wp_insert_post(
			array(
				'post_type'   => 'vacancy',
				'post_title'  => $title,
				'post_status' => 'publish',
			)
		);

	wp_update_post(
		array(
			'ID'         => $id,
			'menu_order' => $order + 1,
		)
	);

	update_field( 'summary', 'Short job description', $id );
	update_field(
		'points',
		array(
			array( 'text' => 'role entails' ),
			array( 'text' => 'experience needed' ),
			array( 'text' => 'hours per week' ),
			array( 'text' => 'starting rate' ),
		),
		$id
	);
	update_field( 'employment_type', 'FULL_TIME', $id );

	echo 'vacancy ' . ( $existing ? 'updated' : 'created' ) . ": {$title}\n";
}

/* --------------------------------------------------------------- Contact Us */

$contact = get_page_by_path( 'contact-us' );

if ( $contact ) {
	update_post_meta( $contact->ID, '_wp_page_template', 'page-templates/contact.php' );

	update_field( 'hero_title', 'Contact Us', $contact->ID );
	update_field( 'hero_image', tpph_media( '7-contact-us-header' ), $contact->ID );
	update_field(
		'contact_text',
		"For further information regarding your referral or admission, you should consult your doctor's rooms.\nShould you wish to contact us directly, you can contact us via:\nPHONE: (08) 6166 1000\nFAX: (08) 6313 6418\nMAIL: PO Box 785 Mount Lawley 6929\nEMAIL: reception@tpph.com.au\nThe nearest Emergency Department is:\nRoyal Perth Hospital\nVictoria Square\nPerth WA 6000\nTel.: 08 9224 2244",
		$contact->ID
	);
	update_field( 'closing_image', tpph_media( '7-contact-us-image-14' ), $contact->ID );

	echo "contact populated (#{$contact->ID})\n";
}
