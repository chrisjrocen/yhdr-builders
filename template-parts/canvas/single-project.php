<?php
/**
 * Custom `project` single, rendered via the
 * blocksy:single:canvas:custom-output filter (see inc/canvas-overrides.php)
 * -- no dedicated single-project.php template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$archive_link = get_post_type_archive_link( 'project' );
$location     = yhdr_get_field( 'project_location', get_the_ID(), '' );
$tag          = yhdr_get_field( 'project_tag', get_the_ID(), '' );
?>
<main id="yhdr-single-project">
	<article <?php post_class( 'single-project' ); ?>>
		<section class="page-header">
			<div class="container">
				<?php if ( $archive_link ) : ?>
					<p class="eyebrow"><a href="<?php echo esc_url( $archive_link ); ?>">&larr; <?php esc_html_e( 'All Projects', 'yhdr' ); ?></a></p>
				<?php endif; ?>
				<h1><?php the_title(); ?></h1>
				<?php if ( $tag || $location ) : ?>
					<p class="single-project__meta">
						<?php if ( $tag ) : ?><span class="badge"><?php echo esc_html( $tag ); ?></span><?php endif; ?>
						<?php if ( $location ) : ?><span><?php echo esc_html( $location ); ?></span><?php endif; ?>
					</p>
				<?php endif; ?>
			</div>
			<?php yhdr_wave_divider( 'down', 'wave-divider--page-header' ); ?>
		</section>

		<div class="container single-project__content">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="single-project__media"><?php the_post_thumbnail( 'large' ); ?></div>
			<?php endif; ?>
			<div class="single-project__body">
				<?php the_content(); ?>
			</div>
		</div>
	</article>

	<?php get_template_part( 'template-parts/home/cta-band' ); ?>
</main>
