<?php
/**
 * Home - accreditation strip.
 *
 * Four line icons with captions, sitting on the cream band that carries the
 * tree watermark.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

$tpph_items = tpph_field( 'accreditations', get_the_ID(), array() );

if ( ! $tpph_items ) {
	return;
}
?>

<section class="section section--tight section--cream home-accreditations" aria-label="<?php esc_attr_e( 'Accreditations', 'tpph' ); ?>">
	<img
		class="section__ornament"
		src="<?php echo esc_url( TPPH_URI . '/assets/theme/img/tree-ornament.webp' ); ?>"
		width="820"
		height="801"
		alt=""
		aria-hidden="true"
		loading="lazy"
		decoding="async"
	>

	<div class="container">
		<ul class="accreditation-list">
			<?php foreach ( $tpph_items as $tpph_item ) : ?>
				<li class="accreditation">
					<?php tpph_icon( $tpph_item['icon'], 'accreditation__icon' ); ?>
					<span class="accreditation__label"><?php echo esc_html( $tpph_item['label'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
