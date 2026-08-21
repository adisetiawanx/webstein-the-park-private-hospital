<?php
/**
 * Create the page tree, matching the artboards one to one.
 *
 * The section landing page IS the first content page — /for-patients-visitors/
 * is the Visitors page, /for-doctors/ is Credentialing — because that is what
 * the artboards show, and because inventing an empty parent page would mean
 * shipping a page nobody designed.
 *
 * Run through wp-cli eval-file. Idempotent: re-running updates nothing.
 */

$pages = array(
	array( 'title' => 'Home',                              'slug' => 'home',                              'parent' => '' ),
	array( 'title' => 'About',                             'slug' => 'about',                             'parent' => '' ),
	array( 'title' => 'Our Team',                          'slug' => 'our-team',                          'parent' => 'about' ),
	array( 'title' => 'For Patients & Visitors',           'slug' => 'for-patients-visitors',             'parent' => '' ),
	array( 'title' => 'Preparing for your Admission',      'slug' => 'preparing-for-your-admission',      'parent' => 'for-patients-visitors' ),
	array( 'title' => 'Post Operative Care',               'slug' => 'post-operative-care',               'parent' => 'for-patients-visitors' ),
	array( 'title' => 'Patient Rights & Responsibilities', 'slug' => 'patient-rights-responsibilities',   'parent' => 'for-patients-visitors' ),
	array( 'title' => 'Fees, Charges & Insurance',         'slug' => 'fees-charges-insurance',            'parent' => 'for-patients-visitors' ),
	array( 'title' => 'For Doctors',                       'slug' => 'for-doctors',                       'parent' => '' ),
	array( 'title' => 'Safety and Quality',                'slug' => 'safety-and-quality',                'parent' => '' ),
	array( 'title' => 'Careers',                           'slug' => 'careers',                           'parent' => '' ),
	array( 'title' => 'Contact Us',                        'slug' => 'contact-us',                        'parent' => '' ),
	array( 'title' => 'Make a Payment',                    'slug' => 'make-a-payment',                    'parent' => '' ),
	array( 'title' => 'Privacy Policy',                    'slug' => 'privacy-policy',                    'parent' => '' ),
	array( 'title' => 'Disclaimer',                        'slug' => 'disclaimer',                        'parent' => '' ),
);

$ids = array();

foreach ( $pages as $i => $page ) {
	$existing = get_page_by_path(
		$page['parent'] ? $page['parent'] . '/' . $page['slug'] : $page['slug']
	);

	if ( $existing ) {
		$ids[ $page['slug'] ] = $existing->ID;
		echo "exists   {$page['slug']} (#{$existing->ID})\n";
		continue;
	}

	$id = wp_insert_post(
		array(
			'post_title'   => $page['title'],
			'post_name'    => $page['slug'],
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_parent'  => $page['parent'] && isset( $ids[ $page['parent'] ] ) ? $ids[ $page['parent'] ] : 0,
			'menu_order'   => $i,
			'post_content' => '',
		)
	);

	$ids[ $page['slug'] ] = $id;
	echo "created  {$page['slug']} (#{$id})\n";
}

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $ids['home'] );
update_option( 'page_for_posts', 0 );

echo "\nfront page set to #{$ids['home']}\n";

flush_rewrite_rules( true );
