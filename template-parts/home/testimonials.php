<?php
/**
 * Home - testimonials.
 *
 * Full-bleed photograph with a green wash and a quote centred over it. The
 * design shows one quote and no slider controls, so a single entry renders
 * static. A second entry turns the section into a fading slider, which is where
 * this always ends up.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

$tpph_heading = tpph_field( 'testimonials_heading' );
$tpph_image   = (int) tpph_field( 'testimonials_image', get_the_ID(), 0 );
$tpph_quotes  = tpph_field( 'testimonials', get_the_ID(), array() );

if ( ! $tpph_quotes ) {
	return;
}

$tpph_multiple = count( $tpph_quotes ) > 1;
?>

<section class="testimonials<?php echo $tpph_multiple ? ' testimonials--slider' : ''; ?>"
	<?php echo $tpph_multiple ? ' data-testimonial-slider' : ''; ?>>

	<?php if ( $tpph_image ) : ?>
		<div class="testimonials__media">
			<?php
			tpph_image(
				$tpph_image,
				'tpph-hero',
				array(
					'class' => 'testimonials__image',
					'sizes' => '100vw',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="testimonials__inner container">
		<?php if ( $tpph_heading ) : ?>
			<h2 class="testimonials__heading"><?php echo esc_html( $tpph_heading ); ?></h2>
		<?php endif; ?>

		<div class="testimonials__track">
			<?php foreach ( $tpph_quotes as $tpph_index => $tpph_quote ) : ?>
				<figure
					class="testimonial<?php echo 0 === $tpph_index ? ' is-active' : ''; ?>"
					<?php echo $tpph_multiple && 0 !== $tpph_index ? ' aria-hidden="true"' : ''; ?>
				>
					<blockquote class="testimonial__quote">
						<p>&ldquo;<?php echo esc_html( $tpph_quote['quote'] ); ?>&rdquo;</p>
					</blockquote>

					<?php if ( ! empty( $tpph_quote['author'] ) ) : ?>
						<figcaption class="testimonial__attribution">
							&ndash; <?php echo esc_html( $tpph_quote['author'] ); ?>
						</figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
