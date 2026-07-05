<?php
/**
 * Processes the Contact page form submission (no dedicated Contact template
 * exists -- the form itself lives in template-parts/canvas/page-contact.php
 * and posts here via admin-post.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_post_yhdr_contact_submit', 'yhdr_handle_contact_submit' );
add_action( 'admin_post_nopriv_yhdr_contact_submit', 'yhdr_handle_contact_submit' );

function yhdr_handle_contact_submit() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/contact/' );

	if (
		! isset( $_POST['yhdr_contact_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['yhdr_contact_nonce'] ) ), 'yhdr_contact_submit' )
	) {
		wp_safe_redirect( add_query_arg( 'yhdr_contact', 'error', $redirect ) );
		exit;
	}

	// Honeypot: real visitors never fill this hidden field in.
	if ( ! empty( $_POST['yhdr_contact_company'] ) ) {
		wp_safe_redirect( add_query_arg( 'yhdr_contact', 'success', $redirect ) );
		exit;
	}

	$name    = isset( $_POST['yhdr_name'] ) ? sanitize_text_field( wp_unslash( $_POST['yhdr_name'] ) ) : '';
	$email   = isset( $_POST['yhdr_email'] ) ? sanitize_email( wp_unslash( $_POST['yhdr_email'] ) ) : '';
	$phone   = isset( $_POST['yhdr_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['yhdr_phone'] ) ) : '';
	$service = isset( $_POST['yhdr_service'] ) ? sanitize_text_field( wp_unslash( $_POST['yhdr_service'] ) ) : '';
	$message = isset( $_POST['yhdr_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['yhdr_message'] ) ) : '';

	if ( empty( $name ) || ! is_email( $email ) || empty( $message ) ) {
		wp_safe_redirect( add_query_arg( 'yhdr_contact', 'error', $redirect ) );
		exit;
	}

	$subject = sprintf( '[Website Quote Request] %s', $name );
	$body    = implode( "\n", [
		'New quote request from the website contact form:',
		'',
		'Name: ' . $name,
		'Email: ' . $email,
		'Phone: ' . $phone,
		'Service: ' . $service,
		'',
		'Message:',
		$message,
	] );

	$headers = [ 'Reply-To: ' . $name . ' <' . $email . '>' ];
	$sent    = wp_mail( YHDR_EMAIL, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'yhdr_contact', $sent ? 'success' : 'error', $redirect ) );
	exit;
}
