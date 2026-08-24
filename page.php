<?php
/**
 * Default page template.
 *
 * Banner, then whatever field-driven sections the page has, then its Gutenberg
 * content. Pages that are purely long-form text — Post Operative Care, the stub
 * pages — need nothing beyond this.
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

		/*
		 * Pages with their own field-driven layout provide a part named after
		 * their slug. Everything else falls through to the content block below.
		 */
		$tpph_slug = get_post_field( 'post_name', get_the_ID() );
		$tpph_part = locate_template( 'template-parts/pages/' . $tpph_slug . '.php' );

		if ( $tpph_part ) {
			get_template_part( 'template-parts/pages/' . $tpph_slug );
		}

		if ( trim( get_the_content() ) ) {
			?>
			<section class="section">
				<div class="container container--narrow rich-text">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		} elseif ( ! $tpph_part ) {
			get_template_part( 'template-parts/sections/awaiting-content' );
		}

	endwhile;
	?>
</main>

<?php
get_footer();
