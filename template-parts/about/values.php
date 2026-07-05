<?php
/**
 * About "What We Stand For" values grid.
 *
 * NOTE: The repeater sub-field is named `what_we_stand_for_heading`, the
 * same name as a top-level field on this page (the section title). Inside
 * this loop we must always call get_sub_field(), never get_field(), to read
 * the correct value.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = yhdr_get_field( 'what_we_stand_for_heading', get_the_ID(), __( 'What we stand for', 'yhdr' ) );

if ( ! yhdr_have_rows( 'what_we_stand_for_values' ) ) {
	return;
}
?>
<section class="our-values">
	<div class="container">
		<header class="section-header">
			<h2><?php echo esc_html( $heading ); ?></h2>
		</header>
		<div class="our-values__grid">
			<?php
			while ( have_rows( 'what_we_stand_for_values' ) ) :
				the_row();
				$value_heading = get_sub_field( 'what_we_stand_for_heading' );
				$value_desc    = get_sub_field( 'what_we_stand_for_description' );
				?>
				<div class="our-values__item">
					<span class="badge-circle"><?php echo esc_html( mb_substr( $value_heading, 0, 1 ) ); ?></span>
					<h3><?php echo esc_html( $value_heading ); ?></h3>
					<p><?php echo esc_html( $value_desc ); ?></p>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
</section>
