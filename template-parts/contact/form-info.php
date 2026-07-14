<?php
/**
 * Contact form (Formidable Forms shortcode) + contact details column.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id       = get_the_ID();
$form_id       = yhdr_get_field( 'form_id', $post_id, '1' );
$contact_phone = yhdr_get_field( 'contact_phone', $post_id, YHDR_PHONE_DISPLAY );
$contact_email = yhdr_get_field( 'contact_email', $post_id, YHDR_EMAIL );
$address       = yhdr_get_field( 'postal_address', $post_id, YHDR_ADDRESS );
$twitter_url   = yhdr_get_field( 'xtwitter', $post_id, YHDR_TWITTER_URL );
$whatsapp      = yhdr_get_field( 'whatsapp', $post_id, YHDR_PHONE_E164 );
$map_embed     = yhdr_get_field( 'map_embed', $post_id, '' );

if ( ! $map_embed ) {
	$map_embed = '<iframe title="' . esc_attr__( 'Map of Kampala, Uganda', 'yhdr' ) . '" src="https://www.openstreetmap.org/export/embed.html?bbox=32.5200,0.2800,32.6500,0.3700&layer=mapnik&marker=0.3476,32.5825" loading="lazy"></iframe>';
}
?>
<section class="contact">
	<div class="container contact__grid">
		<div class="contact-form-card" data-animate="fadeInUp">
			<h2><?php esc_html_e( 'Request a Free Quote', 'yhdr' ); ?></h2>
			<?php echo do_shortcode( '[formidable id="' . esc_attr( $form_id ) . '"]' ); ?>
		</div>

		<div class="contact-info">
			<div class="contact-info__card">
				<h2><?php echo esc_html( yhdr_get_field( 'contact_heading', $post_id, __( 'Reach Us Directly', 'yhdr' ) ) ); ?></h2>

				<div class="contact-info__row">
					<span class="contact-info__icon" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
					</span>
					<span>
						<strong><?php esc_html_e( 'Call / WhatsApp', 'yhdr' ); ?></strong>
						<span><a href="tel:+<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $contact_phone ) ); ?>"><?php echo esc_html( $contact_phone ); ?></a></span>
					</span>
				</div>

				<div class="contact-info__row">
					<span class="contact-info__icon" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z" opacity="0"></path><path d="M22 6c0-1.1-.9-2-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h16a2 2 0 0 0 2-2V6z"></path><path d="M22 6l-10 7L2 6"></path></svg>
					</span>
					<span>
						<strong><?php esc_html_e( 'Email', 'yhdr' ); ?></strong>
						<span><a href="mailto:<?php echo esc_attr( antispambot( $contact_email ) ); ?>"><?php echo esc_html( antispambot( $contact_email ) ); ?></a></span>
					</span>
				</div>

				<div class="contact-info__row">
					<span class="contact-info__icon" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
					</span>
					<span>
						<strong><?php esc_html_e( 'Address', 'yhdr' ); ?></strong>
						<span><?php echo esc_html( $address ); ?></span>
					</span>
				</div>

				<div class="contact-info__row">
					<span class="contact-info__icon" aria-hidden="true">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-7.6 8.7L23 22h-6.9l-5.4-6.6L4.4 22H1.3l8.1-9.3L1 2h7.1l4.9 6.1L18.9 2zm-1.2 18h1.9L7.4 4H5.4l12.3 16z"></path></svg>
					</span>
					<span>
						<strong><?php esc_html_e( 'Follow Us', 'yhdr' ); ?></strong>
						<span><a href="<?php echo esc_url( $twitter_url ); ?>" target="_blank" rel="noopener noreferrer">X (Twitter)</a></span>
					</span>
				</div>

				<a href="<?php echo esc_url( yhdr_whatsapp_url( __( "Hi YHDR Builders, I'd like a quote for my project.", 'yhdr' ), $whatsapp ) ); ?>" class="btn btn-whatsapp contact-info__whatsapp-cta" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Chat on WhatsApp Now', 'yhdr' ); ?>
				</a>
			</div>

			<div class="contact-info__map" data-animate="zoomIn">
				<?php echo $map_embed; ?>
			</div>

			<div class="contact-info__hours">
				<h2><?php esc_html_e( 'Business Hours', 'yhdr' ); ?></h2>
				<div class="contact-info__hours-rows" data-animate-group>
					<div class="contact-info__hours-row" data-animate="fadeInLeft">
						<span><?php esc_html_e( 'Monday - Friday', 'yhdr' ); ?></span>
						<span><?php echo esc_html( yhdr_get_field( 'monday_to_friday', $post_id, '8:00 AM - 6:00 PM' ) ); ?></span>
					</div>
					<div class="contact-info__hours-row" data-animate="fadeInLeft">
						<span><?php esc_html_e( 'Saturday', 'yhdr' ); ?></span>
						<span><?php echo esc_html( yhdr_get_field( 'saturday', $post_id, '8:00 AM - 4:00 PM' ) ); ?></span>
					</div>
					<div class="contact-info__hours-row" data-animate="fadeInLeft">
						<span><?php esc_html_e( 'Sunday & Public Holidays', 'yhdr' ); ?></span>
						<span><?php echo esc_html( yhdr_get_field( 'sunday_public_holidays', $post_id, 'Closed' ) ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
