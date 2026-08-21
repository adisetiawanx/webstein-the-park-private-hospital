<?php
/**
 * Home page.
 *
 * Built from artboard `1. Home`. Each band is its own template part so the
 * order can be changed without unpicking one long file.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="site-main">
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/about' );
	get_template_part( 'template-parts/home/services' );
	get_template_part( 'template-parts/home/promo-cards' );
	get_template_part( 'template-parts/home/accreditations' );
	get_template_part( 'template-parts/home/testimonials' );

	if ( tpph_field( 'show_map', get_the_ID(), true ) ) {
		get_template_part( 'template-parts/sections/map' );
	}
	?>
</main>

<?php
get_footer();
