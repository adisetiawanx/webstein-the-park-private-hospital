<?php
/**
 * Home hero.
 *
 * Full-bleed photograph with a centre-aligned text column sitting over the left
 * of the frame. The image is the LCP element on the site's most visited page,
 * so it is eager and high priority — never lazy.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

$tpph_image   = (int) tpph_field( 'hero_image', get_the_ID(), 0 );
$tpph_eyebrow = tpph_field( 'hero_eyebrow' );
$tpph_heading = tpph_field( 'hero_heading' );
$tpph_button  = tpph_field( 'hero_button_url', get_the_ID(), array() );
$tpph_label   = tpph_field( 'hero_button_label' );

if ( ! $tpph_image && ! $tpph_heading ) {
	return;
}
?>

<section class="hero">
	<?php if ( $tpph_image ) : ?>
		<div class="hero__media">
			<?php
			tpph_image(
				$tpph_image,
				'tpph-hero',
				array(
					'eager' => true,
					'class' => 'hero__image',
					'sizes' => '100vw',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="hero__inner container">
		<div class="hero__content">
			<?php if ( $tpph_eyebrow ) : ?>
				<span class="eyebrow"><?php echo esc_html( $tpph_eyebrow ); ?></span>
			<?php endif; ?>

			<?php if ( $tpph_heading ) : ?>
				<h1 class="hero__heading"><?php echo esc_html( $tpph_heading ); ?></h1>
			<?php endif; ?>

			<?php if ( $tpph_label && ! empty( $tpph_button['url'] ) ) : ?>
				<a
					class="btn hero__cta"
					href="<?php echo esc_url( $tpph_button['url'] ); ?>"
					<?php echo ! empty( $tpph_button['target'] ) ? ' target="_blank" rel="noopener"' : ''; ?>
				>
					<?php echo esc_html( $tpph_label ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
