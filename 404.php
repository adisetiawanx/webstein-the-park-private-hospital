<?php
/**
 * 404.
 *
 * Not in the design. WordPress needs one, and without it a mistyped URL falls
 * through to index.php and renders an empty page with no way back.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="site-main">
	<section class="section error-404">
		<div class="container container--narrow">
			<p class="eyebrow"><?php esc_html_e( 'Page not found', 'tpph' ); ?></p>

			<h1><?php esc_html_e( 'We could not find that page', 'tpph' ); ?></h1>

			<p class="lede error-404__text">
				<?php esc_html_e( 'The page you are looking for may have moved, or the address may have been mistyped.', 'tpph' ); ?>
			</p>

			<ul class="error-404__links">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'tpph' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/for-patients-visitors/' ) ); ?>"><?php esc_html_e( 'For Patients &amp; Visitors', 'tpph' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/about/our-team/' ) ); ?>"><?php esc_html_e( 'Our Team', 'tpph' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'tpph' ); ?></a></li>
			</ul>

			<p class="error-404__phone">
				<?php
				$tpph_contact = tpph_contact_details();
				printf(
					/* translators: %s: telephone link. */
					esc_html__( 'If you need help now, call us on %s.', 'tpph' ),
					tpph_tel_link( $tpph_contact['phone'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
				?>
			</p>
		</div>
	</section>
</main>

<?php
get_footer();
