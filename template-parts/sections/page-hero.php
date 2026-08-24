<?php
/**
 * Inner page banner.
 *
 * A slim full-bleed photograph with the page title over the left. Every inner
 * artboard uses it. Our Hospital is the only one that also carries intro copy,
 * which is why the band grows with its content rather than being a fixed height.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

$tpph_hero    = tpph_hero_image_id();
$tpph_eyebrow = tpph_field( 'hero_eyebrow' );
$tpph_title   = tpph_field( 'hero_title', null, get_the_title() );
$tpph_intro   = tpph_field( 'hero_intro' );
$tpph_wide    = (bool) $tpph_intro;
?>

<section class="page-hero<?php echo $tpph_wide ? ' page-hero--tall' : ''; ?>">
	<?php if ( $tpph_hero ) : ?>
		<div class="page-hero__media">
			<?php
			tpph_image(
				$tpph_hero,
				'tpph-hero',
				array(
					'eager' => true,
					'class' => 'page-hero__image',
					'sizes' => '100vw',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="page-hero__inner container">
		<?php if ( $tpph_eyebrow ) : ?>
			<span class="eyebrow"><?php echo esc_html( $tpph_eyebrow ); ?></span>
		<?php endif; ?>

		<h1 class="page-hero__title"><?php echo esc_html( $tpph_title ); ?></h1>

		<?php if ( $tpph_intro ) : ?>
			<div class="page-hero__intro rich-text">
				<?php echo wp_kses_post( wpautop( $tpph_intro ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
