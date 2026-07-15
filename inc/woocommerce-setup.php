<?php
/**
 * One-time WooCommerce content bootstrap for the House Plan Shop: global
 * attributes (beds/bathrooms/area), plan-type categories, and a handful of
 * sample plan products so the shop/single-product templates have real data
 * to render. Guarded by the `yhdr_wc_seeded` option so it only ever runs
 * once per site. Never deletes existing content -- clearing WooCommerce's
 * stock demo catalog is a manual, documented WP-CLI step (see README note),
 * not something this theme does automatically.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_switch_theme', 'yhdr_wc_seed' );
add_action( 'admin_init', 'yhdr_wc_seed' );

function yhdr_wc_seed() {
	if ( get_option( 'yhdr_wc_seeded' ) || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	$attributes = [
		'beds'          => __( 'Beds', 'yhdr' ),
		'bathrooms'     => __( 'Bathrooms', 'yhdr' ),
		'area'          => __( 'Area (m²)', 'yhdr' ),
		// Variation-enabled attributes: every combination of these two gets
		// its own price and its own downloadable files, set by the admin in
		// the product's Variations panel.
		'file_type'     => __( 'File Type', 'yhdr' ),
		'drawing_sets'  => __( 'Drawing Sets', 'yhdr' ),
	];

	foreach ( $attributes as $slug => $label ) {
		if ( wc_attribute_taxonomy_id_by_name( $slug ) ) {
			continue;
		}

		wc_create_attribute( [
			'name'         => $label,
			'slug'         => $slug,
			'type'         => 'select',
			'order_by'     => 'menu_order',
			'has_archives' => false,
		] );
	}

	// Attribute taxonomies aren't queryable via get_taxonomy() until WooCommerce
	// re-registers them, which normally happens on the next request.
	delete_transient( 'wc_attribute_taxonomies' );
	if ( function_exists( 'wc_create_all_attribute_taxonomies' ) ) {
		wc_create_all_attribute_taxonomies();
	}

	// The two variation attributes have a fixed, shop-wide set of choices
	// (unlike beds/bathrooms/area, whose terms are created per plan below),
	// so create all of their terms up front.
	$file_type_terms    = [ 'CAD + PDF', 'PDF' ];
	$drawing_sets_terms = [ 'Architectural', 'Structural', 'Both' ];
	$file_type_term_ids    = [];
	$drawing_sets_term_ids = [];

	foreach ( $file_type_terms as $name ) {
		$file_type_term_ids[ $name ] = yhdr_wc_get_or_create_term( $name, wc_attribute_taxonomy_name( 'file_type' ) );
	}

	foreach ( $drawing_sets_terms as $name ) {
		$drawing_sets_term_ids[ $name ] = yhdr_wc_get_or_create_term( $name, wc_attribute_taxonomy_name( 'drawing_sets' ) );
	}

	$categories = [ 'Bungalow', 'Two-Storey', 'Villa', 'Mansion', 'Pavilion' ];
	$category_ids = [];

	foreach ( $categories as $name ) {
		$existing = term_exists( $name, 'product_cat' );

		if ( $existing ) {
			$category_ids[ $name ] = (int) $existing['term_id'];
			continue;
		}

		$inserted = wp_insert_term( $name, 'product_cat' );

		if ( ! is_wp_error( $inserted ) ) {
			$category_ids[ $name ] = (int) $inserted['term_id'];
		}
	}

	$sample_plans = [
		[
			'code'  => 'YB-101',
			'name'  => 'The Kira Bungalow',
			'type'  => 'Bungalow',
			'beds'  => '3',
			'baths' => '2',
			'area'  => '145',
			'price' => 1800000,
			'badge' => 'Best Seller',
			'short' => __( 'A compact, cost-efficient 3-bedroom bungalow built for a standard 50x100ft plot.', 'yhdr' ),
			'desc'  => __( 'The Kira Bungalow is our most requested plan -- an efficient single-storey layout with a welcoming veranda, open-plan living/dining, and a private wing for the master bedroom. Designed to keep both build cost and construction time down without compromising comfort.', 'yhdr' ),
		],
		[
			'code'  => 'YB-204',
			'name'  => 'The Naalya Two-Storey',
			'type'  => 'Two-Storey',
			'beds'  => '4',
			'baths' => '3',
			'area'  => '220',
			'price' => 3200000,
			'badge' => 'Popular',
			'short' => __( 'A family-sized two-storey home with 4 bedrooms and a private upstairs lounge.', 'yhdr' ),
			'desc'  => __( 'The Naalya Two-Storey gives a growing family room to spread out: living and entertaining space downstairs, four bedrooms and a family lounge upstairs, plus a covered balcony overlooking the front garden.', 'yhdr' ),
		],
		[
			'code'  => 'YB-310',
			'name'  => 'The Muyenga Villa',
			'type'  => 'Villa',
			'beds'  => '5',
			'baths' => '5',
			'area'  => '340',
			'price' => 5600000,
			'badge' => '',
			'short' => __( 'A 5-bedroom villa with an ensuite for every bedroom and a statement double-height entrance.', 'yhdr' ),
			'desc'  => __( 'The Muyenga Villa is built for hillside plots with a view -- a dramatic double-height entrance hall, five ensuite bedrooms, a separate guest wing, and generous outdoor entertaining space.', 'yhdr' ),
		],
	];

	// Illustrative price multipliers per (file type, drawing sets) combination,
	// applied to each plan's base price -- placeholder test data only, the
	// admin sets real per-variation prices in the Variations panel.
	$price_multipliers = [
		'PDF'       => [ 'Architectural' => 1.0, 'Structural' => 1.0, 'Both' => 1.6 ],
		'CAD + PDF' => [ 'Architectural' => 1.3, 'Structural' => 1.3, 'Both' => 2.0 ],
	];

	// Placeholder downloadable "file" -- stands in for the CAD/PDF drawings
	// the admin will upload per variation via the Variations panel.
	$placeholder_download_url = YHDR_THEME_URI . '/assets/images/placeholder-project.svg';

	// WooCommerce's "Approved Download Directories" feature rejects downloads
	// outside an allow-listed directory. `add_approved_directory()` normally
	// only auto-enables the rule for a logged-in site admin; since this runs
	// outside any admin session (theme activation / WP-CLI) we explicitly
	// allow-list and enable the theme's own images directory ourselves.
	if ( class_exists( '\Automattic\WooCommerce\Internal\ProductDownloads\ApprovedDirectories\Register' ) ) {
		$approved_directories = wc_get_container()->get( \Automattic\WooCommerce\Internal\ProductDownloads\ApprovedDirectories\Register::class );

		if ( $approved_directories->get_mode() === \Automattic\WooCommerce\Internal\ProductDownloads\ApprovedDirectories\Register::MODE_ENABLED
			&& ! $approved_directories->is_valid_path( $placeholder_download_url ) ) {
			$images_dir_url = YHDR_THEME_URI . '/assets/images/';
			$existing_rule  = $approved_directories->get_by_url( $images_dir_url );

			if ( $existing_rule ) {
				$approved_directories->enable_by_id( $existing_rule->get_id() );
			} else {
				$approved_directories->add_approved_directory( $images_dir_url, true );
			}
		}
	}

	foreach ( $sample_plans as $plan ) {
		if ( wc_get_product_id_by_sku( $plan['code'] ) ) {
			continue;
		}

		$product = new WC_Product_Variable();
		$product->set_name( $plan['name'] );
		$product->set_sku( $plan['code'] );
		$product->set_status( 'publish' );
		$product->set_short_description( $plan['short'] );
		$product->set_description( $plan['desc'] );

		if ( ! empty( $category_ids[ $plan['type'] ] ) ) {
			$product->set_category_ids( [ $category_ids[ $plan['type'] ] ] );
		}

		$product_attributes = [];

		// Informational, non-variation attributes (single value each).
		$attr_values = [
			'beds'      => $plan['beds'],
			'bathrooms' => $plan['baths'],
			'area'      => $plan['area'],
		];

		foreach ( $attr_values as $slug => $value ) {
			$taxonomy = wc_attribute_taxonomy_name( $slug );

			if ( ! term_exists( $value, $taxonomy ) ) {
				wp_insert_term( $value, $taxonomy );
			}

			$attribute = new WC_Product_Attribute();
			$attribute->set_id( wc_attribute_taxonomy_id_by_name( $slug ) );
			$attribute->set_name( $taxonomy );
			$attribute->set_options( [ get_term_by( 'name', $value, $taxonomy )->term_id ] );
			$attribute->set_visible( true );
			$attribute->set_variation( false );
			$product_attributes[] = $attribute;
		}

		// Variation-enabled attributes -- every term is offered as an option,
		// and set_variation( true ) is what makes this a true variable product.
		$file_type_attribute = new WC_Product_Attribute();
		$file_type_attribute->set_id( wc_attribute_taxonomy_id_by_name( 'file_type' ) );
		$file_type_attribute->set_name( wc_attribute_taxonomy_name( 'file_type' ) );
		$file_type_attribute->set_options( array_values( $file_type_term_ids ) );
		$file_type_attribute->set_visible( true );
		$file_type_attribute->set_variation( true );
		$product_attributes[] = $file_type_attribute;

		$drawing_sets_attribute = new WC_Product_Attribute();
		$drawing_sets_attribute->set_id( wc_attribute_taxonomy_id_by_name( 'drawing_sets' ) );
		$drawing_sets_attribute->set_name( wc_attribute_taxonomy_name( 'drawing_sets' ) );
		$drawing_sets_attribute->set_options( array_values( $drawing_sets_term_ids ) );
		$drawing_sets_attribute->set_visible( true );
		$drawing_sets_attribute->set_variation( true );
		$product_attributes[] = $drawing_sets_attribute;

		$product->set_attributes( $product_attributes );
		$product_id = $product->save();

		if ( ! $product_id ) {
			continue;
		}

		update_post_meta( $product_id, '_yhdr_beds_sort', (int) $plan['beds'] );

		if ( $plan['badge'] ) {
			update_post_meta( $product_id, '_yhdr_badge', $plan['badge'] );
		}

		// One variation per (file type x drawing sets) combination, each with
		// its own price and its own downloadable file(s).
		foreach ( $file_type_terms as $file_type_name ) {
			foreach ( $drawing_sets_terms as $drawing_sets_name ) {
				$variation = new WC_Product_Variation();
				$variation->set_parent_id( $product_id );
				// Keyed by the full taxonomy name (pa_file_type/pa_drawing_sets),
				// not the bare attribute slug -- that's what get_variation_attributes()
				// looks up under the attribute_{taxonomy} meta key.
				$variation->set_attributes( [
					wc_attribute_taxonomy_name( 'file_type' )    => sanitize_title( $file_type_name ),
					wc_attribute_taxonomy_name( 'drawing_sets' ) => sanitize_title( $drawing_sets_name ),
				] );

				$multiplier = $price_multipliers[ $file_type_name ][ $drawing_sets_name ];
				$variation->set_regular_price( (string) round( $plan['price'] * $multiplier, -3 ) );

				$variation->set_downloadable( true );
				// Plain arrays here, not WC_Product_Download objects -- the
				// object's own constructor doesn't hydrate from an array,
				// only set_downloads()'s internal build_downloads_map() does.
				$variation->set_downloads( [
					[
						'name' => sprintf( '%s -- %s (%s)', $plan['name'], $drawing_sets_name, $file_type_name ),
						'file' => $placeholder_download_url,
					],
				] );

				$variation->save();
			}
		}

		// Recompute the parent's min/max price range from its variations so
		// shop-grid price display/sorting works immediately.
		WC_Product_Variable::sync( $product_id );
		wc_delete_product_transients( $product_id );
	}

	update_option( 'yhdr_wc_seeded', YHDR_VERSION );
}

/**
 * Find an existing term by name in a taxonomy, or create it, returning the
 * term_id either way.
 */
function yhdr_wc_get_or_create_term( $name, $taxonomy ) {
	$existing = term_exists( $name, $taxonomy );

	if ( $existing ) {
		return (int) $existing['term_id'];
	}

	$inserted = wp_insert_term( $name, $taxonomy );

	return is_wp_error( $inserted ) ? 0 : (int) $inserted['term_id'];
}

/**
 * Keep the `_yhdr_beds_sort` postmeta (used for the "Bedrooms" catalog sort
 * option, since WooCommerce has no built-in orderby=attribute) in sync
 * whenever a product's `pa_beds` attribute is edited by hand later.
 */
add_action( 'woocommerce_process_product_meta', 'yhdr_sync_beds_sort_meta' );

function yhdr_sync_beds_sort_meta( $product_id ) {
	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		return;
	}

	$beds = $product->get_attribute( 'pa_beds' );
	$beds_number = (int) preg_replace( '/[^0-9]/', '', $beds );

	update_post_meta( $product_id, '_yhdr_beds_sort', $beds_number );
}

/**
 * Add a "Bedrooms" option to the shop's sort-by dropdown.
 */
add_filter( 'woocommerce_catalog_orderby', 'yhdr_add_bedrooms_orderby' );
add_filter( 'woocommerce_default_catalog_orderby_options', 'yhdr_add_bedrooms_orderby' );

function yhdr_add_bedrooms_orderby( $options ) {
	$options['beds'] = __( 'Bedrooms', 'yhdr' );
	return $options;
}

add_filter( 'woocommerce_get_catalog_ordering_args', 'yhdr_bedrooms_ordering_args' );

function yhdr_bedrooms_ordering_args( $args ) {
	if ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'beds' ) {
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
		$args['meta_key'] = '_yhdr_beds_sort';
	}

	return $args;
}
