<?php

/**
 * Template Name: About Us
 */

get_header();
?>

<main id="yhdr-about">
    <?php
	get_template_part('template-parts/about/header');
	get_template_part('template-parts/about/story');
	get_template_part('template-parts/about/mission-vision');
	get_template_part('template-parts/about/values');
	get_template_part('template-parts/about/how-we-work');
	get_template_part('template-parts/about/team');
	?>
</main>
<?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--navy-dark', 'wave-divider--bg-grey'); ?>
<?php
get_footer();