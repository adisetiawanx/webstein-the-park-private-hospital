<?php
/**
 * Site footer.
 *
 * Identical across all thirteen artboards: logo left, then Legal and Contact
 * columns in the right half.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

$tpph_contact = tpph_contact_details();
?>

<footer class="site-footer">
	<div class="site-footer__inner container">

		<div class="site-footer__brand">
			<picture>
				<source srcset="<?php echo esc_url( TPPH_URI . '/assets/theme/logos/tpph-logo.webp' ); ?>" type="image/webp">
				<img
					src="<?php echo esc_url( TPPH_URI . '/assets/theme/logos/tpph-logo.png' ); ?>"
					width="480"
					height="178"
					alt="<?php esc_attr_e( 'The Park Private Hospital', 'tpph' ); ?>"
					loading="lazy"
					decoding="async"
				>
			</picture>
		</div>

		<div class="site-footer__col">
			<h2 class="site-footer__heading"><?php esc_html_e( 'Legal', 'tpph' ); ?></h2>

			<?php
			if ( has_nav_menu( 'legal' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'legal',
						'container'      => false,
						'menu_class'     => 'site-footer__list',
						'depth'          => 1,
					)
				);
			} else {
				?>
				<ul class="site-footer__list">
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'tpph' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>"><?php esc_html_e( 'Disclaimer', 'tpph' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</div>

		<div class="site-footer__col">
			<h2 class="site-footer__heading"><?php esc_html_e( 'Contact', 'tpph' ); ?></h2>

			<p class="site-footer__line">
				<?php
				printf(
					/* translators: %s: telephone link. */
					esc_html__( 'P: %s', 'tpph' ),
					tpph_tel_link( $tpph_contact['phone'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
				?>
			</p>

			<p class="site-footer__line">
				<?php
				printf(
					/* translators: %s: email link. */
					esc_html__( 'E: %s', 'tpph' ),
					sprintf(
						'<a href="mailto:%1$s">%1$s</a>',
						esc_attr( $tpph_contact['email'] )
					) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
				?>
			</p>

			<address class="site-footer__address">
				<?php echo esc_html( $tpph_contact['name'] ); ?><br>
				<?php echo esc_html( $tpph_contact['street'] ); ?><br>
				<?php
				printf(
					'%1$s %2$s, %3$s',
					esc_html( $tpph_contact['suburb'] ),
					esc_html( $tpph_contact['state'] ),
					esc_html( $tpph_contact['postcode'] )
				);
				?>
			</address>
		</div>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
