<?php
/**
 * Custom `service` archive, rendered via the
 * blocksy:posts-listing:canvas:custom-output filter (see
 * inc/canvas-overrides.php) -- no dedicated archive-service.php template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<main id="yhdr-services-archive">
	<section class="page-header">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e( 'What we do', 'yhdr' ); ?></p>
			<h1><?php post_type_archive_title(); ?></h1>
		</div>
		<?php yhdr_wave_divider( 'down', 'wave-divider--page-header' ); ?>
	</section>

	<section class="services services--archive">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="services__grid">
					<?php
					while ( have_posts() ) :
						the_post();
						yhdr_render_service_card( get_post() );
					endwhile;
					?>
				</div>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<p><?php esc_html_e( 'Our services will be listed here shortly.', 'yhdr' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>
