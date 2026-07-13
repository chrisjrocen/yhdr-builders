<?php

/**
 * Shared, escaped render helpers used by the Home/About template parts and
 * the CPT archive/single canvas overrides, so card markup stays consistent
 * everywhere it appears.
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Small uppercase eyebrow label.
 */
function yhdr_eyebrow($text)
{
	if (empty($text)) {
		return;
	}
	echo '<p class="eyebrow">' . esc_html($text) . '</p>';
}

/**
 * A single stat tile (e.g. "48+ / Projects Delivered").
 */
function yhdr_render_stat($value, $label)
{
	if (empty($value)) {
		return;
	}
?>
	<div class="stat-card">
		<div class="stat-value"><?php echo esc_html($value); ?></div>
		<div class="stat-label"><?php echo esc_html($label); ?></div>
	</div>
<?php
}

/**
 * A single `service` CPT card.
 */
function yhdr_render_service_card($post)
{
	if (! ($post instanceof WP_Post)) {
		return;
	}

	$excerpt = has_excerpt($post) ? get_the_excerpt($post) : wp_trim_words(wp_strip_all_tags($post->post_content), 20);
?>
	<article class="service-card card">
		<?php if (has_post_thumbnail($post)) : ?>
			<div class="service-card__icon"><?php echo get_the_post_thumbnail($post, 'thumbnail'); ?></div>
		<?php endif; ?>
		<h3 class="service-card__title"><?php echo esc_html(get_the_title($post)); ?></h3>
		<p class="service-card__excerpt"><?php echo esc_html($excerpt); ?></p>
		<a href="<?php echo esc_url(get_permalink($post)); ?>" class="service-card__link">
			<?php esc_html_e('Learn more', 'yhdr'); ?> &rarr;
		</a>
	</article>
<?php
}

/**
 * A single `project` CPT card.
 *
 * @param WP_Post $post
 * @param string  $variant 'grid' (default, used on Home/archive) or 'compact'.
 */
function yhdr_render_project_card($post, $variant = 'grid')
{
	if (! ($post instanceof WP_Post)) {
		return;
	}

	$location = yhdr_get_field('project_location', $post->ID, '');
	$tag      = yhdr_get_field('project_tag', $post->ID, '');
?>
	<article class="project-card project-card--<?php echo esc_attr($variant); ?> card"
		<?php echo $tag ? 'data-category="' . esc_attr(sanitize_title($tag)) . '"' : ''; ?>>
		<div class="project-card__media">
			<?php if (has_post_thumbnail($post)) : ?>
				<?php echo get_the_post_thumbnail($post, 'medium_large'); ?>
			<?php endif; ?>
			<?php if ($tag) : ?>
				<span class="project-card__tag badge"><?php echo esc_html($tag); ?></span>
			<?php endif; ?>
		</div>
		<div class="project-card__body">
			<h3 class="project-card__title"><?php echo esc_html(get_the_title($post)); ?></h3>
			<p class="project-card__excerpt"><?php echo esc_html(get_the_excerpt($post)); ?></p>
			<?php if ($location) : ?>
				<p class="project-card__location"><?php echo esc_html($location); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url(get_permalink($post)); ?>" class="project-card__link"
				aria-label="<?php echo esc_attr(get_the_title($post)); ?>">&rarr;</a>
		</div>
	</article>
<?php
}

/**
 * A single `team_member` CPT card.
 */
function yhdr_render_team_card($post)
{
	if (! ($post instanceof WP_Post)) {
		return;
	}

	$role    = yhdr_get_field('title', $post->ID, __('Managing director', 'yhdr'));
	$eyebrow = yhdr_get_field('eyebrow', $post->ID, __('Leadership & Client Relations', 'yhdr'));
?>
	<article class="team-card">
		<div class="team-card__avatar badge-circle">
			<?php if (has_post_thumbnail($post)) : ?>
				<?php echo get_the_post_thumbnail($post, 'medium'); ?>
			<?php else : ?>
				<span><?php echo esc_html(mb_substr(get_the_title($post), 0, 1)); ?></span>
			<?php endif; ?>
		</div>
		<h3 class="team-card__name"><?php echo esc_html(get_the_title($post)); ?></h3>
		<p class="team-card__role"><?php echo esc_html($role); ?></p>
		<p class="team-card__eyebrow"><?php echo esc_html($eyebrow); ?></p>
	</article>
<?php
}

/**
 * One testimonial slide/card. Accepts a normalized associative array so the
 * same markup serves both the Home `client_stories` repeater rows and the
 * `testimonial` CPT posts.
 *
 * @param array $fields ['rating' => int, 'quote' => string, 'client' => string, 'project' => string]
 */
function yhdr_render_testimonial_slide($fields)
{
	$rating  = max(0, min(5, (int) ($fields['rating'] ?? 5)));
	$quote   = $fields['quote'] ?? '';
	$client  = $fields['client'] ?? '';
	$project = $fields['project'] ?? '';

	if (empty($quote)) {
		return;
	}
?>
	<div class="testimonial-card">
		<div class="testimonial-card__rating" aria-hidden="true">
			<?php echo esc_html(str_repeat('★', $rating) . str_repeat('☆', 5 - $rating)); ?></div>
		<blockquote class="testimonial-card__quote">&ldquo;<?php echo esc_html($quote); ?>&rdquo;</blockquote>
		<?php if ($client) : ?>
			<p class="testimonial-card__client">
				<strong><?php echo esc_html($client); ?></strong>
				<?php if ($project) : ?>
					<span class="testimonial-card__project"><?php echo esc_html($project); ?></span>
				<?php endif; ?>
			</p>
		<?php endif; ?>
	</div>
<?php
}

/**
 * Normalize a `testimonial` CPT post into the array shape expected by
 * yhdr_render_testimonial_slide().
 */
function yhdr_testimonial_fields_from_post($post)
{
	if (! ($post instanceof WP_Post)) {
		return [];
	}

	return [
		'rating'  => (int) yhdr_get_field('client_stories_rating', $post->ID, 5),
		'quote'   => yhdr_get_field('client_stories_quote', $post->ID, get_the_excerpt($post)),
		'client'  => yhdr_get_field('client_stories_client', $post->ID, get_the_title($post)),
		'project' => yhdr_get_field('client_stories_project', $post->ID, ''),
	];
}
