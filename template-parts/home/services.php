<?php
/**
 * Home - Our Services.
 *
 * Centred heading, one intro line, a row of six icon-and-label specialties,
 * then a button. Icons are inlined from the theme so they inherit currentColor.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

$tpph_heading  = tpph_field( 'services_heading' );
$tpph_intro    = tpph_field( 'services_intro' );
$tpph_services = tpph_field( 'services', get_the_ID(), array() );
$tpph_button   = tpph_field( 'services_button', get_the_ID(), array() );

if ( ! $tpph_heading && ! $tpph_services ) {
	return;
}
?>

<section class="section home-services">
	<div class="container">

		<div class="section__head">
			<?php if ( $tpph_heading ) : ?>
				<h2><?php echo esc_html( $tpph_heading ); ?></h2>
			<?php endif; ?>

			<?php if ( $tpph_intro ) : ?>
				<p><?php echo esc_html( $tpph_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $tpph_services ) : ?>
			<ul class="service-list">
				<?php foreach ( $tpph_services as $tpph_service ) : ?>
					<li class="service">
						<?php tpph_icon( $tpph_service['icon'], 'service__icon' ); ?>
						<span class="service__label"><?php echo esc_html( $tpph_service['label'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( ! empty( $tpph_button['url'] ) ) : ?>
			<p class="home-services__action">
				<a class="btn" href="<?php echo esc_url( $tpph_button['url'] ); ?>">
					<?php echo esc_html( ! empty( $tpph_button['title'] ) ? $tpph_button['title'] : __( 'Learn More', 'tpph' ) ); ?>
				</a>
			</p>
		<?php endif; ?>

	</div>
</section>
