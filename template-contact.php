<?php

/**
 * Template Name: Contact
 */

get_header();
?>

<main id="yhdr-contact">
	<?php
	get_template_part('template-parts/contact/header');
	get_template_part('template-parts/contact/form-info');
	?>
</main>
<?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--navy-dark', 'wave-divider--bg-grey'); ?>
<?php
get_footer();
