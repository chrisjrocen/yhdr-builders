<?php
/**
 * Custom `testimonial` single, rendered via the
 * blocksy:single:canvas:custom-output filter (see inc/canvas-overrides.php)
 * -- no dedicated single-testimonial.php template. Mainly exists for
 * permalink/SEO completeness since testimonials are primarily surfaced via
 * the Home carousel and the testimonials archive.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$archive_link = get_post_type_archive_link( 'testimonial' );
?>
<main id="yhdr-single-testimonial">
	<section class="page-header">
		<div class="container">
			<?php if ( $archive_link ) : ?>
				<p class="eyebrow"><a href="<?php echo esc_url( $archive_link ); ?>">&larr; <?php esc_html_e( 'All Client Stories', 'yhdr' ); ?></a></p>
			<?php endif; ?>
		</div>
	</section>
	<div class="container single-testimonial__content">
		<?php yhdr_render_testimonial_slide( yhdr_testimonial_fields_from_post( get_post() ) ); ?>
	</div>
</main>
