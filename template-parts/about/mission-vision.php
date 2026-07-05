<?php
/**
 * About "Mission & Vision" section.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mission = yhdr_get_field( 'our_mission', get_the_ID(), '' );
$vision  = yhdr_get_field( 'our_vision', get_the_ID(), '' );

if ( ! $mission && ! $vision ) {
	return;
}
?>
<section class="mission-vision">
	<div class="container mission-vision__grid">
		<?php if ( $mission ) : ?>
			<div class="mission-vision__card mission-vision__card--light">
				<h3><?php esc_html_e( 'Our Mission', 'yhdr' ); ?></h3>
				<p><?php echo nl2br( esc_html( $mission ) ); ?></p>
			</div>
		<?php endif; ?>
		<?php if ( $vision ) : ?>
			<div class="mission-vision__card mission-vision__card--dark">
				<h3><?php esc_html_e( 'Our Vision', 'yhdr' ); ?></h3>
				<p><?php echo nl2br( esc_html( $vision ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
