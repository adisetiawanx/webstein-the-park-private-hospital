<?php
/**
 * Template Name: About — Our Hospital
 *
 * Artboard `2. About`.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/sections/page-hero' );

	$tpph_services_image = (int) tpph_field( 'services_image', null, 0 );
	$tpph_services_list  = tpph_field( 'services_list', null, array() );
	$tpph_band_image     = (int) tpph_field( 'band_image', null, 0 );
	$tpph_facilities     = tpph_field( 'facilities', null, array() );
	$tpph_vmv_cards      = tpph_field( 'vmv_cards', null, array() );
	$tpph_closing_image  = (int) tpph_field( 'closing_image', null, 0 );
	?>

	<main id="main" class="site-main">

		<?php if ( $tpph_services_list || $tpph_services_image ) : ?>
			<section class="section about-services">
				<div class="container">
					<div class="about-services__grid">

						<?php if ( $tpph_services_image ) : ?>
							<figure class="about-services__figure">
								<?php
								tpph_image(
									$tpph_services_image,
									'tpph-content',
									array( 'sizes' => '(min-width: 1024px) 30vw, 100vw' )
								);
								?>
							</figure>
						<?php endif; ?>

						<div class="about-services__body">
							<h2><?php echo esc_html( tpph_field( 'services_heading', null, __( 'Our Services', 'tpph' ) ) ); ?></h2>

							<?php if ( tpph_field( 'services_intro' ) ) : ?>
								<p class="about-services__intro"><?php echo esc_html( tpph_field( 'services_intro' ) ); ?></p>
							<?php endif; ?>

							<?php if ( $tpph_services_list ) : ?>
								<ul class="specialty-list">
									<?php foreach ( $tpph_services_list as $tpph_item ) : ?>
										<li class="specialty-list__item"><?php echo esc_html( $tpph_item['label'] ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( tpph_field( 'services_note' ) ) : ?>
								<p class="about-services__note"><?php echo esc_html( tpph_field( 'services_note' ) ); ?></p>
							<?php endif; ?>
						</div>

					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $tpph_band_image ) : ?>
			<div class="media-band">
				<?php
				tpph_image(
					$tpph_band_image,
					'tpph-wide',
					array(
						'class' => 'media-band__image',
						'sizes' => '100vw',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( $tpph_facilities ) : ?>
			<section class="section section--cream about-facilities">
				<img
					class="section__ornament"
					src="<?php echo esc_url( TPPH_URI . '/assets/theme/img/tree-ornament.webp' ); ?>"
					width="820" height="801" alt="" aria-hidden="true" loading="lazy" decoding="async"
				>

				<div class="container">
					<div class="section__head">
						<h2><?php echo esc_html( tpph_field( 'facilities_heading', null, __( 'Our Facilities', 'tpph' ) ) ); ?></h2>
					</div>

					<div class="facility-panels">
						<?php foreach ( $tpph_facilities as $tpph_facility ) : ?>
							<div class="facility-panel">
								<h3 class="facility-panel__title"><?php echo esc_html( $tpph_facility['title'] ); ?></h3>
								<?php echo tpph_lines_to_html( $tpph_facility['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ( tpph_field( 'facilities_note' ) ) : ?>
						<p class="about-facilities__note">
							<?php echo nl2br( esc_html( tpph_field( 'facilities_note' ) ) ); ?>
						</p>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $tpph_vmv_cards ) : ?>
			<section class="section about-vmv">
				<div class="container">
					<div class="about-vmv__grid">

						<div class="about-vmv__body">
							<h2><?php echo esc_html( tpph_field( 'vmv_heading', null, __( 'Vision, Mission and Values', 'tpph' ) ) ); ?></h2>

							<?php if ( tpph_field( 'vmv_text' ) ) : ?>
								<div class="about-vmv__text rich-text">
									<?php echo wp_kses_post( wpautop( tpph_field( 'vmv_text' ) ) ); ?>
								</div>
							<?php endif; ?>
						</div>

						<ul class="statement-cards statement-cards--four">
							<?php foreach ( $tpph_vmv_cards as $tpph_card ) : ?>
								<li class="statement-card">
									<h3 class="statement-card__title"><?php echo esc_html( $tpph_card['title'] ); ?></h3>
									<?php if ( ! empty( $tpph_card['text'] ) ) : ?>
										<?php if ( 'bullets' === ( $tpph_card['style'] ?? 'paragraph' ) ) : ?>
											<ul class="statement-card__list">
												<?php foreach ( preg_split( '/\r\n|\r|\n/', $tpph_card['text'] ) as $tpph_line ) : ?>
													<?php if ( trim( $tpph_line ) ) : ?>
														<li><?php echo esc_html( trim( $tpph_line, " \t-" ) ); ?></li>
													<?php endif; ?>
												<?php endforeach; ?>
											</ul>
										<?php else : ?>
											<p class="statement-card__text"><?php echo esc_html( $tpph_card['text'] ); ?></p>
										<?php endif; ?>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>

					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $tpph_closing_image ) : ?>
			<?php // Artboard runs this one at 9.75:1, thinner than the ward band above. ?>
			<div class="media-band media-band--short">
				<?php
				tpph_image(
					$tpph_closing_image,
					'tpph-wide',
					array(
						'class' => 'media-band__image',
						'sizes' => '100vw',
					)
				);
				?>
			</div>
		<?php endif; ?>

	</main>

	<?php
endwhile;

get_footer();
