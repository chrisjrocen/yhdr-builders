<?php

/**
 * Home featured projects section.
 */

if (! defined('ABSPATH')) {
	exit;
}

$eyebrow = yhdr_get_field('fprojects_eyebrow', get_the_ID(), __('Featured Projects', 'yhdr'));
$heading = yhdr_get_field('fprojects_heading', get_the_ID(), __('Dirt Today, a Masterpiece Tomorrow', 'yhdr'));
$archive_link = get_post_type_archive_link('project');
?>
<?php yhdr_wave_divider('up', 'wave-divider--how-top', 'wave-divider--offwhite', 'wave-divider--bg-grey'); ?>
<section class="featured-projects">
    <div class="container">
        <header class="section-header">
            <?php yhdr_eyebrow($eyebrow); ?>
            <h2><?php echo esc_html($heading); ?></h2>
        </header>

        <?php if (yhdr_have_rows('fprojects_projects')) : ?>
        <div class="featured-projects__grid">
            <?php
				while (have_rows('fprojects_projects')) :
					the_row();
					$project = get_sub_field('featured_project');
					if (is_a($project, 'WP_Post')) :
						yhdr_render_project_card($project);
					endif;
				endwhile;
				?>
        </div>
        <?php endif; ?>

        <?php if ($archive_link) : ?>
        <div class="featured-projects__cta">
            <a href="<?php echo esc_url($archive_link); ?>"
                class="btn btn-secondary"><?php esc_html_e('View All Projects', 'yhdr'); ?></a>
        </div>
        <?php endif; ?>
    </div>
</section>