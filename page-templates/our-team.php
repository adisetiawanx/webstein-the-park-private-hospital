<?php
/**
 * Template Name: About — Our Team
 *
 * Artboards `6. Careers - 1` and `6. Careers - 5`, which are misnamed in the XD
 * — they are the two states of this page, not Careers.
 *
 * The section ids are the anchor targets the About dropdown scrolls to.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/sections/page-hero' );

	$tpph_executives = tpph_field( 'executives', null, array() );
	$tpph_doctors    = tpph_get_doctors();
	$tpph_filters    = (bool) tpph_field( 'show_filters', null, true );

	if ( $tpph_executives || $tpph_doctors ) {
		wp_enqueue_script( 'tpph-team' );
	}

	// Only offer filters that actually have doctors behind them.
	$tpph_terms = $tpph_filters
		? get_terms(
			array(
				'taxonomy'   => 'specialty',
				'hide_empty' => true,
			)
		)
		: array();
	?>

	<main id="main" class="site-main">

		<?php if ( $tpph_executives ) : ?>
			<section class="section section--cream team-section" id="executive-team">
				<img
					class="section__ornament"
					src="<?php echo esc_url( TPPH_URI . '/assets/theme/img/tree-ornament.webp' ); ?>"
					width="820" height="801" alt="" aria-hidden="true" loading="lazy" decoding="async"
				>

				<div class="container">
					<div class="section__head section__head--left">
						<h2><?php echo esc_html( tpph_field( 'exec_heading', null, __( 'Executive Team', 'tpph' ) ) ); ?></h2>

						<?php if ( tpph_field( 'exec_intro' ) ) : ?>
							<div class="team-section__intro rich-text">
								<?php echo wp_kses_post( wpautop( tpph_field( 'exec_intro' ) ) ); ?>
							</div>
						<?php endif; ?>
					</div>

					<ul class="person-grid">
						<?php
						foreach ( $tpph_executives as $tpph_person ) {
							get_template_part(
								'template-parts/cards/person',
								null,
								array(
									'name'           => $tpph_person['name'] ?? '',
									'role'           => $tpph_person['role'] ?? '',
									'qualifications' => $tpph_person['qualifications'] ?? '',
									'photo'          => $tpph_person['photo'] ?? 0,
									'biography'      => $tpph_person['biography'] ?? '',
								)
							);
						}
						?>
					</ul>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $tpph_doctors ) : ?>
			<section class="section section--cream section--flush-top team-section" id="our-doctors">
				<div class="container">
					<div class="section__head section__head--left">
						<h2><?php echo esc_html( tpph_field( 'doctors_heading', null, __( 'Our Doctors', 'tpph' ) ) ); ?></h2>

						<?php if ( tpph_field( 'doctors_intro' ) ) : ?>
							<div class="team-section__intro rich-text">
								<?php echo wp_kses_post( wpautop( tpph_field( 'doctors_intro' ) ) ); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $tpph_terms && ! is_wp_error( $tpph_terms ) ) : ?>
						<div class="specialty-filter" role="group" aria-label="<?php esc_attr_e( 'Filter doctors by specialty', 'tpph' ); ?>">
							<button class="btn btn--sm specialty-filter__btn is-active" type="button" data-filter="all" aria-pressed="true">
								<?php esc_html_e( 'All Doctors', 'tpph' ); ?>
							</button>
							<?php foreach ( $tpph_terms as $tpph_term ) : ?>
								<button
									class="btn btn--sm specialty-filter__btn"
									type="button"
									data-filter="<?php echo esc_attr( $tpph_term->slug ); ?>"
									aria-pressed="false"
								>
									<?php echo esc_html( $tpph_term->name ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<ul class="person-grid" data-person-grid>
						<?php
						foreach ( $tpph_doctors as $tpph_doctor ) {
							$tpph_slugs = wp_get_post_terms( $tpph_doctor->ID, 'specialty', array( 'fields' => 'slugs' ) );

							get_template_part(
								'template-parts/cards/person',
								null,
								array(
									'name'           => get_the_title( $tpph_doctor ),
									'role'           => tpph_field( 'role', $tpph_doctor->ID ),
									'qualifications' => tpph_field( 'qualifications', $tpph_doctor->ID ),
									'photo'          => get_post_thumbnail_id( $tpph_doctor->ID ),
									'biography'      => tpph_field( 'biography', $tpph_doctor->ID ),
									'specialties'    => is_wp_error( $tpph_slugs ) ? array() : $tpph_slugs,
								)
							);
						}
						?>
					</ul>

					<p class="person-grid__status" role="status" aria-live="polite"></p>
				</div>
			</section>
		<?php endif; ?>

	</main>

	<?php
endwhile;

get_footer();
