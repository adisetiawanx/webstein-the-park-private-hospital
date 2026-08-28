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

	/*
	 * The artboards set some sections two-up: Preparing for your Admission runs
	 * the Telephone Call beside How long will I be in hospital, and On the day of
	 * Admission beside Preparing to go Home. Consecutive half-width sections that
	 * share a background are collected into one band here, so the template below
	 * walks a list of bands rather than a flat list of sections.
	 */
	$tpph_bands = array();

	foreach ( $tpph_sections as $tpph_section ) {
		$tpph_span = $tpph_section['span'] ?? 'full';
		$tpph_half = 'text' === ( $tpph_section['layout'] ?? 'text' ) && in_array( $tpph_span, array( 'left', 'right' ), true );

		$tpph_last = $tpph_bands ? $tpph_bands[ count( $tpph_bands ) - 1 ] : null;

		if ( $tpph_half && $tpph_last && 'split' === $tpph_last['kind']
			&& ( $tpph_last['background'] ?? 'white' ) === ( $tpph_section['background'] ?? 'white' ) ) {
			$tpph_bands[ count( $tpph_bands ) - 1 ]['parts'][] = $tpph_section;
			continue;
		}

		if ( $tpph_half ) {
			$tpph_bands[] = array(
				'kind'       => 'split',
				'background' => $tpph_section['background'] ?? 'white',
				'ornament'   => ! empty( $tpph_section['ornament'] ),
				'side'       => $tpph_section['ornament_side'] ?? 'left',
				'parts'      => array( $tpph_section ),
			);
			continue;
		}

		$tpph_bands[] = array( 'kind' => 'single', 'section' => $tpph_section );
	}
	?>

	<main id="main" class="site-main">
		<?php
		if ( ! $tpph_sections ) {
			get_template_part( 'template-parts/sections/awaiting-content' );
		}

		foreach ( $tpph_bands as $tpph_band_item ) :

			/* --------------------------------------------- Two columns of copy */
			if ( 'split' === $tpph_band_item['kind'] ) :
				$tpph_split_classes = 'section' . ( 'cream' === $tpph_band_item['background'] ? ' section--cream' : '' );
				?>
				<section class="<?php echo esc_attr( $tpph_split_classes ); ?>">
					<?php if ( $tpph_band_item['ornament'] ) : ?>
						<img class="section__ornament<?php echo 'right' === $tpph_band_item['side'] ? ' section__ornament--right' : ''; ?>"
							src="<?php echo esc_url( TPPH_URI . '/assets/theme/img/tree-ornament.webp' ); ?>"
							width="820" height="801" alt="" aria-hidden="true" loading="lazy" decoding="async">
					<?php endif; ?>

					<div class="container">
						<div class="split-band">
							<?php
							foreach ( array( 'left', 'right' ) as $tpph_col ) :
								$tpph_parts = array_filter(
									$tpph_band_item['parts'],
									static function ( $tpph_part ) use ( $tpph_col ) {
										return $tpph_col === ( $tpph_part['span'] ?? 'left' );
									}
								);

								if ( ! $tpph_parts ) {
									continue;
								}
								?>
								<div class="split-band__col split-band__col--<?php echo esc_attr( $tpph_col ); ?>">
									<?php foreach ( $tpph_parts as $tpph_part ) : ?>
										<div class="split-band__item">
											<?php if ( ! empty( $tpph_part['heading'] ) ) : ?>
												<h2 class="text-band__heading"><?php echo esc_html( $tpph_part['heading'] ); ?></h2>
											<?php endif; ?>

											<?php if ( ! empty( $tpph_part['text'] ) ) : ?>
												<div class="rich-text">
													<?php echo tpph_lines_to_html( $tpph_part['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
												</div>
											<?php endif; ?>

											<?php
											// Within a column the artboard always sets the
											// photograph beneath the copy, never beside it.
											$tpph_part_images = is_array( $tpph_part['images'] ?? null ) ? $tpph_part['images'] : array();

											foreach ( $tpph_part_images as $tpph_img ) :
												?>
												<div class="split-band__figure">
													<?php
													tpph_image(
														$tpph_img,
														'tpph-card',
														array( 'sizes' => '(min-width: 1024px) 40vw, 100vw' )
													);
													?>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</section>
				<?php
				continue;
			endif;

			$tpph_section = $tpph_band_item['section'];
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
				<?php
				// Which part of the photograph survives the crop. Read off the
				// artboard per band; 50 is dead centre, which most of them are.
				$tpph_focus = $tpph_section['focus'] ?? '';
				$tpph_focus = ( '' === $tpph_focus || null === $tpph_focus ) ? 50 : (float) $tpph_focus;
				$tpph_style = 50.0 === $tpph_focus ? '' : sprintf( ' style="--band-focus:%s%%"', esc_attr( rtrim( rtrim( number_format( $tpph_focus, 2, '.', '' ), '0' ), '.' ) ) );
				?>
				<div class="<?php echo esc_attr( $tpph_band_class ); ?>"<?php echo $tpph_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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
				$tpph_columns = is_array( $tpph_section['columns'] ?? null ) ? $tpph_section['columns'] : array();
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
			// ACF hands back false, not an empty array, for an untouched gallery —
			// and casting false to an array yields array( false ), which is not
			// empty. So test it rather than casting it.
			$tpph_images   = is_array( $tpph_section['images'] ?? null ) ? $tpph_section['images'] : array();
			$tpph_position = $tpph_section['media_position'] ?? 'right';
			$tpph_has_media = ! empty( $tpph_images );
			$tpph_media_w  = $tpph_section['media_width'] ?? 'half';
			$tpph_overhang = ! empty( $tpph_section['media_overhang'] );

			$tpph_band_classes = 'text-band';

			if ( $tpph_has_media ) {
				$tpph_band_classes .= ' text-band--with-media text-band--media-' . $tpph_position
					. ' text-band--media-w-' . $tpph_media_w
					. ( $tpph_overhang ? ' text-band--media-overhang' : '' );
			}
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
					<div class="<?php echo esc_attr( $tpph_band_classes ); ?>">

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
