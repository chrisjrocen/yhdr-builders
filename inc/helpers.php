<?php
/**
 * Defensive helpers for reading SCF/ACF fields that may not exist yet
 * (post types and field groups are registered separately via the SCF UI).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Safely read an ACF/SCF field, tolerating a missing plugin or empty value.
 *
 * @param string     $field   Field name.
 * @param int|false  $post_id Post ID, or false for the current post.
 * @param mixed      $default Fallback value when the field is empty/unavailable.
 * @return mixed
 */
function yhdr_get_field( $field, $post_id = false, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$value = get_field( $field, $post_id );

	if ( $value === '' || $value === null || $value === false ) {
		return $default;
	}

	return $value;
}

/**
 * Safely start an SCF/ACF repeater loop.
 *
 * @param string    $field   Field name.
 * @param int|false $post_id Post ID, or false for the current post.
 * @return bool
 */
function yhdr_have_rows( $field, $post_id = false ) {
	if ( ! function_exists( 'have_rows' ) ) {
		return false;
	}

	return (bool) have_rows( $field, $post_id );
}

/**
 * Render an ACF "link" field (array with url/title/target) as an escaped anchor.
 *
 * @param mixed  $link_field ACF link field value.
 * @param string $classes    Extra CSS classes for the anchor.
 * @param string $fallback_label Label to use if the link array has no title.
 * @return string
 */
function yhdr_link_button( $link_field, $classes = 'btn', $fallback_label = '' ) {
	if ( ! is_array( $link_field ) || empty( $link_field['url'] ) ) {
		return '';
	}

	$url    = esc_url( $link_field['url'] );
	$label  = ! empty( $link_field['title'] ) ? $link_field['title'] : $fallback_label;
	$target = ! empty( $link_field['target'] ) ? $link_field['target'] : '';
	$rel    = $target === '_blank' ? ' rel="noopener noreferrer"' : '';

	return sprintf(
		'<a href="%1$s" class="%2$s"%3$s%4$s>%5$s</a>',
		$url,
		esc_attr( $classes ),
		$target ? ' target="' . esc_attr( $target ) . '"' : '',
		$rel,
		esc_html( $label )
	);
}

/**
 * Build a wa.me deep link pre-filled with a message.
 *
 * @param string $message Plain-text message.
 * @param string $phone   E.164 phone number (no leading +), defaults to YHDR_PHONE_E164.
 * @return string
 */
function yhdr_whatsapp_url( $message = '', $phone = YHDR_PHONE_E164 ) {
	$url = 'https://wa.me/' . $phone;

	if ( $message !== '' ) {
		$url .= '?text=' . rawurlencode( $message );
	}

	return esc_url( $url );
}

/**
 * Sanitize a WYSIWYG/rich-text field for output.
 *
 * @param string $html Raw HTML.
 * @return string
 */
function yhdr_kses_rich( $html ) {
	return wp_kses_post( $html );
}

/**
 * Estimate a post's reading time in whole minutes (~200 words/minute),
 * matching the `readMins` field used on the Blog archive/single mockups.
 *
 * @param WP_Post $post
 * @return int Minimum 1.
 */
function yhdr_reading_time( $post ) {
	if ( ! ( $post instanceof WP_Post ) ) {
		return 1;
	}

	$word_count = str_word_count( wp_strip_all_tags( $post->post_content ) );

	return max( 1, (int) ceil( $word_count / 200 ) );
}

/**
 * Normalize an ACF/SCF image field (return format "array") into a
 * ['url' => ..., 'alt' => ...] pair, falling back to a placeholder image
 * and the given alt text when the field is empty or not an image array.
 *
 * @param mixed  $image_field ACF image field value.
 * @param string $fallback_alt Alt text to use when the field has none.
 * @return array{url: string, alt: string}
 */
function yhdr_image_field( $image_field, $fallback_alt = '' ) {
	$placeholder = YHDR_THEME_URI . '/assets/images/placeholder-project.svg';

	if ( ! is_array( $image_field ) || empty( $image_field['url'] ) ) {
		return [
			'url' => $placeholder,
			'alt' => $fallback_alt,
		];
	}

	return [
		'url' => $image_field['url'],
		'alt' => ! empty( $image_field['alt'] ) ? $image_field['alt'] : $fallback_alt,
	];
}
