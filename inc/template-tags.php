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
 * Decorative section divider: a solid block with an elliptical curve carved
 * out of one edge via ::after, giving the illusion of a wave between two
 * differently colored sections.
 *
 * @param string $direction 'up' (curve carved from the bottom edge, default)
 *                          or 'down' (curve carved from the top edge).
 * @param string $class Extra wrapper class, e.g. to hook per-page tweaks.
 * @param string $color Modifier class controlling the curve (::after) fill
 *                       color, e.g. 'wave-divider--white' or
 *                       'wave-divider--grey'. Defaults 'wave-divider--navy-dark'.
 * @param string $bg Modifier class controlling the block's own background
 *                    color, e.g. 'wave-divider--bg-white'. Defaults
 *                    'wave-divider--bg-navy-dark'.
 */
function yhdr_wave_divider($direction = 'up', $class = '', $color = 'wave-divider--navy-dark', $bg = 'wave-divider--bg-navy-dark')
{
	$modifier = $direction === 'down' ? 'wave-divider--down' : 'wave-divider--up';
?>
<div class="wave-divider <?php echo esc_attr($modifier); ?> <?php echo esc_attr($color); ?> <?php echo esc_attr($bg); ?> <?php echo esc_attr($class); ?>"
    aria-hidden="true"></div>
<?php
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
 * A single `service` CPT row in the zig-zag services archive list.
 *
 * @param WP_Post $post
 * @param int     $index Zero-based position, used to alternate image/text sides.
 */
function yhdr_render_service_row($post, $index = 0)
{
	if (! ($post instanceof WP_Post)) {
		return;
	}

	$tag     = yhdr_get_field('service_tag', $post->ID, '');
	$excerpt = has_excerpt($post) ? get_the_excerpt($post) : wp_trim_words(wp_strip_all_tags($post->post_content), 40);
	$reverse = $index % 2 === 1;
?>
<article class="service-row<?php echo $reverse ? ' service-row--reverse' : ''; ?>">
    <div class="service-row__media">
        <?php if (has_post_thumbnail($post)) : ?>
        <?php echo get_the_post_thumbnail($post, 'medium_large'); ?>
        <?php else : ?>
        <img src="<?php echo esc_url(YHDR_THEME_URI . '/assets/images/placeholder-project.svg'); ?>" alt=""
            class="service-row__placeholder" />
        <?php endif; ?>
    </div>
    <div class="service-row__body">
        <?php if ($tag) : ?>
        <span class="badge service-row__tag"><?php echo esc_html($tag); ?></span>
        <?php endif; ?>
        <h2 class="service-row__title"><?php echo esc_html(get_the_title($post)); ?></h2>
        <p class="service-row__excerpt"><?php echo esc_html($excerpt); ?></p>
        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary service-row__cta">
            <?php esc_html_e('Request This Service', 'yhdr'); ?>
        </a>
    </div>
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
	$terms    = get_the_terms($post->ID, 'project_category');
	$category_slug = (! empty($terms) && ! is_wp_error($terms)) ? $terms[0]->slug : '';
?>
<article class="project-card project-card--<?php echo esc_attr($variant); ?> card"
    <?php echo $category_slug ? 'data-category="' . esc_attr($category_slug) . '"' : ''; ?>>
    <div class="project-card__media">
        <?php if (has_post_thumbnail($post)) : ?>
        <?php echo get_the_post_thumbnail($post, 'medium_large'); ?>
        <?php else : ?>
        <img src="<?php echo esc_url(YHDR_THEME_URI . '/assets/images/placeholder-project.svg'); ?>" alt=""
            class="project-card__placeholder" />
        <?php endif; ?>
    </div>
    <div class="project-card__body">
        <?php if ($tag || $location) : ?>
        <div class="project-card__meta">
            <?php if ($tag) : ?>
            <span class="project-card__tag badge"><?php echo esc_html($tag); ?></span>
            <?php endif; ?>
            <?php if ($location) : ?>
            <span class="project-card__location"><?php echo esc_html($location); ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <h3 class="project-card__title"><?php echo esc_html(get_the_title($post)); ?></h3>
        <p class="project-card__excerpt"><?php echo esc_html(get_the_excerpt($post)); ?></p>
        <a href="<?php echo esc_url(get_permalink($post)); ?>" class="project-card__link">
            <?php esc_html_e('View details', 'yhdr'); ?> &rarr;
        </a>
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