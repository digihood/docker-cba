<?php
/**
 * Archive template (categories, tags, dates)
 */
if (!defined('ABSPATH')) exit;

get_header();

$archive_args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

$archive_title = '';
$current_cat_id = 0;

if (is_category()) {
    $cat = get_queried_object();
    $archive_title  = $cat->name;
    $current_cat_id = $cat->term_id;
    $archive_args['cat'] = $cat->term_id;
} elseif (is_tag()) {
    $tag = get_queried_object();
    $archive_title = $tag->name;
    $archive_args['tag_id'] = $tag->term_id;
} else {
    $archive_title = get_the_archive_title();
}

$archive_query = new WP_Query($archive_args);

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
?>

<section class="blog-grid-section" style="padding:100px 0;" aria-label="<?= esc_attr($archive_title) ?>">
    <div class="container max-w-content mx-auto">

        <div class="text-center mb-10 lg:mb-14">
            <h1 class="font-semibold text-dark mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;"><?= esc_html($archive_title) ?></h1>

            <?php if (!empty($filter_cats)) : ?>
                <div class="flex flex-wrap justify-center gap-2.5 mt-8">
                    <?php
                    $blog_page_url = get_permalink(get_option('page_for_posts')) ?: home_url('/');
                    ?>
                    <a href="<?= esc_url($blog_page_url) ?>" class="inline-block px-5 py-2.5 rounded-full border text-sm no-underline transition-colors duration-200 border-dark text-dark hover:border-primary hover:text-primary" style="font-family:Montserrat,sans-serif;">
                        <?= esc_html__('Vše', 'cba') ?>
                    </a>
                    <?php foreach ($filter_cats as $cat) :
                        $is_active = ($cat->term_id === $current_cat_id);
                    ?>
                        <a href="<?= esc_url(get_category_link($cat->term_id)) ?>" class="inline-block px-5 py-2.5 rounded-full border text-sm no-underline transition-colors duration-200 <?= $is_active ? 'border-primary bg-primary text-white' : 'border-dark text-dark hover:border-primary hover:text-primary' ?>" style="font-family:Montserrat,sans-serif;">
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

        <?php if ($archive_query->have_posts()) : ?>
            <div class="blog-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="blog-grid">
                <?php while ($archive_query->have_posts()) : $archive_query->the_post();
                    get_template_part('parts/repeats/loop', 'blog-card');
                endwhile; ?>
            </div>

            <?php if ($archive_query->max_num_pages > 1) : ?>
                <div class="text-center mt-12 blog-load-more-wrap">
                    <button type="button" id="blog-load-more" class="inline-flex items-center justify-center bg-primary px-8 py-4 rounded-full text-white text-sm font-semibold uppercase tracking-[0.7px] hover:opacity-80 transition-opacity cursor-pointer" style="font-family:Montserrat,sans-serif;" data-page="1" data-max="<?= $archive_query->max_num_pages ?>">
                        <?= esc_html__('Načíst další články', 'cba') ?>
                    </button>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <p class="text-center text-dark/60 text-lg" style="font-family:Montserrat,sans-serif;">
                <?= esc_html__('V této kategorii zatím nejsou žádné články.', 'cba') ?>
            </p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
</section>

<script>
(function() {
    var btn = document.getElementById('blog-load-more');
    if (!btn) return;

    var gridAjax = <?= json_encode([
        'ajaxurl'    => admin_url('admin-ajax.php'),
        'query_vars' => json_encode($archive_args),
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
