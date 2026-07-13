<?php

/**
 * Home "Why Choose YHDR" section.
 */

if (! defined('ABSPATH')) {
	exit;
}

$eyebrow = yhdr_get_field('why_eyebrow', get_the_ID(), __('Why Choose YHDR', 'yhdr'));
$heading = yhdr_get_field('why_heading', get_the_ID(), __('Why Choose YHDR', 'yhdr'));
?>
<?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--navy-dark', 'wave-divider--bg-offwhite'); ?>
<section class="why-choose">
    <div class="container">
        <header class="section-header section-header--dark">
            <?php yhdr_eyebrow($eyebrow); ?>
            <h2><?php echo esc_html($heading); ?></h2>
        </header>

        <?php if (yhdr_have_rows('values')) : ?>
        <div class="why-choose__grid">
            <?php
				$i = 0;
				while (have_rows('values')) :
					the_row();
					$i++;
				?>
            <div class="why-choose__card">
                <span class="why-choose__number"><?php echo esc_html(sprintf('%02d', $i)); ?></span>
                <h3><?php echo esc_html(get_sub_field('why_title')); ?></h3>
                <p><?php echo esc_html(get_sub_field('why_description')); ?></p>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</section>