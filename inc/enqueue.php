<?php
/**
 * Style/script registration for the yhdr child theme.
 *
 * Blocksy enqueues its own compiled CSS/JS under the handles `ct-main-styles`
 * and `ct-scripts` (see wp-content/themes/blocksy/inc/css/static-files.php
 * and inc/manager.php) -- there is no `blocksy-style` handle to depend on, so
 * the child stylesheet chain depends on `ct-main-styles` directly.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'yhdr_enqueue_assets', 20 );

function yhdr_enqueue_assets() {
	$css_dir = YHDR_THEME_DIR . '/assets/css';
	$css_uri = YHDR_THEME_URI . '/assets/css';
	$js_dir  = YHDR_THEME_DIR . '/assets/js';
	$js_uri  = YHDR_THEME_URI . '/assets/js';

	// Google Fonts used throughout the design: Oswald (headings/labels) + Inter (body).
	wp_enqueue_style(
		'yhdr-fonts',
		'https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap',
		[],
		null
	);

	wp_enqueue_style( 'yhdr-style', get_stylesheet_uri(), [ 'ct-main-styles' ], YHDR_VERSION );

	// Vendored animate.css library -- provides the `animate__*` entrance
	// animation classes used site-wide by scroll-animations.js.
	wp_enqueue_style( 'yhdr-animate-vendor', $css_uri . '/vendor/animate.min.css', [ 'yhdr-style' ], yhdr_asset_version( $css_dir . '/vendor/animate.min.css' ) );

	$core_files = [ 'variables', 'base', 'layout', 'animations' ];
	$prev       = 'yhdr-animate-vendor';

	foreach ( $core_files as $file ) {
		$handle = 'yhdr-' . $file;
		wp_enqueue_style( $handle, $css_uri . '/' . $file . '.css', [ $prev ], yhdr_asset_version( $css_dir . '/' . $file . '.css' ) );
		$prev = $handle;
	}

	// Conditional, page/post-type specific stylesheets.
	$conditional_styles = [
		'home'         => is_page_template( 'template-home.php' ),
		'about'        => is_page_template( 'template-about.php' ),
		'services'     => is_post_type_archive( 'service' ) || is_singular( 'service' ),
		'projects'     => is_post_type_archive( 'project' ) || is_singular( 'project' ),
		'testimonials' => is_post_type_archive( 'testimonial' ) || is_singular( 'testimonial' ) || is_page_template( 'template-home.php' ),
		'contact'      => is_page_template( 'template-contact.php' ),
	];

	foreach ( $conditional_styles as $file => $should_load ) {
		if ( ! $should_load ) {
			continue;
		}

		wp_enqueue_style(
			'yhdr-' . $file,
			$css_uri . '/' . $file . '.css',
			[ 'yhdr-layout' ],
			yhdr_asset_version( $css_dir . '/' . $file . '.css' )
		);
	}

	// Site-wide supplemental behaviour (does not reimplement Blocksy's own nav JS).
	wp_enqueue_script( 'yhdr-nav', $js_uri . '/nav.js', [], yhdr_asset_version( $js_dir . '/nav.js' ), true );
	wp_script_add_data( 'yhdr-nav', 'strategy', 'defer' );

	// Site-wide scroll-triggered entrance animations + stat count-up.
	wp_enqueue_script( 'yhdr-scroll-animations', $js_uri . '/scroll-animations.js', [], yhdr_asset_version( $js_dir . '/scroll-animations.js' ), true );
	wp_script_add_data( 'yhdr-scroll-animations', 'strategy', 'defer' );

	wp_enqueue_script( 'yhdr-counter', $js_uri . '/counter.js', [], yhdr_asset_version( $js_dir . '/counter.js' ), true );
	wp_script_add_data( 'yhdr-counter', 'strategy', 'defer' );

	if ( $conditional_styles['testimonials'] ) {
		wp_enqueue_script( 'yhdr-testimonial-carousel', $js_uri . '/testimonial-carousel.js', [], yhdr_asset_version( $js_dir . '/testimonial-carousel.js' ), true );
		wp_script_add_data( 'yhdr-testimonial-carousel', 'strategy', 'defer' );
	}

	if ( $conditional_styles['projects'] ) {
		wp_enqueue_script( 'yhdr-project-filter', $js_uri . '/project-filter.js', [], yhdr_asset_version( $js_dir . '/project-filter.js' ), true );
		wp_script_add_data( 'yhdr-project-filter', 'strategy', 'defer' );
	}

}

/**
 * Cache-bust local assets using their filesystem mtime.
 *
 * @param string $path Absolute path to the asset.
 * @return string
 */
function yhdr_asset_version( $path ) {
	return file_exists( $path ) ? (string) filemtime( $path ) : YHDR_VERSION;
}
