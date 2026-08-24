<?php
/**
 * Home - three promo cards.
 *
 * Patient and Visitor Information, Become an Accredited Practitioner, Career
 * Opportunities. Solid green cards with a ghost button.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

$tpph_cards = tpph_field( 'promo_cards', get_the_ID(), array() );

if ( ! $tpph_cards ) {
	return;
}
?>

<section class="section section--flush-bottom section--cream home-promos">
	<div class="container">
		<ul class="promo-cards">
			<?php foreach ( $tpph_cards as $tpph_card ) : ?>
				<li class="promo-card">
					<h3 class="promo-card__title"><?php echo esc_html( $tpph_card['title'] ); ?></h3>

					<?php if ( ! empty( $tpph_card['text'] ) ) : ?>
						<p class="promo-card__text"><?php echo esc_html( $tpph_card['text'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $tpph_card['link']['url'] ) ) : ?>
						<a class="btn btn--ghost promo-card__cta" href="<?php echo esc_url( $tpph_card['link']['url'] ); ?>">
							<?php
							echo esc_html(
								! empty( $tpph_card['link']['title'] )
									? $tpph_card['link']['title']
									: __( 'Learn More', 'tpph' )
							);
							?>
							<?php
							/*
							 * Three buttons reading "Learn More" in a row give a screen
							 * reader nothing to tell them apart, and Lighthouse flags it
							 * as non-descriptive link text. The card title supplies the
							 * destination without changing the visible label.
							 */
							?>
							<span class="screen-reader-text">
								<?php
								/* translators: %s: card title. */
								printf( esc_html__( 'about %s', 'tpph' ), esc_html( $tpph_card['title'] ) );
								?>
							</span>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
