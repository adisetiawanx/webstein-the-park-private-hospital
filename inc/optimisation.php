<?php
/**
 * Front-end weight reduction.
 *
 * The brief asks for 90+ Lighthouse on every category. A stock WordPress head
 * ships several things this site has no use for, and each one is a request or a
 * few KB of render-blocking CSS. Everything removed here is removed because the
 * design does not use it, not to shave numbers for their own sake.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

/**
 * Strip the emoji detection script and its inline styles.
 *
 * Roughly 10KB of JavaScript plus an inline style block, polyfilling emoji
 * rendering for browsers that have not needed it in years. No emoji appear
 * anywhere in this design.
 */
function tpph_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter( 'tiny_mce_plugins', 'tpph_remove_emoji_tinymce_plugin' );
}
add_action( 'init', 'tpph_disable_emojis' );

/**
 * Drop the emoji plugin from the classic editor.
 *
 * @param array $plugins TinyMCE plugins.
 * @return array
 */
function tpph_remove_emoji_tinymce_plugin( $plugins ) {
	return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
}

/**
 * Remove head links this site does not serve.
 */
function tpph_clean_head() {
	remove_action( 'wp_head', 'rsd_link' );                            // Really Simple Discovery.
	remove_action( 'wp_head', 'wp_generator' );                        // Version disclosure.
	remove_action( 'wp_head', 'wlwmanifest_link' );                    // Windows Live Writer.
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 ); // There is no blog.
	remove_action( 'wp_head', 'feed_links_extra', 3 );                 // There are no feeds.
}
add_action( 'init', 'tpph_clean_head' );

/**
 * Drop the oEmbed discovery script.
 *
 * There are no embeds in the design. The only iframe on the site is the map,
 * and that is injected by our own facade script.
 */
function tpph_disable_embeds() {
	wp_deregister_script( 'wp-embed' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}
add_action( 'wp_footer', 'tpph_disable_embeds' );

/**
 * Remove the core block library stylesheet on templates that render no blocks.
 *
 * Most pages render entirely from fields, so wp-block-library is dead weight
 * there. Pages whose content genuinely contains blocks keep it.
 */
function tpph_conditional_block_styles() {
	if ( is_admin() ) {
		return;
	}

	$post = get_post();

	if ( $post && has_blocks( $post->post_content ) ) {
		return;
	}

	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'tpph_conditional_block_styles', 100 );

/**
 * Add defer to our own scripts.
 *
 * None of them touch the DOM before it is parsed, so none of them need to block
 * rendering.
 *
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @return string
 */
function tpph_defer_scripts( $tag, $handle ) {
	$deferred = array( 'tpph-navigation', 'tpph-team', 'tpph-map' );

	if ( in_array( $handle, $deferred, true ) && false === strpos( $tag, ' defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'tpph_defer_scripts', 10, 2 );
