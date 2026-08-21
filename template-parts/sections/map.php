<?php
/**
 * Map band.
 *
 * The map in the design is custom styled — cream base, green roads — which the
 * Embed API cannot do, so this needs the Maps JavaScript API. Loading that
 * eagerly costs 8 to 20 Lighthouse points on mobile, and the map sits below the
 * fold on both templates that use it.
 *
 * So: render a facade, and hydrate on IntersectionObserver when the visitor
 * scrolls near it. Get Directions works whether or not the API key is present,
 * which means the section is useful before the key arrives.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

$tpph_contact = tpph_contact_details();
$tpph_lat     = (float) tpph_field( 'map_lat', 'option', $tpph_contact['lat'] );
$tpph_lng     = (float) tpph_field( 'map_lng', 'option', $tpph_contact['lng'] );
$tpph_zoom    = (int) tpph_field( 'map_zoom', 'option', 14 );

$tpph_address = sprintf(
	'%s, %s %s %s',
	$tpph_contact['street'],
	$tpph_contact['suburb'],
	$tpph_contact['state'],
	$tpph_contact['postcode']
);

$tpph_directions = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( $tpph_contact['name'] . ', ' . $tpph_address );

// Present only when the key has been defined in wp-config.php.
$tpph_has_key = defined( 'TPPH_GOOGLE_MAPS_KEY' ) && TPPH_GOOGLE_MAPS_KEY;

if ( $tpph_has_key ) {
	wp_enqueue_script( 'tpph-map' );
}
?>

<section class="map-band" aria-label="<?php esc_attr_e( 'Location', 'tpph' ); ?>">
	<div
		class="map"
		<?php if ( $tpph_has_key ) : ?>
			data-map
			data-lat="<?php echo esc_attr( $tpph_lat ); ?>"
			data-lng="<?php echo esc_attr( $tpph_lng ); ?>"
			data-zoom="<?php echo esc_attr( $tpph_zoom ); ?>"
			data-title="<?php echo esc_attr( $tpph_contact['name'] ); ?>"
		<?php endif; ?>
	>
		<div class="map__facade" aria-hidden="true"></div>

		<a class="btn btn--sm map__directions" href="<?php echo esc_url( $tpph_directions ); ?>" target="_blank" rel="noopener">
			<?php esc_html_e( 'Get Directions', 'tpph' ); ?>
		</a>

		<p class="map__address">
			<span class="screen-reader-text"><?php esc_html_e( 'Hospital address:', 'tpph' ); ?></span>
			<?php echo esc_html( $tpph_contact['name'] . ', ' . $tpph_address ); ?>
		</p>
	</div>
</section>
