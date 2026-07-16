<?php

/**
 * Custom blog (`post`) archive, rendered via the
 * blocksy:posts-listing:canvas:custom-output filter (see
 * inc/canvas-overrides.php) -- no dedicated archive.php/index.php template.
 *
 * The first post on page 1 is pulled out as the "featured" article; the grid
 * below excludes it. Category filtering is a static client-side show/hide
 * over the server-rendered grid (see assets/js/blog-filter.js), matching the
 * Projects archive pattern -- the featured card is not re-computed per
 * filter.
 */

if (! defined('ABSPATH')) {
    exit;
}

$categories     = get_categories(['hide_empty' => true]);
$has_categories = ! empty($categories);
$featured_post  = null;
$grid_posts     = [];

if (have_posts()) {
    $is_first = true;

    while (have_posts()) {
        the_post();

        if ($is_first && ! is_paged()) {
            $featured_post = get_post();
        } else {
            $grid_posts[] = get_post();
        }

        $is_first = false;
    }
}
?>
<main id="yhdr-blog-archive">
    <section class="page-header blog-hero">
        <div class="container">
            <p class="eyebrow"><?php esc_html_e('Building Insights', 'yhdr'); ?></p>
            <h1><?php esc_html_e('Advice We’d Give Our Own Family', 'yhdr'); ?></h1>
            <div class="page-header__intro">
                <p><?php esc_html_e('Honest, technical guidance on building in Uganda — costs, engineering, water and design — from the team on the ground.', 'yhdr'); ?>
                </p>
            </div>
        </div>
        <?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--grey', 'wave-divider--bg-navy-mid'); ?>
    </section>

    <?php if ($featured_post) : ?>
        <section class="blog-featured-wrap container">
            <?php yhdr_render_blog_featured_card($featured_post); ?>
        </section>
    <?php endif; ?>

    <section class="blog-archive">
        <div class="container">
            <?php if ($has_categories) : ?>
                <div class="blog__filters" role="group"
                    aria-label="<?php esc_attr_e('Filter articles by category', 'yhdr'); ?>">
                    <button type="button" class="blog__filter-btn is-active"
                        data-filter="all"><?php esc_html_e('All', 'yhdr'); ?></button>
                    <?php foreach ($categories as $category) : ?>
                        <button type="button" class="blog__filter-btn"
                            data-filter="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (! empty($grid_posts)) : ?>
                <div class="blog__grid" data-animate-group>
                    <?php foreach ($grid_posts as $post) : ?>
                        <?php yhdr_render_blog_card($post); ?>
                    <?php endforeach; ?>
                </div>
                <?php the_posts_pagination(); ?>
            <?php elseif (! $featured_post) : ?>
                <p><?php esc_html_e('Our first articles are on the way — check back soon.', 'yhdr'); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <section class="blog-cta">
        <div class="container">
            <h2><?php esc_html_e('Got a Question About Your Build?', 'yhdr'); ?></h2>
            <p><?php esc_html_e('Skip the comment section — ask the engineers directly. Advice costs nothing.', 'yhdr'); ?>
            </p>
            <div class="blog-cta__actions">
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary">
                    <?php esc_html_e('Ask Us Anything', 'yhdr'); ?>
                </a>
                <a href="<?php echo yhdr_whatsapp_url(); ?>" target="_blank" rel="noopener" class="btn btn-secondary">
                    <?php esc_html_e('Chat on WhatsApp', 'yhdr'); ?>
                </a>
            </div>
        </div>
    </section>
    <?php yhdr_wave_divider('up', 'wave-divider--why-top', 'wave-divider--navy-dark', 'wave-divider--bg-grey'); ?>

</main>