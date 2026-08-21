<?php
/**
 * Import the staged WebP images into the Media Library with alt text.
 *
 * Idempotent: an image already present under the same slug is skipped, so this
 * can be re-run after adding to the staging folder.
 */

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$staging  = WP_CONTENT_DIR . '/themes/the-park-private-hospital/.upload-staging';
$manifest = json_decode( file_get_contents( $staging . '/manifest.json' ), true );

if ( ! $manifest ) {
	echo "no manifest\n";
	return;
}

$created = 0;
$skipped = 0;
$map     = array();

foreach ( $manifest as $item ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'name'           => $item['slug'],
			'posts_per_page' => 1,
			'post_status'    => 'inherit',
			'fields'         => 'ids',
		)
	);

	if ( $existing ) {
		$map[ $item['slug'] ] = $existing[0];
		$skipped++;
		continue;
	}

	$id = media_handle_sideload(
		array(
			'name'     => basename( $item['file'] ),
			'tmp_name' => $item['file'],
		),
		0,
		null,
		array(
			'post_title' => $item['title'],
			'post_name'  => $item['slug'],
		)
	);

	if ( is_wp_error( $id ) ) {
		echo 'FAILED  ' . $item['slug'] . ': ' . $id->get_error_message() . "\n";
		continue;
	}

	update_post_meta( $id, '_wp_attachment_image_alt', $item['alt'] );

	$map[ $item['slug'] ] = $id;
	$created++;
}

update_option( 'tpph_media_map', $map, false );

echo "created {$created}, already present {$skipped}\n";
echo "attachment map stored in the tpph_media_map option for the content seeder\n";
