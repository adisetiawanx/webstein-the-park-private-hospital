<?php
/**
 * Placeholder for the four pages the design links to but never draws.
 *
 * Fees Charges & Insurance sits in the For Patients & Visitors dropdown;
 * Privacy Policy and Disclaimer sit in the footer of all thirteen artboards;
 * Make a Payment is the header button. All four are linked in the design and
 * none of them has a layout.
 *
 * A stub keeps those links live — a dead href is both a Lighthouse SEO finding
 * and, in a client review, reads as an unfinished build rather than as missing
 * copy.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="section">
	<div class="container container--narrow rich-text">
		<p class="lede">
			<?php esc_html_e( 'Content for this page is being finalised and will be published shortly.', 'tpph' ); ?>
		</p>
		<p>
			<?php
			printf(
				/* translators: %s: contact page link. */
				esc_html__( 'If you need this information now, please %s and our team will help.', 'tpph' ),
				sprintf(
					'<a href="%s">%s</a>',
					esc_url( home_url( '/contact-us/' ) ),
					esc_html__( 'contact us', 'tpph' )
				)
			);
			?>
		</p>
	</div>
</section>
