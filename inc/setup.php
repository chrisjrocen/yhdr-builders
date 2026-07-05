<?php
/**
 * Basic child theme setup.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'yhdr_setup' );

function yhdr_setup() {
	load_child_theme_textdomain( 'yhdr', YHDR_THEME_DIR . '/languages' );
}
