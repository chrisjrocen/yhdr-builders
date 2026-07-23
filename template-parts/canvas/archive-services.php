<?php

/**
 * Custom `service` archive, rendered via the
 * blocksy:posts-listing:canvas:custom-output filter (see
 * inc/canvas-overrides.php) -- no dedicated archive-service.php template.
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<main id="yhdr-services-archive">
    <section class="page-header">
        <div class="container">
            <p class="eyebrow"><?php esc_html_e('Our Services', 'yhdr'); ?></p>
            <h1><?php esc_html_e('Everything Your Build Needs', 'yhdr'); ?></h1>
            <div class="page-header__intro">
                <p><?php esc_html_e('From the first architectural sketch to the last coat of paint -- and the water beneath your land -- one team, one contract, one standard.', 'yhdr'); ?>
                </p>
            </div>
        </div>
        <?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--grey', 'wave-divider--bg-navy-mid'); ?>
    </section>

    <section class="services services--archive">
        <div class="container services__list">
            <?php if (have_posts()) : ?>
            <?php
                $i = 0;
                while (have_posts()) :
                    the_post();
                    yhdr_render_service_row(get_post(), $i);
                    $i++;
                endwhile;
                ?>
            <?php the_posts_pagination(); ?>
            <?php else : ?>
            <p><?php esc_html_e('Our services will be listed here shortly.', 'yhdr'); ?></p>
            <?php endif; ?>
        </div>
    </section>
    <section class="services-cta">

        <div class="container">
            <h2><?php esc_html_e('Not Sure Where to Start?', 'yhdr'); ?></h2>
            <p><?php esc_html_e('Tell us about your land and your budget. We\'ll advise honestly -- even if the honest answer is "not yet".', 'yhdr'); ?>
            </p>
            <div class="services-cta__actions" data-animate-group>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary" data-animate="fadeIn">
                    <?php esc_html_e('Get a Free Consultation', 'yhdr'); ?>
                </a>
                <a href="<?php echo yhdr_whatsapp_url(__('Hi YHDR Builders, I have a question about a service.', 'yhdr')); ?>"
                    class="btn btn-secondary" target="_blank" rel="noopener noreferrer" data-animate="fadeIn">
                    <?php esc_html_e('Chat on WhatsApp', 'yhdr'); ?>
                </a>
            </div>
        </div>
    </section>
    <?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--navy-dark', 'wave-divider--bg-grey'); ?>
</main>