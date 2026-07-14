<?php

/**
 * Contact page header.
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<section class="page-header">
    <div class="container">
        <p class="eyebrow"><?php esc_html_e('Contact Us', 'yhdr'); ?></p>
        <h1><?php esc_html_e("Let's Talk About Your Build", 'yhdr'); ?></h1>
        <div class="page-header__intro">
            <p><?php esc_html_e('A free consultation costs nothing -- and could save you millions of shillings.', 'yhdr'); ?>
            </p>
        </div>
    </div>
    <?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--grey', 'wave-divider--bg-navy-mid'); ?>
</section>