<?php

/**
 * Template Name: Home Page
 */

get_header();
?>

<main id="yhdr-home">
    <?php
	get_template_part('template-parts/home/hero');
	get_template_part('template-parts/home/stats');
	get_template_part('template-parts/home/services');
	get_template_part('template-parts/home/featured-projects');
	get_template_part('template-parts/home/why-choose');
	get_template_part('template-parts/home/testimonials');
	get_template_part('template-parts/home/cta-band');
	?>
</main>
<?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--navy-dark', 'wave-divider--bg-white'); ?>
<?php
get_footer();