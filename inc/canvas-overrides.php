<?php
/**
 * Renders custom archive/single markup for the `service`, `project` and
 * `testimonial` CPTs by short-circuiting Blocksy's
 * own template-parts/archive.php and template-parts/single.php via the
 * `blocksy:posts-listing:canvas:custom-output` and
 * `blocksy:single:canvas:custom-output` filters. No dedicated template files
 * (archive-*.php / single-*.php) are created for these -- everything runs
 * through Blocksy's normal template hierarchy, only the inner content
 * changes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'blocksy:posts-listing:canvas:custom-output', 'yhdr_archive_canvas_override' );

function yhdr_archive_canvas_override( $output ) {
	$part = null;

	if ( is_post_type_archive( 'service' ) ) {
		$part = 'archive-services';
	} elseif ( is_post_type_archive( 'project' ) ) {
		$part = 'archive-projects';
	} elseif ( is_post_type_archive( 'testimonial' ) ) {
		$part = 'archive-testimonials';
	}

	if ( ! $part ) {
		return $output;
	}

	ob_start();
	get_template_part( 'template-parts/canvas/' . $part );
	return ob_get_clean();
}

add_filter( 'blocksy:single:canvas:custom-output', 'yhdr_single_canvas_override' );

function yhdr_single_canvas_override( $output ) {
	$part = null;

	if ( is_singular( 'service' ) ) {
		$part = 'single-service';
	} elseif ( is_singular( 'project' ) ) {
		$part = 'single-project';
	} elseif ( is_singular( 'testimonial' ) ) {
		$part = 'single-testimonial';
	}

	if ( ! $part ) {
		return $output;
	}

	if ( have_posts() ) {
		the_post();
	}

	ob_start();
	get_template_part( 'template-parts/canvas/' . $part );
	return ob_get_clean();
}
