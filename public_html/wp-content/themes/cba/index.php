<?php
/**
 * Blog page template (/clanky/)
 */
if (!defined('ABSPATH')) exit;

get_header();

// --- 1. Articles section (same as HP) ---
$featured_query = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 4,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

$all_posts   = [];
$featured_ids = [];
while ($featured_query->have_posts()) {
    $featured_query->the_post();
    $words = str_word_count(strip_tags(get_the_content()));
    $all_posts[] = [
        'title'     => get_the_title(),
        'permalink' => get_permalink(),
        'thumb_id'  => get_post_thumbnail_id(),
        'cats'      => get_the_category(),
        'excerpt'   => get_the_excerpt(),
        'read_time' => max(1, round($words / 200)),
    ];
    $featured_ids[] = get_the_ID();
}
wp_reset_postdata();

$featured   = $all_posts[0] ?? null;
$list_posts = array_slice($all_posts, 1, 3);

$filter_cats = get_categories([
    'hide_empty' => true,
    'number'     => 7,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'exclude'    => [get_option('default_category')],
]);
$filter_cats = array_values(array_filter($filter_cats, function ($c) {
    return !in_array($c->slug, ['uncategorized', 'nezarazene', 'bez-kategorie'], true);
}));

$blog_title = get_the_title(get_option('page_for_posts')) ?: __('Články', 'cba');
?>

<!-- ============ ARTICLES SECTION ============ -->
<section class="articles-section" style="background:#fff3db;padding:100px 0;" aria-label="<?= esc_attr($blog_title) ?>">
    <div class="container max-w-content mx-auto">

        <div class="text-center mb-10 lg:mb-14">
            <h1 class="font-semibold text-dark mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;"><?= esc_html($blog_title) ?></h1>

            <?php if (!empty($filter_cats)) : ?>
                <div class="flex flex-wrap justify-center gap-2.5 mt-8">
                    <?php foreach ($filter_cats as $cat) : ?>
                        <a href="<?= esc_url(get_category_link($cat->term_id)) ?>" class="inline-block px-5 py-2.5 rounded-full border border-dark text-dark text-sm no-underline hover:border-primary hover:text-primary transition-colors duration-200" style="font-family:Montserrat,sans-serif;">
                            <?= esc_html($cat->name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= esc_url(home_url('/')) ?>" method="get" class="flex justify-center mt-6">
                <div class="flex w-full" style="max-width:550px;">
                    <input type="hidden" name="post_type" value="post">
                    <input type="text" name="s" placeholder="<?= esc_attr__('Vyhledejte článek na konkrétní téma', 'cba') ?>" class="flex-1 bg-white rounded-l-full px-5 text-sm text-dark outline-none placeholder:text-dark/25" style="font-family:Montserrat,sans-serif;height:37px;" style="font-family:Montserrat,sans-serif;">
                    <button type="submit" class="bg-dark text-white rounded-r-full px-5 text-sm hover:opacity-80 transition-opacity flex-shrink-0" style="font-family:Montserrat,sans-serif;height:37px;" style="font-family:Montserrat,sans-serif;">
                        <?= esc_html__('Hledat', 'cba') ?>
                    </button>
                </div>
            </form>
        </div>

        <?php if ($featured) : ?>
            <div class="flex flex-col lg:flex-row items-stretch justify-between gap-6 lg:gap-5 py-10">

                <?php $has_thumb = !empty($featured['thumb_id']); ?>
                <article class="article-featured relative rounded-[20px] overflow-hidden group flex flex-col <?= $has_thumb ? '' : 'bg-dark' ?>" style="min-width:598px;">
                    <?php if ($has_thumb) : ?>
                        <a href="<?= esc_url($featured['permalink']) ?>" class="absolute inset-0 no-underline" tabindex="-1" aria-hidden="true">
                            <?= wp_get_attachment_image($featured['thumb_id'], 'large', false, [
                                'class'   => 'w-full h-full object-cover',
                                'alt'     => esc_attr($featured['title']),
                                'loading' => 'eager',
                            ]) ?>
                            <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(169,147,109,0.95) 0%,rgba(255,243,219,0) 58%);"></div>
                        </a>
                    <?php else : ?>
                        <div class="absolute inset-0 bg-dark" aria-hidden="true"></div>
                    <?php endif; ?>
                    <div class="absolute top-7 left-[56px] z-10 flex items-center gap-1.5 text-white text-sm font-bold" style="font-family:Montserrat,sans-serif;">
                        <?php d1g1B::icon('doba-cteni', 'w-[19px] h-[19px] [&_path]:stroke-white'); ?>
                        <?= $featured['read_time'] ?> min. čtení
                    </div>
                    <div class="relative z-10 mt-auto p-10 lg:p-14">
                        <h2 class="font-bold text-white text-2xl lg:text-[34px] leading-tight mb-4" style="font-family:Montserrat,sans-serif;">
                            <a href="<?= esc_url($featured['permalink']) ?>" class="text-white no-underline hover:opacity-80 transition-opacity">
                                <?= esc_html($featured['title']) ?>
                            </a>
                        </h2>
                        <?php if (!empty($featured['excerpt'])) : ?>
                            <p class="text-white/85 text-lg leading-relaxed font-medium" style="font-family:Montserrat,sans-serif;max-width:450px;">
                                <?= esc_html(wp_trim_words($featured['excerpt'], 18, '...')) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </article>

                <?php if (!empty($list_posts)) : ?>
                    <div class="flex flex-col gap-[25px] max-w-[600px]">
                        <?php foreach ($list_posts as $post) : ?>
                            <article class="article-list bg-white rounded-[20px] overflow-hidden flex flex-col hover:shadow-card transition-shadow duration-300 group">
                                <div class="p-7 flex flex-col justify-between h-full">
                                    <h3 class="font-semibold text-dark text-xl leading-snug group-hover:text-primary transition-colors" style="font-family:Montserrat,sans-serif;">
                                        <a href="<?= esc_url($post['permalink']) ?>" class="text-dark no-underline hover:text-primary">
                                            <?= esc_html($post['title']) ?>
                                        </a>
                                    </h3>
                                    <?php if (!empty($post['excerpt'])) : ?>
                                        <p class="text-dark/60 text-sm leading-relaxed line-clamp-2" style="font-family:Montserrat,sans-serif;">
                                            <?= esc_html(wp_trim_words($post['excerpt'], 22, '...')) ?>
                                        </p>
                                    <?php endif; ?>
                                    <div class="flex items-center gap-1.5 text-dark text-xs" style="font-family:Montserrat,sans-serif;">
                                        <?php d1g1B::icon('doba-cteni', 'w-3 h-3'); ?>
                                        <?= $post['read_time'] ?> min. čtení
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

    </div>
</section>

<?php
// --- 2. Grid section ("Další články") ---
$grid_args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'post__not_in'   => $featured_ids,
    'orderby'        => 'date',
    'order'          => 'DESC',
];
$grid_query = new WP_Query($grid_args);

if ($grid_query->have_posts()) : ?>

<section class="blog-grid-section" style="padding:100px 0;" aria-label="<?= esc_attr__('Další články', 'cba') ?>">
    <div class="container max-w-content mx-auto">

        <div class="text-center mb-10 lg:mb-14">
            <h2 class="font-semibold text-dark mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;"><?= esc_html__('Další články', 'cba') ?></h2>
        </div>

        <div class="blog-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="blog-grid">
            <?php while ($grid_query->have_posts()) : $grid_query->the_post();
                get_template_part('parts/repeats/loop', 'blog-card');
            endwhile; ?>
        </div>

        <?php if ($grid_query->max_num_pages > 1) : ?>
            <div class="text-center mt-12 blog-load-more-wrap">
                <button type="button" id="blog-load-more" class="inline-flex items-center justify-center bg-primary px-8 py-4 rounded-full text-white text-sm font-semibold uppercase tracking-[0.7px] hover:opacity-80 transition-opacity cursor-pointer" style="font-family:Montserrat,sans-serif;" data-page="1" data-max="<?= $grid_query->max_num_pages ?>">
                    <?= esc_html__('Načíst další články', 'cba') ?>
                </button>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php endif;
wp_reset_postdata();
?>

<script>
(function() {
    var btn = document.getElementById('blog-load-more');
    if (!btn) return;

    var gridAjax = <?= json_encode([
        'ajaxurl'    => admin_url('admin-ajax.php'),
        'query_vars' => json_encode($grid_args),
        'template'   => 'blog-card',
    ]) ?>;

    btn.addEventListener('click', function() {
        var page = parseInt(btn.getAttribute('data-page'));
        var max  = parseInt(btn.getAttribute('data-max'));
        var next = page + 1;

        if (btn.classList.contains('loading') || next > max) return;
        btn.classList.add('loading');
        btn.style.opacity = '0.5';

        var fd = new FormData();
        fd.append('action', 'ajax_pagination');
        fd.append('query_vars', gridAjax.query_vars);
        fd.append('page', next);
        fd.append('template', gridAjax.template);

        fetch(gridAjax.ajaxurl, { method: 'POST', body: fd })
            .then(function(r) { return r.text(); })
            .then(function(html) {
                document.getElementById('blog-grid').insertAdjacentHTML('beforeend', html);
                btn.setAttribute('data-page', next);
                btn.classList.remove('loading');
                btn.style.opacity = '1';
                if (next >= max) {
                    btn.closest('.blog-load-more-wrap').style.display = 'none';
                }
            })
            .catch(function() {
                btn.classList.remove('loading');
                btn.style.opacity = '1';
            });
    });
})();
</script>

<?php get_footer(); ?>
