<?php
/**
 * YHDR Builders child theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'YHDR_THEME_DIR', get_stylesheet_directory() );
define( 'YHDR_THEME_URI', get_stylesheet_directory_uri() );
define( 'YHDR_VERSION', '1.0.0' );

// Business info used across templates, hooks and JSON-LD.
define( 'YHDR_PHONE_DISPLAY', '+256 726 483 228' );
define( 'YHDR_PHONE_E164', '256726483228' );
define( 'YHDR_EMAIL', 'yhdrbuilderscolimited@gmail.com' );
define( 'YHDR_ADDRESS', 'P.O. Box 131842, Kampala, Uganda' );
define( 'YHDR_TWITTER_URL', 'https://x.com/YhdrBuilders' );

require YHDR_THEME_DIR . '/inc/helpers.php';
require YHDR_THEME_DIR . '/inc/setup.php';
require YHDR_THEME_DIR . '/inc/enqueue.php';
require YHDR_THEME_DIR . '/inc/template-tags.php';
require YHDR_THEME_DIR . '/inc/canvas-overrides.php';
require YHDR_THEME_DIR . '/inc/woocommerce-setup.php';
