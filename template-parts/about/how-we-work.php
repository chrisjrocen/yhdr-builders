<?php
/**
 * About "How We Work" process section.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = yhdr_get_field( 'how_we_work_eyebrow', get_the_ID(), __( 'How we work', 'yhdr' ) );

if ( ! yhdr_have_rows( 'how_we_work_cards' ) ) {
	return;
}
?>
<section class="how-we-work">
	<div class="container">
		<header class="section-header section-header--dark">
			<?php yhdr_eyebrow( $eyebrow ); ?>
		</header>
		<div class="how-we-work__grid">
			<?php
			while ( have_rows( 'how_we_work_cards' ) ) :
				the_row();
				$step = get_sub_field( 'how_we_work_values' );
				?>
				<div class="how-we-work__step">
					<span class="how-we-work__number"><?php echo esc_html( $step ? sprintf( '%02d', (int) $step ) : '' ); ?></span>
					<h3><?php echo esc_html( get_sub_field( 'how_we_work_title' ) ); ?></h3>
					<p><?php echo esc_html( get_sub_field( 'how_we_work_description' ) ); ?></p>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
</section>
