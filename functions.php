<?php
/**
 * The Park Private Hospital — theme bootstrap.
 *
 * Everything lives in inc/. This file only defines constants and pulls the
 * modules in, so there is one obvious place to look for any given concern.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;

define( 'TPPH_VERSION', '1.0.0' );
define( 'TPPH_DIR', get_template_directory() );
define( 'TPPH_URI', get_template_directory_uri() );

require_once TPPH_DIR . '/inc/setup.php';
require_once TPPH_DIR . '/inc/enqueue.php';
