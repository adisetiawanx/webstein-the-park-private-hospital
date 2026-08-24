<?php
/**
 * Person card — used for both the Executive Team and Our Doctors grids.
 *
 * Two faces: a portrait with a green name bar, and the biography. Clicking
 * swaps them in place, which is what the two Our Team artboards show.
 *
 * Click rather than hover: the biographies run to roughly 200 words, which is
 * unreadable in a hover, and hover does not exist on touch at all.
 *
 * Expects $args:
 *   name, role, qualifications, photo (attachment ID), biography, specialties
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

$tpph_name  = $args['name'] ?? '';
$tpph_role  = $args['role'] ?? '';
$tpph_quals = $args['qualifications'] ?? '';
$tpph_photo = (int) ( $args['photo'] ?? 0 );
$tpph_bio   = trim( (string) ( $args['biography'] ?? '' ) );
$tpph_terms = $args['specialties'] ?? array();

if ( ! $tpph_name ) {
	return;
}

$tpph_uid = 'person-' . sanitize_title( $tpph_name );
?>

<li
	class="person-card<?php echo $tpph_bio ? ' person-card--has-bio' : ''; ?>"
	<?php if ( $tpph_terms ) : ?>
		data-specialties="<?php echo esc_attr( implode( ' ', $tpph_terms ) ); ?>"
	<?php endif; ?>
>
	<div class="person-card__face">
		<div class="person-card__photo">
			<?php if ( $tpph_photo ) : ?>
				<?php
				tpph_image(
					$tpph_photo,
					'tpph-portrait',
					array(
						'sizes' => '(min-width: 1440px) 320px, (min-width: 768px) 33vw, 90vw',
						/* translators: %s: person name. */
						'alt'   => sprintf( __( '%s', 'tpph' ), $tpph_name ),
					)
				);
				?>
			<?php else : ?>
				<?php // No photo supplied yet. A flat placeholder is better than a broken frame. ?>
				<div class="person-card__photo-placeholder" aria-hidden="true"></div>
			<?php endif; ?>
		</div>

		<div class="person-card__meta">
			<h3 class="person-card__name"><?php echo esc_html( $tpph_name ); ?></h3>

			<?php if ( $tpph_quals ) : ?>
				<p class="person-card__quals"><?php echo esc_html( $tpph_quals ); ?></p>
			<?php endif; ?>

			<?php if ( $tpph_role ) : ?>
				<p class="person-card__role"><?php echo esc_html( $tpph_role ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $tpph_bio ) : ?>
			<button
				class="person-card__open"
				type="button"
				aria-expanded="false"
				aria-controls="<?php echo esc_attr( $tpph_uid ); ?>"
			>
				<span class="screen-reader-text">
					<?php
					/* translators: %s: person name. */
					printf( esc_html__( 'Read the biography of %s', 'tpph' ), esc_html( $tpph_name ) );
					?>
				</span>
			</button>
		<?php endif; ?>
	</div>

	<?php if ( $tpph_bio ) : ?>
		<div class="person-card__bio" id="<?php echo esc_attr( $tpph_uid ); ?>" hidden>
			<div class="person-card__bio-scroll">
				<h3 class="screen-reader-text"><?php echo esc_html( $tpph_name ); ?></h3>
				<?php echo wp_kses_post( wpautop( $tpph_bio ) ); ?>
			</div>

			<button class="person-card__close" type="button">
				<span class="screen-reader-text">
					<?php
					/* translators: %s: person name. */
					printf( esc_html__( 'Close the biography of %s', 'tpph' ), esc_html( $tpph_name ) );
					?>
				</span>
				<svg viewBox="0 0 14 14" aria-hidden="true" focusable="false" width="14" height="14">
					<path d="M1 1l12 12M13 1L1 13" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
				</svg>
			</button>
		</div>
	<?php endif; ?>
</li>
