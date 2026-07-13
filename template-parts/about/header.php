<?php
/**
 * About page header. There is no dedicated ACF field for the intro
 * paragraph -- it uses the Page's own editor content, falling back to a
 * default tagline if the page content is empty.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content = get_the_content();
$title   = get_the_title() ? get_the_title() : __( 'Class Shouldn\'t Cost a Fortune', 'yhdr' );
?>
<section class="page-header">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'About Us', 'yhdr' ); ?></p>
		<h1><?php echo esc_html( $title ); ?></h1>
		<div class="page-header__intro">
			<?php if ( $content ) : ?>
				<?php echo yhdr_kses_rich( apply_filters( 'the_content', $content ) ); ?>
			<?php else : ?>
				<p><?php esc_html_e( 'We design your ideas, we build them -- this is where cheap meets class.', 'yhdr' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php yhdr_wave_divider( 'up', 'wave-divider--page-header' ); ?>
</section>
