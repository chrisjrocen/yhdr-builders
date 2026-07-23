<?php
/**
 * Home bottom CTA band.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading     = yhdr_get_field( 'bottom_cta_heading', get_the_ID(), __( 'Have Land? Have a Dream? Let\'s Build It.', 'yhdr' ) );
$description = yhdr_get_field( 'bottom_cta_description', get_the_ID(), __( 'Tell us about your project and get a free, no-obligation quote within 48 hours.', 'yhdr' ) );
$primary_cta = yhdr_get_field( 'bottom_cta_primary_cta', get_the_ID(), [ 'url' => home_url( '/contact/' ) ] );
$secondary   = yhdr_get_field( 'bottom_cta_secondary_cta', get_the_ID(), [ 'url' => yhdr_whatsapp_url(), 'target' => '_blank' ] );
?>
<section class="cta-band">
	<div class="container">
		<div class="cta-band__card" data-animate="fadeInUp">
			<h2><?php echo esc_html( $heading ); ?></h2>
			<p><?php echo esc_html( $description ); ?></p>
			<div class="cta-band__actions" data-animate="fadeIn" data-animate-delay="150ms">
				<?php
				echo yhdr_link_button( $primary_cta, 'btn btn-primary', __( 'Get a Free Quote', 'yhdr' ) );
				echo yhdr_link_button( $secondary, 'btn btn-secondary', __( 'Chat on WhatsApp', 'yhdr' ) );
				?>
			</div>
		</div>
	</div>
</section>
