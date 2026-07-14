<?php

/**
 * Home "What we do" services section.
 *
 * The 6 cards are not an ACF repeater on the Home Page field group -- they
 * come directly from the `service` CPT.
 */

if (! defined('ABSPATH')) {
	exit;
}

$eyebrow     = yhdr_get_field('services_eyebrow', get_the_ID(), __('What we do', 'yhdr'));
$heading     = yhdr_get_field('services_heading', get_the_ID(), __('From First Sketch to Final Handover', 'yhdr'));
$description = yhdr_get_field('services_description', get_the_ID(), '');

$services = new WP_Query([
	'post_type'              => 'service',
	'posts_per_page'         => 6,
	'no_found_rows'          => true,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
]);
?>
<?php yhdr_wave_divider('up', 'wave-divider--hero', 'wave-divider--grey', 'wave-divider--bg-white'); ?>
<section class="services">
    <div class="container">
        <header class="section-header">
            <?php yhdr_eyebrow($eyebrow); ?>
            <h2><?php echo esc_html($heading); ?></h2>
            <?php if ($description) : ?>
            <p class="section-header__description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </header>

        <?php if ($services->have_posts()) : ?>
        <div class="services__grid" data-animate-group>
            <?php
				while ($services->have_posts()) :
					$services->the_post();
					yhdr_render_service_card(get_post());
				endwhile;
				wp_reset_postdata();
				?>
        </div>
        <?php endif; ?>
    </div>
</section>