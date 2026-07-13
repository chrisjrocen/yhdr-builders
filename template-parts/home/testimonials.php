<?php

/**
 * Home testimonials carousel. Skipped entirely if no client stories exist
 * yet, rather than showing fabricated reviews.
 */

if (! defined('ABSPATH')) {
	exit;
}

if (! yhdr_have_rows('client_stories')) {
	return;
}

$eyebrow = yhdr_get_field('client_stories_eyebrow', get_the_ID(), __('Client stories', 'yhdr'));
$heading = yhdr_get_field('client_stories_heading', get_the_ID(), __('What our clients say', 'yhdr'));
$archive_link = get_post_type_archive_link('testimonial');
?>
<?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--grey', 'wave-divider--bg-navy-dark'); ?>
<section class="testimonials">
    <div class="container">
        <header class="section-header">
            <?php yhdr_eyebrow($eyebrow); ?>
            <h2><?php echo esc_html($heading); ?></h2>
        </header>

        <div class="testimonial-carousel">
            <div class="testimonial-carousel__track">
                <?php
				while (have_rows('client_stories')) :
					the_row();
					yhdr_render_testimonial_slide([
						'rating'  => get_sub_field('client_stories_rating'),
						'quote'   => get_sub_field('client_stories_quote'),
						'client'  => get_sub_field('client_stories_client'),
						'project' => get_sub_field('client_stories_project'),
					]);
				endwhile;
				?>
            </div>
            <div class="testimonial-carousel__controls">
                <button type="button" class="testimonial-carousel__btn testimonial-carousel__btn--prev"
                    aria-label="<?php esc_attr_e('Previous testimonial', 'yhdr'); ?>">&larr;</button>
                <button type="button" class="testimonial-carousel__btn testimonial-carousel__btn--next"
                    aria-label="<?php esc_attr_e('Next testimonial', 'yhdr'); ?>">&rarr;</button>
            </div>
        </div>

        <?php if ($archive_link) : ?>
        <div class="testimonials__cta">
            <a href="<?php echo esc_url($archive_link); ?>"><?php esc_html_e('Read more client stories', 'yhdr'); ?>
                &rarr;</a>
        </div>
        <?php endif; ?>
    </div>
</section>