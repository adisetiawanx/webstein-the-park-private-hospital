<?php
/**
 * Template Name: Page Sections
 *
 * Covers six artboards with one template: Visitors, Preparing for your
 * Admission, Post Operative Care, Patient Rights & Responsibilities,
 * Credentialing and Safety and Quality.
 *
 * Every one of them is the same three shapes in a different order — a heading
 * and text with images alongside, a row of columns, or a full-width photograph
 * — so they share a template rather than getting six near-identical ones.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/sections/page-hero' );

	$tpph_sections = tpph_field( 'sections', null, array() );
	?>

	<main id="main" class="site-main">
		<?php
		if ( ! $tpph_sections ) {
			get_template_part( 'template-parts/sections/awaiting-content' );
		}

		foreach ( $tpph_sections as $tpph_section ) :
			$tpph_layout = $tpph_section['layout'] ?? 'text';
			$tpph_cream  = 'cream' === ( $tpph_section['background'] ?? 'white' );
			$tpph_classes = 'section' . ( $tpph_cream ? ' section--cream' : '' );

			/* ------------------------------------------------ Full-width photo */
			if ( 'media' === $tpph_layout ) :
				$tpph_image = (int) ( $tpph_section['image'] ?? 0 );

				if ( ! $tpph_image ) {
					continue;
				}
				?>
				<?php
				$tpph_band = $tpph_section['band_height'] ?? 'standard';
				$tpph_band_class = 'media-band';

				if ( 'short' === $tpph_band ) {
					$tpph_band_class .= ' media-band--short';
				} elseif ( 'tall' === $tpph_band ) {
					$tpph_band_class .= ' media-band--tall';
				}
				?>
				<div class="<?php echo esc_attr( $tpph_band_class ); ?>">
					<?php
					tpph_image(
						$tpph_image,
						'tpph-wide',
						array(
							'class' => 'media-band__image',
							'sizes' => '100vw',
						)
					);
					?>
				</div>
				<?php
				continue;
			endif;

			/* ------------------------------------------------------- Columns */
			if ( 'columns' === $tpph_layout ) :
				$tpph_columns = $tpph_section['columns'] ?? array();
				?>
				<section class="<?php echo esc_attr( $tpph_classes ); ?>">
					<?php if ( ! empty( $tpph_section['ornament'] ) ) : ?>
						<img class="section__ornament<?php echo 'right' === ( $tpph_section['ornament_side'] ?? 'left' ) ? ' section__ornament--right' : ''; ?>"
							src="<?php echo esc_url( TPPH_URI . '/assets/theme/img/tree-ornament.webp' ); ?>"
							width="820" height="801" alt="" aria-hidden="true" loading="lazy" decoding="async">
					<?php endif; ?>

					<div class="container">
						<?php if ( ! empty( $tpph_section['heading'] ) ) : ?>
							<div class="section__head section__head--left">
								<h2><?php echo esc_html( $tpph_section['heading'] ); ?></h2>
							</div>
						<?php endif; ?>

						<div class="column-band">
							<?php foreach ( $tpph_columns as $tpph_column ) : ?>
								<div class="column-band__item">
									<?php if ( ! empty( $tpph_column['image'] ) ) : ?>
										<div class="column-band__figure">
											<?php
											tpph_image(
												$tpph_column['image'],
												'tpph-card',
												array( 'sizes' => '(min-width: 1024px) 30vw, 100vw' )
											);
											?>
										</div>
									<?php endif; ?>

									<?php if ( ! empty( $tpph_column['title'] ) ) : ?>
										<h3 class="column-band__title"><?php echo esc_html( $tpph_column['title'] ); ?></h3>
									<?php endif; ?>

									<?php if ( ! empty( $tpph_column['text'] ) ) : ?>
										<div class="column-band__text rich-text">
											<?php echo tpph_lines_to_html( $tpph_column['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</section>
				<?php
				continue;
			endif;

			/* ---------------------------------------------- Heading and text */
			$tpph_images   = $tpph_section['images'] ?? array();
			$tpph_position = $tpph_section['media_position'] ?? 'right';
			$tpph_has_media = ! empty( $tpph_images );
			?>
			<section class="<?php echo esc_attr( $tpph_classes ); ?>">
				<?php if ( ! empty( $tpph_section['ornament'] ) ) : ?>
					<img class="section__ornament<?php echo 'right' === ( $tpph_section['ornament_side'] ?? 'left' ) ? ' section__ornament--right' : ''; ?>"
						src="<?php echo esc_url( TPPH_URI . '/assets/theme/img/tree-ornament.webp' ); ?>"
						width="820" height="801" alt="" aria-hidden="true" loading="lazy" decoding="async">
				<?php endif; ?>

				<div class="container">
					<?php
					// Safety and Quality sets its text on a white card over the cream
					// band. Nothing else in the design does.
					$tpph_panel = ! empty( $tpph_section['panel'] );

					if ( $tpph_panel ) {
						echo '<div class="text-panel">';
					}
					?>
					<div class="text-band<?php echo $tpph_has_media ? ' text-band--with-media text-band--media-' . esc_attr( $tpph_position ) : ''; ?>">

						<div class="text-band__body">
							<?php if ( ! empty( $tpph_section['heading'] ) ) : ?>
								<h2 class="text-band__heading"><?php echo esc_html( $tpph_section['heading'] ); ?></h2>
							<?php endif; ?>

							<?php if ( ! empty( $tpph_section['text'] ) ) : ?>
								<div class="rich-text">
									<?php echo tpph_lines_to_html( $tpph_section['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( $tpph_has_media ) : ?>
							<div class="text-band__media text-band__media--<?php echo count( $tpph_images ) > 1 ? 'collage' : 'single'; ?>">
								<?php foreach ( $tpph_images as $tpph_img ) : ?>
									<?php
									tpph_image(
										$tpph_img,
										'tpph-card',
										array( 'sizes' => '(min-width: 1024px) 30vw, 100vw' )
									);
									?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

					</div>
					<?php
					if ( $tpph_panel ) {
						echo '</div>';
					}
					?>
				</div>
			</section>
			<?php
		endforeach;
		?>
	</main>

	<?php
endwhile;

get_footer();
