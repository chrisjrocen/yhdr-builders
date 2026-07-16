<?php

/**
 * Shop archive + `product_cat` term archive override (Shop.dc.html).
 *
 * WooCommerce falls back to this same file for both the main shop page and
 * any product_cat term archive, since no dedicated taxonomy-product_cat.php
 * exists -- category filter pills below are real permalinks to those term
 * archives (server-side filtering, no JS), and the sort dropdown resubmits
 * the current URL with an `orderby` query var that
 * yhdr_bedrooms_ordering_args() (inc/woocommerce-setup.php) and
 * WooCommerce's own catalog ordering already understand.
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header('shop');

$shop_url        = get_permalink(wc_get_page_id('shop'));
$current_term    = is_product_category() ? get_queried_object() : null;
$plan_categories = get_terms([
	'taxonomy'   => 'product_cat',
	'hide_empty' => true,
]);
$has_categories  = ! is_wp_error($plan_categories) && ! empty($plan_categories);

global $wp_query;
$total = $wp_query->found_posts;
?>
<main id="yhdr-shop-archive">
	<section class="page-header">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e('House Plan Shop', 'yhdr'); ?></p>
			<h1><?php esc_html_e('Ready-Made Plans. Ready to Build.', 'yhdr'); ?></h1>
			<div class="page-header__intro">
				<p><?php esc_html_e('Every plan includes full architectural drawings, a bill of quantities and 3D renders -- ready to hand straight to a contractor, or build with us.', 'yhdr'); ?>
				</p>
			</div>
		</div>
		<?php yhdr_wave_divider('up', 'wave-divider--page-header', 'wave-divider--grey', 'wave-divider--bg-navy-mid'); ?>
	</section>

	<section class="shop shop--archive">
		<div class="container">
			<?php if ($has_categories) : ?>
				<div class="shop__filters" role="group" aria-label="<?php esc_attr_e('Filter plans by type', 'yhdr'); ?>">
					<a href="<?php echo esc_url($shop_url); ?>"
						class="shop__filter-btn<?php echo ! $current_term ? ' is-active' : ''; ?>">
						<?php esc_html_e('All Plans', 'yhdr'); ?>
					</a>
					<?php foreach ($plan_categories as $category) : ?>
						<a href="<?php echo esc_url(get_term_link($category)); ?>"
							class="shop__filter-btn<?php echo ($current_term && $current_term->term_id === $category->term_id) ? ' is-active' : ''; ?>">
							<?php echo esc_html($category->name); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="shop__toolbar">
				<p class="shop__count">
					<?php echo esc_html(sprintf(_n('%d plan available', '%d plans available', $total, 'yhdr'), $total)); ?>
				</p>
				<form class="shop__sort" method="get">
					<label for="shop-orderby"><?php esc_html_e('Sort by', 'yhdr'); ?></label>
					<select name="orderby" id="shop-orderby" class="js-shop-sort-select">
						<?php
						$current_orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', 'menu_order'));
						$orderby_options  = apply_filters('woocommerce_catalog_orderby', [
							'menu_order' => __('Most popular', 'yhdr'),
							'price'      => __('Price: low to high', 'yhdr'),
							'price-desc' => __('Price: high to low', 'yhdr'),
							'beds'       => __('Bedrooms', 'yhdr'),
						]);
						foreach ($orderby_options as $id => $label) :
							if ('popularity' === $id || 'rating' === $id || 'date' === $id) {
								continue;
							}
						?>
							<option value="<?php echo esc_attr($id); ?>" <?php selected($current_orderby, $id); ?>>
								<?php echo esc_html($label); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</form>
			</div>

			<?php if (have_posts()) : ?>
				<div class="shop__grid" data-animate-group>
					<?php
					while (have_posts()) :
						the_post();
						yhdr_render_plan_card(wc_get_product(get_the_ID()));
					endwhile;
					?>
				</div>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<p><?php esc_html_e('Our house plan catalogue will be published here shortly.', 'yhdr'); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php yhdr_wave_divider('up', 'wave-divider--shop-how-top', 'wave-divider--navy-dark', 'wave-divider--bg-grey'); ?>
	<section class="shop-how">
		<div class="container">
			<h2><?php esc_html_e('How It Works', 'yhdr'); ?></h2>
			<div class="shop-how__grid" data-animate-group>
				<?php
				$steps = [
					[__('Choose Your Plan', 'yhdr'), __('Browse the catalogue and pick the plan that fits your land and budget.', 'yhdr')],
					[__('Pay Securely', 'yhdr'), __("Buy the plan on WhatsApp -- we'll confirm your order and send payment details.", 'yhdr')],
					[__('Receive Everything', 'yhdr'), __('Get the full drawing set, BOQ and 3D renders, ready for any contractor.', 'yhdr')],
					[__('Build -- With Us or Anyone', 'yhdr'), __('Hand the plan to your own team, or let YHDR Builders bring it to life.', 'yhdr')],
				];
				foreach ($steps as $index => $step) :
				?>
					<div class="shop-how__step" data-animate="fadeInUp">
						<div class="shop-how__number badge-circle"><?php echo esc_html($index + 1); ?></div>
						<h3><?php echo esc_html($step[0]); ?></h3>
						<p><?php echo esc_html($step[1]); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
			<p class="shop-how__note">
				<?php esc_html_e("Every plan's price is fully deductible from your final contract if you build with YHDR Builders.", 'yhdr'); ?>
			</p>
		</div>

	</section>

	<section class="shop-cta">
		<div class="container">
			<h2><?php esc_html_e("Don't See Your Dream Here?", 'yhdr'); ?></h2>
			<p><?php esc_html_e('We design fully custom homes too -- tell us what you have in mind and our architects will take it from there.', 'yhdr'); ?>
			</p>
			<a href="<?php echo esc_url(get_post_type_archive_link('service')); ?>" class="btn btn-primary">
				<?php esc_html_e('Explore Custom Design', 'yhdr'); ?>
			</a>
		</div>
	</section>
	<?php yhdr_wave_divider('up', 'wave-divider--shop-how-top', 'wave-divider--navy-dark', 'wave-divider--bg-grey'); ?>
</main>

<?php get_footer('shop'); ?>