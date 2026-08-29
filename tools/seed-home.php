<?php
/**
 * Populate the home page from artboard `1. Home`.
 *
 * Copy is transcribed from the XD exactly, including the promo card that ends
 * mid-sentence ("designed to complement"). That truncation is in the design;
 * it is flagged for the client rather than invented around.
 */

/*
 * Run through wp-cli or `wp eval-file`, never over HTTP. A migration tool that
 * copies wp-content verbatim will put this on a public server, where without
 * this line it is a reachable endpoint that rewrites page content.
 */
defined( 'ABSPATH' ) || exit;

$media = get_option( 'tpph_media_map', array() );

function tpph_media( $slug ) {
	$map = get_option( 'tpph_media_map', array() );
	return isset( $map[ $slug ] ) ? (int) $map[ $slug ] : 0;
}

$home = get_page_by_path( 'home' );

if ( ! $home ) {
	echo "home page missing\n";
	return;
}

$id = $home->ID;

$fields = array(

	// --- Hero ---
	'hero_image'        => tpph_media( '1-home-header' ),
	'hero_eyebrow'      => 'Welcome to The Park Private Hospital',
	'hero_heading'      => 'Your boutique healthcare experience designed around your comfort, safety and well being.',
	'hero_button_label' => 'Patient Information',
	'hero_button_url'   => array(
		'title'  => 'Patient Information',
		'url'    => home_url( '/for-patients-visitors/' ),
		'target' => '',
	),

	// --- About ---
	'about_heading' => 'About Us',
	'about_text'    => "Nestled within the Mount Lawley Heritage Precinct, The Park Private Hospital has been purpose built to offer an exceptional standard of private healthcare in a calm, supportive and personalised environment. We provide a peaceful setting that promotes well being and recovery, supported by a modern facility and a dedicated team focused on your individual needs.",
	'about_button'  => array(
		'title'  => 'Contact Us',
		'url'    => home_url( '/contact-us/' ),
		'target' => '',
	),
	'about_image'   => tpph_media( '1-home-about-us' ),
	'statements'    => array(
		array(
			'title' => 'Our Vision',
			'text'  => 'To be the premier provider of boutique healthcare in Western Australia.',
		),
		array(
			'title' => 'Our Mission',
			'text'  => 'To provide our patients with optimal healthcare in a safe and personalised environment.',
		),
		array(
			'title' => 'Our Values',
			'text'  => 'Authenticity, Respect, Excellence, Compassion, Hospitality and Sustainability.',
		),
	),

	// --- Services ---
	'services_heading' => 'Our Services',
	'services_intro'   => 'The Park Private Hospital offers personalised care across a range of specialties:',
	'services'         => array(
		array( 'icon' => 'oral-maxillofacial-surgery', 'label' => 'Oral Maxillofacial Surgery' ),
		array( 'icon' => 'plastic-surgery',            'label' => 'Plastic Surgery' ),
		array( 'icon' => 'cosmetic-surgery',           'label' => 'Cosmetic Surgery' ),
		array( 'icon' => 'general-surgery',            'label' => 'General Surgery' ),
		array( 'icon' => 'surgical-podiatry',          'label' => 'Surgical Podiatry' ),
		array( 'icon' => 'sleep-studies',              'label' => 'Sleep Studies' ),
	),
	'services_button'  => array(
		'title'  => 'Learn More',
		'url'    => home_url( '/about/' ),
		'target' => '',
	),

	// --- Promo cards ---
	'promo_cards' => array(
		array(
			'title' => 'Patient and Visitor Information',
			'text'  => 'Explore our patient and visitor information for clear guidance on preparing for your procedure, navigating your visit and knowing what to expect throughout your time with us.',
			'link'  => array( 'title' => 'Learn More', 'url' => home_url( '/for-patients-visitors/' ), 'target' => '' ),
		),
		array(
			'title' => 'Become an Accredited Practitioner',
			'text'  => 'The Executive Team is currently accepting applications for credentialing from healthcare professionals seeking admitting rights to The Park Private Hospital and Walcott Street Surgical Centre. We offer a supportive, well coordinated environment designed to complement your clinical practice.',
			'link'  => array( 'title' => 'Learn More', 'url' => home_url( '/for-doctors/' ), 'target' => '' ),
		),
		array(
			'title' => 'Career Opportunities',
			'text'  => 'If you share our commitment to extraordinary service, personalised care and maintaining the boutique standard that sets us apart, we would love to hear from you. We are always looking for suitably qualified and experienced staff across all departments who are passionate about delivering exceptional care in a supportive and values driven environment.',
			'link'  => array( 'title' => 'Learn More', 'url' => home_url( '/careers/' ), 'target' => '' ),
		),
	),

	// --- Accreditations ---
	'accreditations' => array(
		array( 'icon' => 'accreditation-licensed',   'label' => 'Licensed Class A Private Hospital' ),
		array( 'icon' => 'accreditation-achs',       'label' => 'ACHS Accredited Health Service' ),
		array( 'icon' => 'accreditation-experience', 'label' => '95.5% Score on Patient Experience' ),
		array( 'icon' => 'accreditation-infection',  'label' => '0% Healthcare Associated Infection Rate' ),
	),

	// --- Testimonials ---
	'testimonials_heading' => 'Testimonials',
	'testimonials_image'   => tpph_media( '1-home-testimonials' ),
	'testimonials'         => array(
		array(
			'quote'  => 'The whole experience from start to finish was seamless. I was treated with respect and understanding. The Park Private Hospital is a hidden gem, a beautiful, serene building with staff to match.',
			'author' => 'Patient 2025',
		),
	),

	'show_map' => 1,
);

foreach ( $fields as $key => $value ) {
	update_field( $key, $value, $id );
	echo 'set  ' . $key . "\n";
}

// The shared fallback banner for inner pages.
update_option( 'tpph_default_hero', tpph_media( '3-for-patients-visitors-header' ) );
update_field( 'default_hero_image', tpph_media( '3-for-patients-visitors-header' ), 'option' );

echo "\nhome populated (#{$id})\n";
