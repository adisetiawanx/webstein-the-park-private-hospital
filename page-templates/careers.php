<?php
/**
 * Template Name: Careers
 *
 * Artboard `6. Careers`.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/sections/page-hero' );

	$tpph_images    = tpph_field( 'intro_images', null, array() );
	$tpph_vacancies = tpph_get_vacancies();
	$tpph_contact   = tpph_contact_details();
	$tpph_closing   = (int) tpph_field( 'closing_image', null, 0 );
	?>

	<main id="main" class="site-main">

		<section class="section">
			<div class="container">
				<div class="text-band<?php echo $tpph_images ? ' text-band--with-media text-band--media-right' : ''; ?>">
					<div class="text-band__body">
						<h2 class="text-band__heading"><?php echo esc_html( tpph_field( 'intro_heading', null, __( 'Join our team', 'tpph' ) ) ); ?></h2>

						<?php if ( tpph_field( 'intro_text' ) ) : ?>
							<div class="rich-text">
								<?php echo tpph_lines_to_html( tpph_field( 'intro_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $tpph_images ) : ?>
						<div class="text-band__media text-band__media--<?php echo count( $tpph_images ) > 1 ? 'collage' : 'single'; ?>">
							<?php foreach ( $tpph_images as $tpph_img ) : ?>
								<?php tpph_image( $tpph_img, 'tpph-card', array( 'sizes' => '(min-width: 1024px) 30vw, 100vw' ) ); ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php if ( $tpph_vacancies ) : ?>
			<section class="section section--cream">
				<img class="section__ornament"
					src="<?php echo esc_url( TPPH_URI . '/assets/theme/img/tree-ornament.webp' ); ?>"
					width="822" height="804" alt="" aria-hidden="true" loading="lazy" decoding="async">

				<div class="container">
					<div class="section__head">
						<h2><?php echo esc_html( tpph_field( 'vacancies_heading', null, __( 'Current Vacancies', 'tpph' ) ) ); ?></h2>
					</div>

					<ul class="vacancy-cards">
						<?php
						foreach ( $tpph_vacancies as $tpph_vacancy ) :
							$tpph_points = tpph_field( 'points', $tpph_vacancy->ID, array() );
							$tpph_email  = tpph_field( 'apply_email', $tpph_vacancy->ID, $tpph_contact['careers'] );
							?>
							<li class="vacancy-card">
								<h3 class="vacancy-card__title"><?php echo esc_html( get_the_title( $tpph_vacancy ) ); ?></h3>

								<?php if ( tpph_field( 'summary', $tpph_vacancy->ID ) ) : ?>
									<p class="vacancy-card__summary"><?php echo esc_html( tpph_field( 'summary', $tpph_vacancy->ID ) ); ?></p>
								<?php endif; ?>

								<?php if ( $tpph_points ) : ?>
									<ul class="vacancy-card__points">
										<?php foreach ( $tpph_points as $tpph_point ) : ?>
											<li><?php echo esc_html( $tpph_point['text'] ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>

								<?php
								// mailto rather than a form: the design has no form anywhere, and
								// the artboard names this address directly.
								$tpph_subject = sprintf(
									/* translators: %s: job title. */
									__( 'Application: %s', 'tpph' ),
									get_the_title( $tpph_vacancy )
								);
								?>
								<a
									class="btn btn--ghost vacancy-card__cta"
									href="mailto:<?php echo esc_attr( $tpph_email ); ?>?subject=<?php echo rawurlencode( $tpph_subject ); ?>"
								>
									<?php esc_html_e( 'Apply Now', 'tpph' ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( tpph_field( 'register_text' ) ) : ?>
			<section class="section careers-register">
				<div class="container container--narrow">
					<div class="section__head">
						<h2><?php echo esc_html( tpph_field( 'register_heading', null, __( 'Register your interest', 'tpph' ) ) ); ?></h2>
					</div>

					<div class="careers-register__text rich-text">
						<?php
						// Linkify the careers address so it is actionable, not just printed.
						$tpph_text = esc_html( tpph_field( 'register_text' ) );
						$tpph_text = str_replace(
							esc_html( $tpph_contact['careers'] ),
							'<a href="mailto:' . esc_attr( $tpph_contact['careers'] ) . '">' . esc_html( $tpph_contact['careers'] ) . '</a>',
							$tpph_text
						);
						echo wpautop( $tpph_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $tpph_closing ) : ?>
			<div class="media-band">
				<?php tpph_image( $tpph_closing, 'tpph-wide', array( 'class' => 'media-band__image', 'sizes' => '100vw' ) ); ?>
			</div>
		<?php endif; ?>

	</main>

	<?php
endwhile;

get_footer();
