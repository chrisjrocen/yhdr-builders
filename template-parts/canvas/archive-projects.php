<?php
/**
 * Custom `project` archive, rendered via the
 * blocksy:posts-listing:canvas:custom-output filter (see
 * inc/canvas-overrides.php) -- no dedicated archive-project.php template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_terms( [
	'taxonomy'   => 'project_category',
	'hide_empty' => true,
] );
$has_categories = ! is_wp_error( $categories ) && ! empty( $categories );
?>
<main id="yhdr-projects-archive">
	<section class="page-header">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e( 'Our Work', 'yhdr' ); ?></p>
			<h1><?php post_type_archive_title(); ?></h1>
		</div>
	</section>

	<section class="projects projects--archive">
		<div class="container">
			<?php if ( $has_categories ) : ?>
				<div class="projects__filters" role="group" aria-label="<?php esc_attr_e( 'Filter projects by category', 'yhdr' ); ?>">
					<button type="button" class="projects__filter-btn is-active" data-filter="all"><?php esc_html_e( 'All', 'yhdr' ); ?></button>
					<?php foreach ( $categories as $category ) : ?>
						<button type="button" class="projects__filter-btn" data-filter="<?php echo esc_attr( $category->slug ); ?>"><?php echo esc_html( $category->name ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>
				<div class="projects__grid">
					<?php
					while ( have_posts() ) :
						the_post();
						yhdr_render_project_card( get_post() );
					endwhile;
					?>
				</div>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<p><?php esc_html_e( 'Our project gallery will be published here shortly.', 'yhdr' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>
