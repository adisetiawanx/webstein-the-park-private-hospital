<?php
/**
 * Default page template.
 *
 * Banner, then the page's Gutenberg content. Pages with a field-driven layout
 * use one of the templates in page-templates/ instead; this is what the four
 * stub pages and any future plain-text page fall back to.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/sections/page-hero' );

		if ( trim( get_the_content() ) ) {
			?>
			<section class="section">
				<div class="container container--narrow rich-text">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		} else {
			get_template_part( 'template-parts/sections/awaiting-content' );
		}

	endwhile;
	?>
</main>

<?php
get_footer();
