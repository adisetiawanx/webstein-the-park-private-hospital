<?php
/**
 * Populate About — Our Hospital from artboard `2. About`.
 *
 * Copy is transcribed verbatim, including "a financially health business",
 * which is a typo in the design. It is flagged for the client rather than
 * silently corrected — the agreed scope is what the XD says.
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

$page = get_page_by_path( 'about' );

if ( ! $page ) {
	echo "about page missing\n";
	return;
}

$id = $page->ID;

update_post_meta( $id, '_wp_page_template', 'page-templates/about.php' );

$fields = array(
	'hero_image'   => tpph_media( '2-about-our-hospital' ),
	'hero_eyebrow' => 'About Us',
	'hero_title'   => 'Our Hospital',
	'hero_intro'   => "Located within the Mount Lawley Heritage Precinct, The Park Private Hospital brings together modern facilities, attentive staff and a calm, restorative atmosphere. From private rooms and fresh meals to coordinated admission and care processes, our purpose built hospital is designed to offer a boutique environment where patients feel supported, respected and well informed.\n\nOur team of experienced nurses, administrative and patient services staff, and credentialed practitioners share a commitment to personalised care, guided by our Vision, Mission and Values. Whether attending for a day procedure or an overnight stay, we aim to provide safe, well organised care that is centred around individual needs.",

	'services_image'   => tpph_media( '2-about-our-services' ),
	'services_heading' => 'Our Services',
	'services_intro'   => 'The Park Private Hospital provides a diverse and continually expanding range of surgical services, supported by an increasing number of Accredited Practitioners joining our facility. Current specialties include:',
	'services_list'    => array(
		array( 'label' => 'Oral Maxillofacial Surgery' ),
		array( 'label' => 'Plastic and Reconstructive Surgery' ),
		array( 'label' => 'Cosmetic Surgery' ),
		array( 'label' => 'General Surgery' ),
		array( 'label' => 'Surgical Podiatry' ),
		array( 'label' => 'Sleep Studies' ),
	),
	'services_note'    => 'In partnership with Walcott Street Surgical Centre and Specialist Oral & Maxillofacial Surgery, we also operate the Perth Private Facial Trauma Service, providing private, specialised care for patients with facial injuries.',

	'band_image' => tpph_media( '2-about-our-facilities' ),

	'facilities_heading' => 'Our Facilities',
	'facilities'         => array(
		array(
			'title' => 'The Park Private Hospital',
			'text'  => "As a fully Licensed and Accredited Class A private hospital, The Park Private Hospital operates in accordance with national standards for safety, clinical quality and governance.\nThe Hospital Includes:\n- Two modern Operating Theatres\n- 4 Bay Recovery / PACU area\n- 7 Bay Day Procedure Unit\n- 9 Bed Ward (including two beds dedicated for High Dependency Patients)\n- Free onsite parking\n- Onsite Pathology Collection Centre\n- Catering Facilities",
		),
		array(
			'title' => 'Walcott Street Surgical Centre',
			'text'  => "The Walcott Street Surgical Centre and The Park Private Hospital operate under a shared Governance Structure and Executive Team.\nOnly a five minute walk from The Park Private Hospital, Walcott Street Surgical Centre is a fully Licensed and Accredited Class B private day procedure facility, purpose built to cater for surgical procedures performed under intravenous (IV) sedation and local anaesthetic.\nWalcott Street Surgical Centre is located at:\n41 Walcott Street MOUNT LAWLEY WA 6050\nPh: 08 9328 3006\nFax: 08 9328 3007",
		),
	),
	'facilities_note'    => "Please Note: There is no Emergency Department at this Hospital.\nThe nearest Emergency Department is Royal Perth Hospital, Victoria Square PERTH WA 6000. Tel: 08 9224 2244.",

	'vmv_heading' => 'Vision, Mission and Values',
	'vmv_text'    => 'Our organisation is guided by a defined purpose and a consistent approach to delivering high quality care. The statements that follow outline what drives our decisions, how we work, and the standards we expect of ourselves. They help ensure our patients receive personalised, safe and reliable healthcare, and they keep our team aligned in how we deliver our services. Together, they provide a clear framework for who we are and how we operate.',
	'vmv_cards'   => array(
		array(
			'title' => 'Our Vision',
			'style' => 'paragraph',
			'text'  => 'To be the premier provider of boutique healthcare in Western Australia.',
		),
		array(
			'title' => 'Our Mission',
			'style' => 'paragraph',
			'text'  => 'To provide our patients with optimal healthcare in a safe and personalised environment.',
		),
		array(
			'title' => 'Our Values',
			'style' => 'paragraph',
			'text'  => 'Authenticity, Respect, Excellence, Compassion, Hospitality and Sustainability.',
		),
		array(
			'title' => 'Principles',
			'style' => 'bullets',
			'text'  => "A commitment to be a point of difference with extraordinary service.\nA commitment to be a point of difference with superior meals and patient support\nA commitment to being a financially health business.\nA commitment to service led by our medical practitioner's requirements.",
		),
	),

	'closing_image' => tpph_media( '2-about-vision' ),
);

foreach ( $fields as $key => $value ) {
	update_field( $key, $value, $id );
}

echo "about populated (#{$id}), template page-templates/about.php\n";
