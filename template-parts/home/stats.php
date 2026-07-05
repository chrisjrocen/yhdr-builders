<?php
/**
 * Home stats bar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fallback_stats = [
	[ 'value' => '48+', 'label' => __( 'Projects Delivered', 'yhdr' ) ],
	[ 'value' => '7',   'label' => __( 'Years in Business', 'yhdr' ) ],
	[ 'value' => '35+', 'label' => __( 'Team Members', 'yhdr' ) ],
	[ 'value' => '98%', 'label' => __( 'Client Satisfaction', 'yhdr' ) ],
];
?>
<section class="stats">
	<div class="container stats__grid">
		<?php
		if ( yhdr_have_rows( 'stats' ) ) :
			while ( have_rows( 'stats' ) ) :
				the_row();
				yhdr_render_stat( get_sub_field( 'stats_value' ), get_sub_field( 'stats_description' ) );
			endwhile;
		else :
			foreach ( $fallback_stats as $stat ) :
				yhdr_render_stat( $stat['value'], $stat['label'] );
			endforeach;
		endif;
		?>
	</div>
</section>
