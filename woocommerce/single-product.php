<?php

/**
 * Single plan page override (Plan.dc.html's product-detail layout,
 * implemented as a real single-product permalink rather than the mockup's
 * JS-only lightbox). Plans are variable products -- File Type and Drawing
 * Sets are real WooCommerce variation attributes, so WooCommerce's own
 * variation JS drives the live no-reload price swap and the per-variation
 * downloadable files; assets/js/single-plan.js only re-skins the native
 * <select> elements as radio buttons/checkboxes and keeps the WhatsApp
 * shortcut in sync with whatever variation is currently selected.
 *
 * The product gallery is rendered manually (main image + first two gallery
 * images) rather than via woocommerce_before_single_product_summary, to
 * match the mockup's static hero + secondary-image-grid layout -- this
 * trades away WooCommerce's built-in lightbox/zoom for that layout.
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header('shop');

while (have_posts()) :
	the_post();

	$product = wc_get_product(get_the_ID());

	if (! $product) {
		continue;
	}

	$type_term   = yhdr_plan_type_term($product);
	$sku         = $product->get_sku();
	$shop_url    = get_permalink(wc_get_page_id('shop'));
	$badge       = get_post_meta($product->get_id(), '_yhdr_badge', true);
	$gallery_ids = $product->get_gallery_image_ids();

	$short_desc = $product->get_short_description();
	if (! $short_desc) {
		$short_desc = wp_strip_all_tags($product->get_description());
	}

	// The exact File Type/Drawing Sets/price only exist once a variation is
	// selected, so this base message stays generic; single-plan.js appends
	// the live selection when a valid variation is found.
	$whatsapp_base_message = sprintf(
		/* translators: 1: plan code, 2: plan name */
		__("Hi YHDR Builders, I'd like to buy plan %1\$s (%2\$s).", 'yhdr'),
		$sku,
		$product->get_name()
	);
?>
	<main id="yhdr-single-plan">
		<nav class="single-plan__breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'yhdr'); ?>">
			<div class="container">
				<a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'yhdr'); ?></a>
				<span aria-hidden="true">/</span>
				<?php if ($shop_url) : ?>
					<a href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('House Plan Shop', 'yhdr'); ?></a>
					<span aria-hidden="true">/</span>
				<?php endif; ?>
				<span class="single-plan__breadcrumb-current"><?php echo esc_html($product->get_name()); ?></span>
			</div>
		</nav>

		<div class="container single-plan__overview">
			<div class="single-plan__media" data-animate="fadeInLeft">
				<div class="single-plan__hero-image">
					<?php echo wp_kses_post($product->get_image('large', ['class' => 'single-plan__hero-img'])); ?>
				</div>
				<?php if ($gallery_ids) : ?>
					<div class="single-plan__sub-images">
						<?php foreach (array_slice($gallery_ids, 0, 2) as $attachment_id) : ?>
							<?php echo wp_get_attachment_image($attachment_id, 'medium_large', false, ['class' => 'single-plan__sub-img']); ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="single-plan__body" data-animate="fadeIn" data-animate-delay="200ms">
				<div class="single-plan__badges">
					<?php if ($type_term) : ?><span
							class="badge"><?php echo esc_html($type_term->name); ?></span><?php endif; ?>
					<?php if ($badge) : ?><span class="badge-gold"><?php echo esc_html($badge); ?></span><?php endif; ?>
					<?php if ($sku) : ?><span
							class="single-plan__sku"><?php echo esc_html(sprintf(__('Plan %s', 'yhdr'), $sku)); ?></span><?php endif; ?>
				</div>

				<h1><?php echo esc_html($product->get_name()); ?></h1>

				<div class="single-plan__price">
					<span class="single-plan__price-amount"><?php echo wp_kses_post($product->get_price_html()); ?></span>
					<span
						class="single-plan__price-caption"><?php esc_html_e('full plan set · one-time payment', 'yhdr'); ?></span>
				</div>

				<?php if (yhdr_plan_spec_pills($product)) : ?>
					<div class="single-plan__specs">
						<?php foreach (yhdr_plan_spec_pills($product) as $pill) : ?>
							<div class="single-plan__spec">
								<span class="single-plan__spec-icon"
									aria-hidden="true"><?php echo esc_html($pill['icon']); ?></span>
								<span class="single-plan__spec-value"><?php echo esc_html($pill['value']); ?></span>
								<span class="single-plan__spec-label"><?php echo esc_html($pill['label']); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ($short_desc) : ?>
					<p class="single-plan__description"><?php echo esc_html(wp_trim_words($short_desc, 40)); ?></p>
				<?php endif; ?>

				<div class="single-plan__actions">
					<div class="single-plan__add-to-cart woocommerce">
						<?php woocommerce_template_single_add_to_cart(); ?>
					</div>
					<a href="<?php echo esc_url(yhdr_whatsapp_url($whatsapp_base_message)); ?>" id="yhdr-whatsapp-buy-link"
						data-base-message="<?php echo esc_attr($whatsapp_base_message); ?>" class="btn btn-whatsapp-outline"
						target="_blank" rel="noopener noreferrer">
						<?php esc_html_e('Ask on WhatsApp', 'yhdr'); ?>
					</a>
				</div>

				<div class="single-plan__build-note">
					<p>
						<strong><?php esc_html_e('Build it with YHDR:', 'yhdr'); ?></strong>
						<?php esc_html_e('the full plan price is deducted from your construction contract if we build this home for you.', 'yhdr'); ?>
					</p>
				</div>
			</div>
		</div>

		<div class="container single-plan__how">
			<div class="single-plan__included card">
				<h2><?php esc_html_e("What's in the Plan Set", 'yhdr'); ?></h2>
				<ul>
					<li><span class="single-plan__check"
							aria-hidden="true">&check;</span><?php esc_html_e('Full architectural drawings', 'yhdr'); ?>
					</li>
					<li><span class="single-plan__check"
							aria-hidden="true">&check;</span><?php esc_html_e('Structural drawings', 'yhdr'); ?></li>
					<li><span class="single-plan__check"
							aria-hidden="true">&check;</span><?php esc_html_e('Electrical and plumbing layouts', 'yhdr'); ?>
					</li>
					<li><span class="single-plan__check"
							aria-hidden="true">&check;</span><?php esc_html_e('Bill of quantities (BOQ)', 'yhdr'); ?></li>
					<li><span class="single-plan__check"
							aria-hidden="true">&check;</span><?php esc_html_e('3D renders', 'yhdr'); ?></li>
					<li><span class="single-plan__check"
							aria-hidden="true">&check;</span><?php esc_html_e('One free revision consultation', 'yhdr'); ?>
					</li>
				</ul>
			</div>

			<div class="single-plan__steps">
				<h2><?php esc_html_e('How It Works', 'yhdr'); ?></h2>
				<ol>
					<li>
						<span class="single-plan__step-number"><?php echo esc_html('1'); ?></span>
						<span class="single-plan__step-body">
							<strong><?php esc_html_e('Add to cart & check out', 'yhdr'); ?></strong>
							<?php esc_html_e('Order in minutes, pay by MTN MoMo, Airtel Money, or bank transfer.', 'yhdr'); ?>
						</span>
					</li>
					<li>
						<span class="single-plan__step-number"><?php echo esc_html('2'); ?></span>
						<span class="single-plan__step-body">
							<strong><?php esc_html_e('Receive your plan set', 'yhdr'); ?></strong>
							<?php esc_html_e('Everything is emailed to you within 24 hours of payment confirmation.', 'yhdr'); ?>
						</span>
					</li>
					<li>
						<span class="single-plan__step-number"><?php echo esc_html('3'); ?></span>
						<span class="single-plan__step-body">
							<strong><?php esc_html_e('Build with anyone', 'yhdr'); ?></strong>
							<?php esc_html_e('Hand it to any contractor -- or let YHDR build it and deduct the plan price.', 'yhdr'); ?>
						</span>
					</li>
				</ol>
			</div>
			<?php yhdr_wave_divider('up', 'wave-divider--shop-how-top', 'wave-divider--navy-dark', 'wave-divider--bg-grey'); ?>
		</div>

		<?php
		$related_ids = wc_get_related_products($product->get_id(), 3);

		if ($related_ids) :
		?>
			<section class="shop shop--related">
				<div class="container">
					<div class="shop--related__header">
						<h2><?php esc_html_e('You May Also Like', 'yhdr'); ?></h2>
						<?php if ($shop_url) : ?>
							<a href="<?php echo esc_url($shop_url); ?>"
								class="shop--related__all"><?php esc_html_e('All plans', 'yhdr'); ?> &rarr;</a>
						<?php endif; ?>
					</div>
					<div class="shop__grid" data-animate-group>
						<?php
						foreach ($related_ids as $related_id) {
							yhdr_render_plan_card(wc_get_product($related_id));
						}
						?>
					</div>
				</div>
			</section>
		<?php
		endif;
		?>
	</main>

	<?php
	$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

	if ($cart_count > 0) :
	?>
		<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="single-plan__cart-bar">
			<span class="single-plan__cart-count"><?php echo esc_html($cart_count); ?></span>
			<?php
			echo esc_html(
				sprintf(
					/* translators: %d: number of plans in the cart */
					_n('%d plan in cart · Checkout', '%d plans in cart · Checkout', $cart_count, 'yhdr'),
					$cart_count
				)
			);
			?>
			&rarr;
		</a>
	<?php
	endif;
	?>
<?php
endwhile;

get_footer('shop');
