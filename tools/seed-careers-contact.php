<?php
/**
 * Populate Careers and Contact Us, and create the vacancies.
 *
 * The Careers intro and the three vacancy cards were lorem ipsum in the design.
 * The client supplied the real intro and three roles on 10 September 2026, so
 * both are real copy now. The roles are advertised on Seek rather than filled
 * in here, which is why they carry a link and no description.
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

/* ------------------------------------------------------------------ Careers */

$careers = get_page_by_path( 'careers' );

if ( $careers ) {
	update_post_meta( $careers->ID, '_wp_page_template', 'page-templates/careers.php' );

	update_field( 'hero_title', 'Careers', $careers->ID );
	update_field( 'hero_image', tpph_media( '5-careers-header' ), $careers->ID );

	update_field( 'intro_heading', 'Join our team', $careers->ID );
	update_field(
		'intro_text',
		"At The Park Private Hospital, we believe outstanding patient care begins with outstanding people. Guided by our values of Authenticity, Respect, Excellence, Compassion, Hospitality and Sustainability, we are proud to foster a supportive and professional workplace where every team member contributes to the exceptional care we provide.\nWe are always interested in hearing from passionate healthcare professionals and support staff who share our commitment to delivering safe, personalised care in a welcoming boutique hospital environment.",
		$careers->ID
	);
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

/*
 * Titles are the ones Seek advertises, not the shorthand in the client's email,
 * so the heading a candidate reads here matches the page they land on. No
 * summary or detail lines came with them: the card degrades to title plus
 * button, which is why those fields are cleared rather than left behind.
 *
 * CSSD is a draft. The client sent it knowing it had lapsed ("this isn't
 * current, but can be used also"), and a live card whose Apply button lands on
 * an expired Seek listing is worse than no card. Publishing it is one click
 * when the role reopens.
 */
$vacancies = array(
	array(
		'title'  => 'Registered Nurse Night Duty',
		'url'    => 'https://au.seek.com/job/94013790',
		'type'   => 'PART_TIME',
		'status' => 'publish',
	),
	array(
		'title'  => 'Registered Nurse',
		'url'    => 'https://au.seek.com/job/93832320',
		'type'   => 'PART_TIME',
		'status' => 'publish',
	),
	array(
		'title'  => 'CSSD Permanent Part Time',
		'url'    => 'https://au.seek.com/expiredjob/91528416',
		'type'   => 'PART_TIME',
		'status' => 'draft',
	),
);

foreach ( $vacancies as $order => $vacancy ) {
	$existing = get_posts(
		array(
			'post_type'      => 'vacancy',
			'title'          => $vacancy['title'],
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
				'post_title'  => $vacancy['title'],
				'post_status' => $vacancy['status'],
			)
		);

	wp_update_post(
		array(
			'ID'          => $id,
			'menu_order'  => $order + 1,
			'post_status' => $vacancy['status'],
		)
	);

	update_field( 'summary', '', $id );
	update_field( 'points', array(), $id );
	update_field( 'apply_url', $vacancy['url'], $id );
	update_field( 'employment_type', $vacancy['type'], $id );

	echo 'vacancy ' . ( $existing ? 'updated' : 'created' ) . ": {$vacancy['title']} ({$vacancy['status']})\n";
}

/*
 * Retire the artboard placeholders. Scoped to the three titles the seed itself
 * created, so a vacancy the client adds by hand is never touched.
 */
foreach ( array( 'Job One', 'Job Two', 'Job Three' ) as $placeholder ) {
	foreach (
		get_posts(
			array(
				'post_type'      => 'vacancy',
				'title'          => $placeholder,
				'posts_per_page' => -1,
				'post_status'    => 'any',
				'fields'         => 'ids',
			)
		) as $stale
	) {
		wp_delete_post( $stale, true );
		echo "vacancy removed: {$placeholder}\n";
	}
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
