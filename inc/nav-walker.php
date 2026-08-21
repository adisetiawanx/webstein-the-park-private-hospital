<?php
/**
 * Primary navigation walker.
 *
 * The design opens submenus on hover, with the 300ms ease-out transition
 * recorded in the XD's interactions.json. Hover alone is not operable by
 * keyboard and does not exist on touch, so every parent item also gets a real
 * toggle button. CSS handles hover on desktop; the button handles keyboard
 * everywhere and tap below 1024px.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

/**
 * Walker that emits accessible dropdowns.
 */
class TPPH_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Open a submenu.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n{$indent}<ul class=\"nav__submenu\">\n";
	}

	/**
	 * Close a submenu.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "{$indent}</ul>\n";
	}

	/**
	 * Render one item.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @param int      $id     Menu item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		$item_classes = array( 0 === $depth ? 'nav__item' : 'nav__subitem' );

		if ( $has_children ) {
			$item_classes[] = 'nav__item--has-children';
		}

		if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-ancestor', $classes, true ) ) {
			$item_classes[] = 'is-current';
		}

		// Let editors flag the header call to action from the menu screen.
		if ( in_array( 'menu-item--cta', $classes, true ) ) {
			$item_classes[] = 'nav__item--cta';
		}

		$url    = ! empty( $item->url ) ? $item->url : '#';
		$title  = apply_filters( 'the_title', $item->title, $item->ID );
		$target = ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '" rel="noopener"' : '';

		$output .= '<li class="' . esc_attr( implode( ' ', $item_classes ) ) . '">';

		$link_class = 0 === $depth ? 'nav__link' : 'nav__sublink';

		$output .= sprintf(
			'<a class="%s" href="%s"%s>%s</a>',
			esc_attr( $link_class ),
			esc_url( $url ),
			$target,
			esc_html( $title )
		);

		if ( $has_children ) {
			/*
			 * The accessible name has to say which menu it opens. "Show
			 * submenu" repeated three times in a row tells a screen reader user
			 * nothing about where they are.
			 */
			$output .= sprintf(
				'<button class="nav__toggle" type="button" aria-expanded="false">'
					. '<span class="screen-reader-text">%s</span>'
					. '<svg class="nav__chevron" viewBox="0 0 12 8" aria-hidden="true" focusable="false">'
					. '<path d="M1 1.5 6 6.5l5-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'
					. '</svg>'
					. '</button>',
				/* translators: %s: menu item title. */
				esc_html( sprintf( __( 'Open the %s menu', 'tpph' ), $title ) )
			);
		}
	}

	/**
	 * Close one item.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}

/**
 * Render the primary menu, or a hard-coded fallback before the menu is created.
 *
 * The fallback exists so the header is never empty on a fresh install — it
 * mirrors the navigation drawn in the XD, including the two anchor links into
 * the Our Team page.
 */
function tpph_primary_menu() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav__list',
				'depth'          => 2,
				'walker'         => new TPPH_Nav_Walker(),
			)
		);

		return;
	}

	$fallback = array(
		array(
			'title' => 'Home',
			'url'   => home_url( '/' ),
		),
		array(
			'title'    => 'About',
			'url'      => home_url( '/about/' ),
			'children' => array(
				array(
					'title' => 'Our Executive Team',
					'url'   => home_url( '/about/our-team/#executive-team' ),
				),
				array(
					'title' => 'Our Doctors',
					'url'   => home_url( '/about/our-team/#our-doctors' ),
				),
			),
		),
		array(
			'title'    => 'For Patients &amp; Visitors',
			'url'      => home_url( '/for-patients-visitors/visitors/' ),
			'children' => array(
				array(
					'title' => 'Visitors',
					'url'   => home_url( '/for-patients-visitors/visitors/' ),
				),
				array(
					'title'  => 'Online Admission',
					'url'    => 'https://www.preadmit.com.au/Patient/parkprivatehospital',
					'target' => '_blank',
				),
				array(
					'title' => 'Fees, Charges &amp; Insurance',
					'url'   => home_url( '/for-patients-visitors/fees-charges-insurance/' ),
				),
				array(
					'title' => 'Preparing for your Admission',
					'url'   => home_url( '/for-patients-visitors/preparing-for-your-admission/' ),
				),
				array(
					'title' => 'Post Operative Care',
					'url'   => home_url( '/for-patients-visitors/post-operative-care/' ),
				),
				array(
					'title' => 'Patient Rights &amp; Responsibilities',
					'url'   => home_url( '/for-patients-visitors/patient-rights-responsibilities/' ),
				),
			),
		),
		array(
			'title'    => 'For Doctors',
			'url'      => home_url( '/for-doctors/credentialing/' ),
			'children' => array(
				array(
					'title' => 'Credentialing',
					'url'   => home_url( '/for-doctors/credentialing/' ),
				),
			),
		),
		array(
			'title'  => 'Online Admissions',
			'url'    => 'https://www.preadmit.com.au/Patient/parkprivatehospital',
			'target' => '_blank',
		),
		array(
			'title' => 'Safety &amp; Quality',
			'url'   => home_url( '/safety-and-quality/' ),
		),
		array(
			'title' => 'Careers',
			'url'   => home_url( '/careers/' ),
		),
		array(
			'title' => 'Contact Us',
			'url'   => home_url( '/contact-us/' ),
		),
	);

	echo '<ul class="nav__list">';

	foreach ( $fallback as $index => $item ) {
		$has_children = ! empty( $item['children'] );
		$target       = ! empty( $item['target'] ) ? ' target="_blank" rel="noopener"' : '';

		printf(
			'<li class="nav__item%s"><a class="nav__link" href="%s"%s>%s</a>',
			$has_children ? ' nav__item--has-children' : '',
			esc_url( $item['url'] ),
			$target, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_kses_post( $item['title'] )
		);

		if ( $has_children ) {
			printf(
				'<button class="nav__toggle" type="button" aria-expanded="false">'
					. '<span class="screen-reader-text">%s</span>'
					. '<svg class="nav__chevron" viewBox="0 0 12 8" aria-hidden="true" focusable="false">'
					. '<path d="M1 1.5 6 6.5l5-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'
					. '</svg></button>',
				/* translators: %s: menu item title. */
				esc_html( sprintf( __( 'Open the %s menu', 'tpph' ), wp_strip_all_tags( $item['title'] ) ) )
			);

			echo '<ul class="nav__submenu">';

			foreach ( $item['children'] as $child ) {
				printf(
					'<li class="nav__subitem"><a class="nav__sublink" href="%s"%s>%s</a></li>',
					esc_url( $child['url'] ),
					! empty( $child['target'] ) ? ' target="_blank" rel="noopener"' : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					wp_kses_post( $child['title'] )
				);
			}

			echo '</ul>';
		}

		echo '</li>';
	}

	echo '</ul>';
}
