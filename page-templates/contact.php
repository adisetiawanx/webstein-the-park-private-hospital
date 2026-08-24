<?php
/**
 * Template Name: Contact Us
 *
 * Artboard `7. Contact Us`: details on the left, map on the right with the
 * address card overlapping its top corner, then a full-width photograph.
 *
 * There is no contact form. The design does not have one, so the site does not
 * ship one.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/sections/page-hero' );

	$tpph_contact = tpph_contact_details();
	$tpph_closing = (int) tpph_field( 'closing_image', null, 0 );

	$tpph_card = tpph_field(
		'map_card',
		null,
		sprintf(
			"%s\n%s\n%s %s %s",
			$tpph_contact['name'],
			$tpph_contact['street'],
			$tpph_contact['suburb'],
			$tpph_contact['state'],
			$tpph_contact['postcode']
		)
	);
	?>

	<main id="main" class="site-main">

		<section class="section contact">
			<div class="container">
				<div class="contact__grid">

					<div class="contact__details rich-text">
						<?php echo tpph_lines_to_html( tpph_field( 'contact_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>

					<div class="contact__map">
						<?php get_template_part( 'template-parts/sections/map' ); ?>

						<div class="contact__card">
							<?php echo nl2br( esc_html( $tpph_card ) ); ?>
						</div>
					</div>

				</div>
			</div>
		</section>

		<?php if ( $tpph_closing ) : ?>
			<div class="media-band">
				<?php tpph_image( $tpph_closing, 'tpph-wide', array( 'class' => 'media-band__image', 'sizes' => '100vw' ) ); ?>
			</div>
		<?php endif; ?>

	</main>

	<?php
endwhile;

get_footer();
