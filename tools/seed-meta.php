<?php
/**
 * Seed Yoast meta descriptions.
 *
 * Lighthouse scores SEO 85 without these. They are editable in Yoast after
 * handover; what is here is a sensible starting point drawn from each page's
 * own content rather than boilerplate.
 *
 * Stub pages are deliberately left without one — writing a description for a
 * page that has no content yet would be inventing it.
 */

$descriptions = array(
	'home' => 'The Park Private Hospital is a boutique private hospital in Mount Lawley, Perth, offering oral and maxillofacial, plastic, cosmetic, general and podiatric surgery, and sleep studies.',
	'about' => 'A fully licensed and accredited Class A private hospital in the Mount Lawley Heritage Precinct, with two operating theatres, a day procedure unit and a nine bed ward.',
	'about/our-team' => 'Meet the executive team and the accredited surgeons, anaesthetists and podiatric surgeons practising at The Park Private Hospital in Mount Lawley.',
	'for-patients-visitors' => 'Visiting guidelines for The Park Private Hospital, including who may accompany a day patient, inpatient visiting hours, our smoke-free policy, and parking.',
	'for-patients-visitors/preparing-for-your-admission' => 'What to organise before your admission to The Park Private Hospital: paperwork, your pre-admission telephone call, fasting instructions and what to bring on the day.',
	'for-patients-visitors/post-operative-care' => 'Post-operative instructions for patients discharged from The Park Private Hospital, including recovery guidance and when to contact your surgeon.',
	'for-patients-visitors/patient-rights-responsibilities' => 'The rights you can expect when receiving care at The Park Private Hospital, and the responsibilities that support a safe experience for everyone.',
	'for-doctors' => 'Credentialing information for healthcare professionals seeking admitting rights at The Park Private Hospital and Walcott Street Surgical Centre.',
	'safety-and-quality' => 'Accreditation and licensing information for The Park Private Hospital, a licensed Class A private hospital and ACHS accredited health service.',
	'careers' => 'Current vacancies and career opportunities at The Park Private Hospital, a boutique private hospital in Mount Lawley, Perth.',
	'contact-us' => 'Contact The Park Private Hospital in Mount Lawley, Perth. Phone (08) 6166 1000, or find us at 14 Alvan Street, Mount Lawley WA 6050.',
);

foreach ( $descriptions as $path => $description ) {
	$page = get_page_by_path( $path );

	if ( ! $page ) {
		echo "missing: {$path}\n";
		continue;
	}

	update_post_meta( $page->ID, '_yoast_wpseo_metadesc', $description );
	echo 'meta set: ' . $path . ' (' . strlen( $description ) . " chars)\n";
}

// Yoast otherwise appends an empty separator, giving "The Park Private
// Hospital -" as the homepage title.
update_option( 'blogdescription', 'Boutique private hospital in Mount Lawley, Perth' );

echo "\ntagline set\n";
