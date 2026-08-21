<?php
/**
 * Home — About Us band.
 *
 * Text column on the left, three staggered green statement cards on the right,
 * then a wide photograph beneath. The stagger is decorative: it unwinds to a
 * plain stack below 768 where it would otherwise collide with the copy.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

$tpph_heading    = tpph_field( 'about_heading' );
$tpph_text       = tpph_field( 'about_text' );
$tpph_button     = tpph_field( 'about_button', get_the_ID(), array() );
$tpph_image      = (int) tpph_field( 'about_image', get_the_ID(), 0 );
$tpph_statements = tpph_field( 'statements', get_the_ID(), array() );

if ( ! $tpph_heading && ! $tpph_text && ! $tpph_statements ) {
	return;
}
?>

<section class="section section--cream home-about">
	<img
		class="section__ornament section__ornament--right"
		src="<?php echo esc_url( TPPH_URI . '/assets/theme/img/tree-ornament.webp' ); ?>"
		width="822"
		height="804"
		alt=""
		aria-hidden="true"
		loading="lazy"
		decoding="async"
	>

	<div class="container">
		<div class="home-about__grid">

			<div class="home-about__text">
				<?php if ( $tpph_heading ) : ?>
					<h2><?php echo esc_html( $tpph_heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $tpph_text ) : ?>
					<div class="home-about__body rich-text">
						<?php echo wp_kses_post( wpautop( $tpph_text ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $tpph_button['url'] ) ) : ?>
					<a class="btn btn--outline" href="<?php echo esc_url( $tpph_button['url'] ); ?>">
						<?php echo esc_html( ! empty( $tpph_button['title'] ) ? $tpph_button['title'] : __( 'Contact Us', 'tpph' ) ); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( $tpph_statements ) : ?>
				<ul class="statement-cards">
					<?php foreach ( $tpph_statements as $tpph_statement ) : ?>
						<li class="statement-card">
							<h3 class="statement-card__title"><?php echo esc_html( $tpph_statement['title'] ); ?></h3>
							<?php if ( ! empty( $tpph_statement['text'] ) ) : ?>
								<p class="statement-card__text"><?php echo esc_html( $tpph_statement['text'] ); ?></p>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

		</div>
	</div>

	<?php if ( $tpph_image ) : ?>
		<div class="home-about__figure container">
			<?php
			tpph_image(
				$tpph_image,
				'tpph-wide',
				array(
					'class' => 'home-about__image',
					'sizes' => '(min-width: 1440px) 1440px, 100vw',
				)
			);
			?>
		</div>
	<?php endif; ?>
</section>
