<?php
/**
 * About "Our Story" section.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = yhdr_get_field( 'our_story_heading', get_the_ID(), __( 'Our Story', 'yhdr' ) );
$story   = yhdr_get_field( 'our_story', get_the_ID(), '' );
$image   = yhdr_get_field( 'our_story_image', get_the_ID(), [] );
?>
<section class="our-story">
	<div class="container our-story__grid">
		<div class="our-story__content">
			<h2><?php echo esc_html( $heading ); ?></h2>
			<?php if ( $story ) : ?>
				<div class="our-story__text"><?php echo yhdr_kses_rich( $story ); ?></div>
			<?php endif; ?>
		</div>
		<?php if ( is_array( $image ) && ! empty( $image['url'] ) ) : ?>
			<div class="our-story__media">
				<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>" />
			</div>
		<?php endif; ?>
	</div>
</section>
