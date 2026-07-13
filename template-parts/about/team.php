<?php

/**
 * About "Our Team" section.
 *
 * NOTE: `team_section_intro` reuses the top-level field name
 * `how_we_work_description` (a distinct field from the How We Work
 * repeater's sub-field of the same name) -- call get_field() here, not
 * get_sub_field(), and not from inside the how-we-work loop.
 */

if (! defined('ABSPATH')) {
	exit;
}

$heading = yhdr_get_field('team_section_heading', get_the_ID(), __('The people behind the projects', 'yhdr'));
$intro   = yhdr_get_field('how_we_work_description', get_the_ID(), __('A hands-on team of designers, engineers and builders who treat your site like their own.', 'yhdr'));

$team = new WP_Query([
	'post_type'              => 'team_member',
	'posts_per_page'         => -1,
	'no_found_rows'          => true,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
]);
?>
<?php yhdr_wave_divider('up', 'wave-divider--how-top', 'wave-divider--grey', 'wave-divider--bg-navy-dark'); ?>
<section class="our-team">
    <div class="container">
        <header class="section-header">
            <h2><?php echo esc_html($heading); ?></h2>
            <?php if ($intro) : ?>
            <p class="section-header__description"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>
        </header>

        <?php if ($team->have_posts()) : ?>
        <div class="our-team__grid">
            <?php
				while ($team->have_posts()) :
					$team->the_post();
					yhdr_render_team_card(get_post());
				endwhile;
				wp_reset_postdata();
				?>
        </div>
        <?php endif; ?>
    </div>
</section>