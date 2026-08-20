<?php
/**
 * Fallback template.
 *
 * The site has no blog. This exists to satisfy the WordPress template
 * hierarchy and to render anything that slips past the more specific
 * templates.
 *
 * @package tpph
 */

get_header();
?>

<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/sections/page-hero' );
		?>
		<div class="section section--content">
			<div class="container container--narrow">
				<?php the_content(); ?>
			</div>
		</div>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
