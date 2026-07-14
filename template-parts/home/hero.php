<?php

/**
 * Home hero section.
 */

if (! defined('ABSPATH')) {
	exit;
}

$eyebrow     = yhdr_get_field('hero_eyebrow', get_the_ID(), __('Design-Build Construction · Kampala, Uganda', 'yhdr'));
$heading     = yhdr_get_field('hero_heading', get_the_ID(), __('We Design Your Ideas. We Build Them.', 'yhdr'));
$description = yhdr_get_field('hero_description', get_the_ID(), __('Where cheap meets class -- transparent pricing, dependable timelines, and construction quality you can see.', 'yhdr'));
$primary_cta = yhdr_get_field('hero_primary_cta', get_the_ID(), []);
$secondary   = yhdr_get_field('hero_secondary_cta', get_the_ID(), []);
$main_image  = yhdr_image_field(yhdr_get_field('hero_main_image', get_the_ID(), []), __('A recently completed YHDR Builders project', 'yhdr'));
$minor_image = yhdr_image_field(yhdr_get_field('hero_minor_image', get_the_ID(), []), __('A YHDR Builders construction site', 'yhdr'));
?>
<section class="hero">
    <div class="container hero__inner">
        <div class="hero__content" data-animate="fadeInUp">
            <?php yhdr_eyebrow($eyebrow); ?>
            <h1 class="hero__heading"><?php echo esc_html($heading); ?></h1>
            <p class="hero__description"><?php echo esc_html($description); ?></p>
            <div class="hero__actions">
                <?php
				echo yhdr_link_button($primary_cta, 'btn btn-primary', __('Get a Free Quote', 'yhdr'));
				echo yhdr_link_button($secondary, 'btn btn-whatsapp', __('Chat on WhatsApp', 'yhdr'));
				?>
            </div>
        </div>
        <div class="hero__media">
            <img class="hero__image hero__image--main" data-animate="fadeIn" src="<?php echo esc_url($main_image['url']); ?>"
                alt="<?php echo esc_attr($main_image['alt']); ?>" />
            <img class="hero__image hero__image--minor" data-animate="fadeInUp" data-animate-delay="300ms" src="<?php echo esc_url($minor_image['url']); ?>"
                alt="<?php echo esc_attr($minor_image['alt']); ?>" />
        </div>
    </div>
    <?php yhdr_wave_divider('up', 'wave-divider--hero', 'wave-divider--white'); ?>
</section>