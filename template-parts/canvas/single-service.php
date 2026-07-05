<?php
/**
 * Custom `service` single, rendered via the
 * blocksy:single:canvas:custom-output filter (see inc/canvas-overrides.php)
 * -- no dedicated single-service.php template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$archive_link = get_post_type_archive_link( 'service' );
?>
<main id="yhdr-single-service">
	<article <?php post_class( 'single-service' ); ?>>
		<section class="page-header">
			<div class="container">
				<?php if ( $archive_link ) : ?>
					<p class="eyebrow"><a href="<?php echo esc_url( $archive_link ); ?>">&larr; <?php esc_html_e( 'All Services', 'yhdr' ); ?></a></p>
				<?php endif; ?>
				<h1><?php the_title(); ?></h1>
			</div>
			<?php yhdr_wave_divider( 'down', 'wave-divider--page-header' ); ?>
		</section>

		<div class="container single-service__content">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="single-service__media"><?php the_post_thumbnail( 'large' ); ?></div>
			<?php endif; ?>
			<div class="single-service__body">
				<?php the_content(); ?>
			</div>
		</div>
	</article>

	<?php get_template_part( 'template-parts/home/cta-band' ); ?>
</main>
