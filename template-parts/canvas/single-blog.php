<?php

/**
 * Custom blog (`post`) single, rendered via the
 * blocksy:single:canvas:custom-output filter (see inc/canvas-overrides.php)
 * -- no dedicated single.php template.
 */

if (! defined('ABSPATH')) {
    exit;
}

$post_id       = get_the_ID();
$categories    = get_the_category($post_id);
$category      = ! empty($categories) ? $categories[0] : null;
$blog_page_id  = (int) get_option('page_for_posts');
$blog_url      = $blog_page_id ? get_permalink($blog_page_id) : home_url('/');
$read_mins     = yhdr_reading_time(get_post());

// Related posts: same category first, backfilled with any other posts.
$related_ids = [];
if ($category) {
    $same_category = get_posts([
        'category__in'        => [$category->term_id],
        'post__not_in'        => [$post_id],
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
    ]);
    $related_ids = wp_list_pluck($same_category, 'ID');
}
if (count($related_ids) < 3) {
    $fill = get_posts([
        'post__not_in'        => array_merge([$post_id], $related_ids),
        'posts_per_page'      => 3 - count($related_ids),
        'ignore_sticky_posts' => true,
    ]);
    $related_ids = array_merge($related_ids, wp_list_pluck($fill, 'ID'));
}
$related_posts = ! empty($related_ids) ? get_posts([
    'post__in'       => $related_ids,
    'orderby'        => 'post__in',
    'posts_per_page' => 3,
]) : [];
?>
<main id="yhdr-single-blog">
    <article <?php post_class('single-blog'); ?>>
        <section
            class="page-header single-blog__header<?php echo has_post_thumbnail() ? ' single-blog__header--has-image' : ''; ?>">
            <div class="container">
                <nav class="single-blog__breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'yhdr'); ?>">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'yhdr'); ?></a>
                    <span aria-hidden="true">/</span>
                    <a href="<?php echo esc_url($blog_url); ?>"><?php esc_html_e('Blog', 'yhdr'); ?></a>
                    <?php if ($category) : ?>
                        <span aria-hidden="true">/</span>
                        <span class="single-blog__breadcrumb-current"><?php echo esc_html($category->name); ?></span>
                    <?php endif; ?>
                </nav>
                <?php if ($category) : ?>
                    <span class="badge-gold single-blog__category"><?php echo esc_html($category->name); ?></span>
                <?php endif; ?>
                <h1 data-animate="fadeInUp"><?php the_title(); ?></h1>
                <p class="single-blog__byline">
                    <?php esc_html_e('By the YHDR Builders team', 'yhdr'); ?>
                    &middot; <?php echo esc_html(get_the_date()); ?>
                    &middot;
                    <?php echo esc_html(sprintf(_n('%d min read', '%d min read', $read_mins, 'yhdr'), $read_mins)); ?>
                </p>
            </div>
        </section>
        <?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--grey', 'wave-divider--bg-navy-mid'); ?>

        <div class="container single-blog__content">
            <?php if (has_post_thumbnail()) : ?>
                <div class="single-blog__featured-image" data-animate="fadeIn">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <?php if (has_excerpt()) : ?>
                <p class="single-blog__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
            <?php endif; ?>

            <div class="single-blog__body">
                <?php the_content(); ?>
            </div>

            <div class="single-blog__cta">
                <div class="single-blog__cta-copy">
                    <h2><?php esc_html_e('Planning a Build?', 'yhdr'); ?></h2>
                    <p><?php esc_html_e('Get an honest, itemised quote — or just ask a question. We reply within 24 hours.', 'yhdr'); ?>
                    </p>
                </div>
                <div class="single-blog__cta-actions">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Get a Quote', 'yhdr'); ?>
                    </a>
                    <a href="<?php echo yhdr_whatsapp_url(); ?>" target="_blank" rel="noopener" class="btn btn-secondary">
                        <?php esc_html_e('WhatsApp Us', 'yhdr'); ?>
                    </a>
                </div>
            </div>
        </div>
    </article>

    <?php if (! empty($related_posts)) : ?>
        <section class="blog-related" aria-label="<?php esc_attr_e('More articles', 'yhdr'); ?>">
            <div class="container">
                <div class="blog-related__heading">
                    <h2><?php esc_html_e('Keep Reading', 'yhdr'); ?></h2>
                    <a href="<?php echo esc_url($blog_url); ?>" class="blog-related__all">
                        <?php esc_html_e('All articles', 'yhdr'); ?> &rarr;
                    </a>
                </div>
                <div class="blog-related__grid">
                    <?php foreach ($related_posts as $related) : ?>
                        <?php yhdr_render_blog_related_card($related); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--navy-dark', 'wave-divider--bg-grey'); ?>
</main>