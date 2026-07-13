<?php
/**
 * Custom `testimonial` archive, rendered via the
 * blocksy:posts-listing:canvas:custom-output filter (see
 * inc/canvas-overrides.php) -- no dedicated archive-testimonial.php template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<main id="yhdr-testimonials-archive">
	<section class="page-header">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e( 'Client stories', 'yhdr' ); ?></p>
			<h1><?php post_type_archive_title(); ?></h1>
		</div>
	</section>

	<section class="testimonials testimonials--archive">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="testimonials__grid">
					<?php
					while ( have_posts() ) :
						the_post();
						yhdr_render_testimonial_slide( yhdr_testimonial_fields_from_post( get_post() ) );
					endwhile;
					?>
				</div>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<p><?php esc_html_e( 'Client stories will be published here shortly.', 'yhdr' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/home/cta-band' ); ?>
</main>
