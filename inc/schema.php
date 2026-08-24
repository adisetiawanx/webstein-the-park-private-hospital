<?php
/**
 * JSON-LD structured data.
 *
 * Written here rather than left to Yoast so the graph survives the plugin being
 * deactivated — a hospital losing its Organization markup because someone
 * turned off an SEO plugin is not an acceptable failure mode.
 *
 * The corollary is that Yoast's own graph has to be switched off, or the page
 * emits two Organization entities that disagree with each other, which is worse
 * than having none. That is done below with a filter that is simply inert when
 * Yoast is absent.
 *
 * Yoast still handles titles, meta descriptions and Open Graph — the parts that
 * genuinely need an editing UI.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

/**
 * Turn off Yoast's JSON-LD graph.
 *
 * A no-op when Yoast is not installed, which is exactly what we want: nothing
 * breaks if the plugin goes away, and the theme graph keeps rendering.
 */
add_filter( 'wpseo_json_ld_output', '__return_false' );

/**
 * The hospital itself. Referenced by every other node.
 *
 * @return array
 */
function tpph_schema_organisation() {
	$contact = tpph_contact_details();
	$logo    = TPPH_URI . '/assets/theme/logos/tpph-logo.png';

	return array(
		'@type'             => array( 'Hospital', 'MedicalOrganization' ),
		'@id'               => home_url( '/#organization' ),
		'name'              => $contact['name'],
		'url'               => home_url( '/' ),
		'logo'              => array(
			'@type'  => 'ImageObject',
			'url'    => $logo,
			'width'  => 480,
			'height' => 178,
		),
		'image'             => $logo,
		'telephone'         => $contact['phone'],
		'faxNumber'         => $contact['fax'],
		'email'             => $contact['email'],
		'address'           => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $contact['street'],
			'addressLocality' => $contact['suburb'],
			'addressRegion'   => $contact['state'],
			'postalCode'      => $contact['postcode'],
			'addressCountry'  => 'AU',
		),
		'geo'               => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) tpph_field( 'map_lat', 'option', $contact['lat'] ),
			'longitude' => (float) tpph_field( 'map_lng', 'option', $contact['lng'] ),
		),
		'areaServed'        => array(
			'@type' => 'AdministrativeArea',
			'name'  => 'Western Australia',
		),
		'medicalSpecialty'  => array(
			'Surgical',
			'PlasticSurgery',
			'Podiatric',
			'Anesthesia',
		),
		/*
		 * Stated explicitly because the design says so twice, and because a
		 * search result implying otherwise could send someone to the wrong place
		 * in an emergency.
		 */
		'availableService'  => array(
			array(
				'@type' => 'MedicalProcedure',
				'name'  => 'Day surgery',
			),
			array(
				'@type' => 'MedicalProcedure',
				'name'  => 'Overnight surgical admission',
			),
		),
		'isAcceptingNewPatients' => true,
		'description'       => 'A boutique private hospital in the Mount Lawley Heritage Precinct, Perth, providing oral and maxillofacial, plastic, cosmetic, general and podiatric surgery, and sleep studies.',
	);
}

/**
 * Breadcrumb trail for the current page.
 *
 * @return array|null
 */
function tpph_schema_breadcrumb() {
	if ( is_front_page() ) {
		return null;
	}

	$items = array(
		array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => __( 'Home', 'tpph' ),
			'item'     => home_url( '/' ),
		),
	);

	$position = 2;

	if ( is_page() ) {
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );

		foreach ( $ancestors as $ancestor ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => get_the_title( $ancestor ),
				'item'     => get_permalink( $ancestor ),
			);
		}

		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => get_the_title(),
		);
	} elseif ( is_404() ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => __( 'Page not found', 'tpph' ),
		);
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => trailingslashit( home_url( add_query_arg( array() ) ) ) . '#breadcrumb',
		'itemListElement' => $items,
	);
}

/**
 * One Physician node per doctor. Only added on the page that lists them.
 *
 * @return array
 */
function tpph_schema_physicians() {
	$nodes = array();

	foreach ( tpph_get_doctors() as $doctor ) {
		$specialties = wp_get_post_terms( $doctor->ID, 'specialty', array( 'fields' => 'names' ) );
		$image       = get_the_post_thumbnail_url( $doctor->ID, 'tpph-portrait' );

		$node = array(
			'@type'       => 'Physician',
			'@id'         => get_permalink() . '#doctor-' . $doctor->post_name,
			'name'        => get_the_title( $doctor ),
			'jobTitle'    => tpph_field( 'role', $doctor->ID ),
			'memberOf'    => array( '@id' => home_url( '/#organization' ) ),
			'worksFor'    => array( '@id' => home_url( '/#organization' ) ),
			'description' => wp_trim_words( (string) tpph_field( 'biography', $doctor->ID ), 55 ),
		);

		if ( ! is_wp_error( $specialties ) && $specialties ) {
			$node['medicalSpecialty'] = $specialties;
		}

		if ( $image ) {
			$node['image'] = $image;
		}

		$nodes[] = $node;
	}

	return $nodes;
}

/**
 * One JobPosting per vacancy.
 *
 * Worth having: Google surfaces these in its Jobs experience, which is free
 * reach for a hospital that recruits continuously.
 *
 * @return array
 */
function tpph_schema_jobs() {
	$contact = tpph_contact_details();
	$nodes   = array();

	foreach ( tpph_get_vacancies() as $vacancy ) {
		$points      = tpph_field( 'points', $vacancy->ID, array() );
		$description = tpph_field( 'summary', $vacancy->ID, '' );

		if ( $points ) {
			$description .= ' ' . implode( '. ', wp_list_pluck( $points, 'text' ) ) . '.';
		}

		$closes = tpph_field( 'closes', $vacancy->ID );

		$node = array(
			'@type'              => 'JobPosting',
			'@id'                => get_permalink() . '#vacancy-' . $vacancy->post_name,
			'title'              => get_the_title( $vacancy ),
			'description'        => trim( $description ),
			'datePosted'         => get_the_date( 'Y-m-d', $vacancy ),
			'employmentType'     => tpph_field( 'employment_type', $vacancy->ID, 'FULL_TIME' ),
			'directApply'        => true,
			'hiringOrganization' => array( '@id' => home_url( '/#organization' ) ),
			'jobLocation'        => array(
				'@type'   => 'Place',
				'address' => array(
					'@type'           => 'PostalAddress',
					'streetAddress'   => $contact['street'],
					'addressLocality' => $contact['suburb'],
					'addressRegion'   => $contact['state'],
					'postalCode'      => $contact['postcode'],
					'addressCountry'  => 'AU',
				),
			),
		);

		/*
		 * Without validThrough, Google treats a posting as stale after 30 days
		 * and drops it. Default to 90 days out when the client has not set one.
		 */
		$node['validThrough'] = $closes
			? gmdate( 'c', strtotime( $closes ) )
			: gmdate( 'c', strtotime( get_the_date( 'Y-m-d', $vacancy ) . ' +90 days' ) );

		$nodes[] = $node;
	}

	return $nodes;
}

/**
 * Emit the graph.
 */
function tpph_output_schema() {
	if ( is_admin() || is_feed() ) {
		return;
	}

	$url   = is_front_page() ? home_url( '/' ) : get_permalink();
	$graph = array( tpph_schema_organisation() );

	$graph[] = array(
		'@type'      => 'WebSite',
		'@id'        => home_url( '/#website' ),
		'url'        => home_url( '/' ),
		'name'       => get_bloginfo( 'name' ),
		'publisher'  => array( '@id' => home_url( '/#organization' ) ),
		'inLanguage' => get_bloginfo( 'language' ),
	);

	$page = array(
		'@type'      => is_front_page() ? 'WebPage' : 'WebPage',
		'@id'        => $url . '#webpage',
		'url'        => $url,
		'name'       => wp_get_document_title(),
		'isPartOf'   => array( '@id' => home_url( '/#website' ) ),
		'about'      => array( '@id' => home_url( '/#organization' ) ),
		'inLanguage' => get_bloginfo( 'language' ),
	);

	if ( is_singular() ) {
		$page['datePublished'] = get_the_date( 'c' );
		$page['dateModified']  = get_the_modified_date( 'c' );

		$hero = tpph_hero_image_id();

		if ( $hero ) {
			$page['primaryImageOfPage'] = array(
				'@type' => 'ImageObject',
				'url'   => wp_get_attachment_image_url( $hero, 'tpph-hero' ),
			);
		}
	}

	$breadcrumb = tpph_schema_breadcrumb();

	if ( $breadcrumb ) {
		$page['breadcrumb'] = array( '@id' => $breadcrumb['@id'] );
	}

	$graph[] = $page;

	if ( $breadcrumb ) {
		$graph[] = $breadcrumb;
	}

	// Physicians and jobs only where they are actually presented.
	if ( is_page_template( 'page-templates/our-team.php' ) ) {
		$graph = array_merge( $graph, tpph_schema_physicians() );
	}

	if ( is_page_template( 'page-templates/careers.php' ) ) {
		$graph = array_merge( $graph, tpph_schema_jobs() );
	}

	$payload = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'tpph_output_schema', 20 );
