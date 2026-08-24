<?php
/**
 * Template helpers.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read a field with a safe fallback when SCF is not active.
 *
 * Every template goes through this rather than calling get_field() directly, so
 * deactivating the plugin degrades the site to empty sections instead of fatal
 * errors on every page.
 *
 * @param string   $selector Field name.
 * @param int|null $post_id  Post ID, or null for the current post.
 * @param mixed    $fallback Value to return when the field is unavailable.
 * @return mixed
 */
function tpph_field( $selector, $post_id = null, $fallback = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $fallback;
	}

	$value = get_field( $selector, $post_id );

	return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
}

/**
 * Output a responsive image from an attachment ID.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size          Registered image size.
 * @param array  $args          eager: bool, class: string, sizes: string, alt: string.
 */
function tpph_image( $attachment_id, $size = 'tpph-content', $args = array() ) {
	$attachment_id = (int) $attachment_id;

	if ( ! $attachment_id ) {
		return;
	}

	$defaults = array(
		'eager' => false,
		'class' => '',
		'sizes' => '',
		'alt'   => null,
	);
	$args     = wp_parse_args( $args, $defaults );

	$attr = array( 'class' => $args['class'] );

	if ( $args['sizes'] ) {
		$attr['sizes'] = $args['sizes'];
	}

	if ( null !== $args['alt'] ) {
		$attr['alt'] = $args['alt'];
	}

	/*
	 * The LCP element must not be lazy-loaded and should be fetched ahead of
	 * everything else. Getting this wrong on the hero costs several Lighthouse
	 * points on its own.
	 */
	if ( $args['eager'] ) {
		$attr['loading']       = 'eager';
		$attr['fetchpriority'] = 'high';
		$attr['decoding']      = 'sync';
	} else {
		$attr['loading']  = 'lazy';
		$attr['decoding'] = 'async';
	}

	echo wp_get_attachment_image( $attachment_id, $size, false, $attr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Inline an SVG from the theme icon directory.
 *
 * Inlined rather than served through an img tag so the icons inherit
 * currentColor and do not each cost a request. These are design-system assets,
 * not content, which is why they live in the theme and not the Media Library.
 *
 * @param string $name  File name without extension.
 * @param string $class Optional class attribute.
 */
function tpph_icon( $name, $class = 'icon' ) {
	$name = sanitize_file_name( $name );
	$file = TPPH_DIR . '/assets/theme/icons/' . $name . '.svg';

	if ( ! file_exists( $file ) ) {
		return;
	}

	$svg = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	// Decorative by default. Templates that need a label supply one in markup.
	$svg = preg_replace(
		'/<svg\b/',
		'<svg class="' . esc_attr( $class ) . '" aria-hidden="true" focusable="false"',
		$svg,
		1
	);

	echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Resolve the hero image for a page, falling back to the shared heritage
 * building header used across the inner artboards.
 *
 * @param int|null $post_id Post ID.
 * @return int Attachment ID, or 0.
 */
function tpph_hero_image_id( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$hero    = (int) tpph_field( 'hero_image', $post_id, 0 );

	if ( $hero ) {
		return $hero;
	}

	if ( has_post_thumbnail( $post_id ) ) {
		return (int) get_post_thumbnail_id( $post_id );
	}

	return (int) get_option( 'tpph_default_hero', 0 );
}

/**
 * Escaped telephone link that keeps the human-readable formatting.
 *
 * @param string $number Display number, for example "(08) 6166 1000".
 * @return string
 */
function tpph_tel_link( $number ) {
	$href = preg_replace( '/[^0-9+]/', '', $number );

	return sprintf( '<a href="tel:%s">%s</a>', esc_attr( $href ), esc_html( $number ) );
}

/**
 * Site-wide contact details, defined once.
 *
 * These appear in the footer of all thirteen artboards, on Contact Us, and in
 * the schema graph. Keeping one source avoids the three drifting apart.
 *
 * @return array
 */
function tpph_contact_details() {
	return array(
		'name'     => 'The Park Private Hospital',
		'phone'    => '(08) 6166 1000',
		'fax'      => '(08) 6313 6418',
		'email'    => 'reception@tpph.com.au',
		'careers'  => 'careers@tpph.com.au',
		'street'   => '14 Alvan Street',
		'suburb'   => 'Mount Lawley',
		'state'    => 'WA',
		'postcode' => '6050',
		'postal'   => 'PO Box 785 Mount Lawley 6929',
		'lat'      => -31.9333,
		'lng'      => 115.8747,
	);
}

/**
 * Turn a plain textarea into paragraphs and bullet lists.
 *
 * The facility panels in the design mix prose with bulleted lists inside one
 * block of copy. Rather than hand editors a second field, or a rich-text editor
 * that would let them break the styling, a line beginning with a dash becomes a
 * list item and consecutive dashes group into one list.
 *
 * @param string $text Raw textarea value.
 * @return string Escaped HTML.
 */
function tpph_lines_to_html( $text ) {
	$lines   = preg_split( '/\r\n|\r|\n/', (string) $text );
	$html    = '';
	$in_list = false;

	foreach ( $lines as $line ) {
		$line = trim( $line );

		if ( '' === $line ) {
			continue;
		}

		$is_bullet = ( 0 === strpos( $line, '-' ) || 0 === strpos( $line, '•' ) );

		if ( $is_bullet ) {
			if ( ! $in_list ) {
				$html   .= '<ul>';
				$in_list = true;
			}

			$html .= '<li>' . esc_html( trim( ltrim( $line, '-• ' ) ) ) . '</li>';
			continue;
		}

		if ( $in_list ) {
			$html   .= '</ul>';
			$in_list = false;
		}

		$html .= '<p>' . esc_html( $line ) . '</p>';
	}

	if ( $in_list ) {
		$html .= '</ul>';
	}

	return $html;
}
