<?php
/**
 * Custom Contact page, rendered via the
 * blocksy:single:canvas:custom-output filter when is_page('contact') (see
 * inc/canvas-overrides.php) -- no dedicated page-contact.php template file
 * name, this is a canvas override keyed off the page slug.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$status  = isset( $_GET['yhdr_contact'] ) ? sanitize_key( wp_unslash( $_GET['yhdr_contact'] ) ) : '';
$content = get_the_content();
?>
<main id="yhdr-contact">
	<section class="page-header">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e( 'Contact', 'yhdr' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<?php if ( $content ) : ?>
				<div class="page-header__intro"><?php echo yhdr_kses_rich( apply_filters( 'the_content', $content ) ); ?></div>
			<?php endif; ?>
		</div>
	</section>

	<section class="contact">
		<div class="container contact__grid">
			<div class="contact__form-col">
				<?php if ( $status === 'success' ) : ?>
					<div class="contact__banner contact__banner--success" role="status">
						<?php esc_html_e( 'Thanks! Your message has been sent -- we\'ll be in touch shortly.', 'yhdr' ); ?>
					</div>
				<?php elseif ( $status === 'error' ) : ?>
					<div class="contact__banner contact__banner--error" role="alert">
						<?php esc_html_e( 'Sorry, something went wrong sending your message. Please try again or reach us on WhatsApp.', 'yhdr' ); ?>
					</div>
				<?php endif; ?>

				<form class="contact__form" id="yhdr-contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="yhdr_contact_submit" />
					<?php wp_nonce_field( 'yhdr_contact_submit', 'yhdr_contact_nonce' ); ?>
					<div class="contact__honeypot" aria-hidden="true">
						<label for="yhdr_contact_company"><?php esc_html_e( 'Company', 'yhdr' ); ?></label>
						<input type="text" id="yhdr_contact_company" name="yhdr_contact_company" tabindex="-1" autocomplete="off" />
					</div>

					<div class="form-field">
						<label for="yhdr_name"><?php esc_html_e( 'Full name', 'yhdr' ); ?></label>
						<input type="text" id="yhdr_name" name="yhdr_name" required />
					</div>
					<div class="form-field">
						<label for="yhdr_phone"><?php esc_html_e( 'Phone number', 'yhdr' ); ?></label>
						<input type="tel" id="yhdr_phone" name="yhdr_phone" />
					</div>
					<div class="form-field">
						<label for="yhdr_email"><?php esc_html_e( 'Email address', 'yhdr' ); ?></label>
						<input type="email" id="yhdr_email" name="yhdr_email" required />
					</div>
					<div class="form-field">
						<label for="yhdr_service"><?php esc_html_e( 'Service needed', 'yhdr' ); ?></label>
						<select id="yhdr_service" name="yhdr_service">
							<?php
							$services = get_posts( [
								'post_type'      => 'service',
								'posts_per_page' => -1,
								'orderby'        => 'title',
								'order'          => 'ASC',
							] );
							foreach ( $services as $service ) :
								?>
								<option value="<?php echo esc_attr( $service->post_title ); ?>"><?php echo esc_html( $service->post_title ); ?></option>
							<?php endforeach; ?>
							<option value="<?php esc_attr_e( 'Other', 'yhdr' ); ?>"><?php esc_html_e( 'Other', 'yhdr' ); ?></option>
						</select>
					</div>
					<div class="form-field">
						<label for="yhdr_message"><?php esc_html_e( 'Tell us about your project', 'yhdr' ); ?></label>
						<textarea id="yhdr_message" name="yhdr_message" rows="5" required></textarea>
					</div>

					<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Request a Free Quote', 'yhdr' ); ?></button>
				</form>

				<a href="<?php echo esc_url( yhdr_whatsapp_url( __( 'Hi YHDR Builders, I\'d like a quote for my project.', 'yhdr' ) ) ); ?>" class="btn btn-whatsapp contact__whatsapp-cta" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Chat on WhatsApp Instead', 'yhdr' ); ?>
				</a>
			</div>

			<div class="contact__info-col">
				<div class="contact__info-card">
					<h3><?php esc_html_e( 'Call or WhatsApp', 'yhdr' ); ?></h3>
					<p><a href="tel:+<?php echo esc_attr( YHDR_PHONE_E164 ); ?>"><?php echo esc_html( YHDR_PHONE_DISPLAY ); ?></a></p>
				</div>
				<div class="contact__info-card">
					<h3><?php esc_html_e( 'Email', 'yhdr' ); ?></h3>
					<p><a href="mailto:<?php echo esc_attr( antispambot( YHDR_EMAIL ) ); ?>"><?php echo esc_html( antispambot( YHDR_EMAIL ) ); ?></a></p>
				</div>
				<div class="contact__info-card">
					<h3><?php esc_html_e( 'Address', 'yhdr' ); ?></h3>
					<p><?php echo esc_html( YHDR_ADDRESS ); ?></p>
				</div>
				<div class="contact__info-card">
					<h3><?php esc_html_e( 'Follow Us', 'yhdr' ); ?></h3>
					<p><a href="<?php echo esc_url( YHDR_TWITTER_URL ); ?>" target="_blank" rel="noopener noreferrer">X (Twitter)</a></p>
				</div>
				<div class="contact__map">
					<iframe
						title="<?php esc_attr_e( 'YHDR Builders location map', 'yhdr' ); ?>"
						src="https://www.openstreetmap.org/export/embed.html?bbox=32.50%2C0.28%2C32.65%2C0.38&layer=mapnik"
						loading="lazy"
						referrerpolicy="no-referrer-when-downgrade">
					</iframe>
				</div>
			</div>
		</div>
	</section>
</main>
