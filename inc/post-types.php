<?php
/**
 * Content model: doctors, vacancies and specialties.
 *
 * Registered in the theme rather than a plugin, deliberately. The whole content
 * model is theme-owned so that deactivating a plugin cannot empty the Our Team
 * and Careers pages.
 *
 * Neither post type is publicly queryable. The design has no single-doctor or
 * single-vacancy page — biographies open inline on Our Team, and Apply Now is a
 * mailto — so exposing individual URLs would only publish a set of thin,
 * near-duplicate pages for Google to index. Flipping this on later is a
 * one-line change if the client ever asks for detail pages.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register post types and taxonomies.
 */
function tpph_register_content_model() {

	register_post_type(
		'doctor',
		array(
			'labels'              => array(
				'name'               => __( 'Doctors', 'tpph' ),
				'singular_name'      => __( 'Doctor', 'tpph' ),
				'add_new_item'       => __( 'Add Doctor', 'tpph' ),
				'edit_item'          => __( 'Edit Doctor', 'tpph' ),
				'new_item'           => __( 'New Doctor', 'tpph' ),
				'view_item'          => __( 'View Doctor', 'tpph' ),
				'search_items'       => __( 'Search Doctors', 'tpph' ),
				'not_found'          => __( 'No doctors yet', 'tpph' ),
				'all_items'          => __( 'All Doctors', 'tpph' ),
				'menu_name'          => __( 'Doctors', 'tpph' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 21,
			'menu_icon'           => 'dashicons-groups',
			// Page attributes gives editors the menu_order drag handle, which is
			// how the grid order on Our Team is controlled.
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
			'taxonomies'          => array( 'specialty' ),
		)
	);

	register_post_type(
		'vacancy',
		array(
			'labels'              => array(
				'name'          => __( 'Vacancies', 'tpph' ),
				'singular_name' => __( 'Vacancy', 'tpph' ),
				'add_new_item'  => __( 'Add Vacancy', 'tpph' ),
				'edit_item'     => __( 'Edit Vacancy', 'tpph' ),
				'new_item'      => __( 'New Vacancy', 'tpph' ),
				'search_items'  => __( 'Search Vacancies', 'tpph' ),
				'not_found'     => __( 'No vacancies yet', 'tpph' ),
				'all_items'     => __( 'All Vacancies', 'tpph' ),
				'menu_name'     => __( 'Vacancies', 'tpph' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 22,
			'menu_icon'           => 'dashicons-portfolio',
			'supports'            => array( 'title', 'page-attributes' ),
		)
	);

	register_taxonomy(
		'specialty',
		array( 'doctor' ),
		array(
			'labels'             => array(
				'name'          => __( 'Specialties', 'tpph' ),
				'singular_name' => __( 'Specialty', 'tpph' ),
				'add_new_item'  => __( 'Add Specialty', 'tpph' ),
				'edit_item'     => __( 'Edit Specialty', 'tpph' ),
				'all_items'     => __( 'Specialties', 'tpph' ),
				'menu_name'     => __( 'Specialties', 'tpph' ),
			),
			'public'             => false,
			'publicly_queryable' => false,
			'hierarchical'       => false,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		)
	);
}
add_action( 'init', 'tpph_register_content_model' );

/**
 * Seed the four specialty terms drawn as filter pills on Our Team.
 *
 * Runs once. Editors can rename, reorder or add to them afterwards without this
 * putting them back.
 */
function tpph_seed_specialties() {
	if ( get_option( 'tpph_specialties_seeded' ) ) {
		return;
	}

	$terms = array(
		'Anaesthetists',
		'Oral and Maxillofacial Surgeons',
		'Plastic Surgeons',
		'Podiatric Surgeon',
	);

	foreach ( $terms as $term ) {
		if ( ! term_exists( $term, 'specialty' ) ) {
			wp_insert_term( $term, 'specialty' );
		}
	}

	update_option( 'tpph_specialties_seeded', 1 );
}
add_action( 'init', 'tpph_seed_specialties', 20 );

/**
 * Order doctors and vacancies by menu_order in the admin list, so the drag
 * order editors set is the order they see.
 *
 * @param WP_Query $query Query.
 */
function tpph_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$post_type = $query->get( 'post_type' );

	if ( in_array( $post_type, array( 'doctor', 'vacancy' ), true ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'menu_order title' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'tpph_admin_order' );

/**
 * Fetch doctors or executives in display order.
 *
 * @param array $args Optional WP_Query overrides.
 * @return WP_Post[]
 */
function tpph_get_doctors( $args = array() ) {
	$defaults = array(
		'post_type'              => 'doctor',
		'posts_per_page'         => -1,
		'orderby'                => 'menu_order title',
		'order'                  => 'ASC',
		'no_found_rows'          => true,
		'update_post_term_cache' => true,
	);

	return get_posts( wp_parse_args( $args, $defaults ) );
}

/**
 * Fetch current vacancies in display order.
 *
 * @return WP_Post[]
 */
function tpph_get_vacancies() {
	return get_posts(
		array(
			'post_type'              => 'vacancy',
			'posts_per_page'         => -1,
			'orderby'                => 'menu_order title',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		)
	);
}
