<?php
/**
 * Search results template
 */
if (!defined('ABSPATH')) exit;

get_header();

$search_term = get_search_query();

$search_post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'any';
$search_args = [
    'post_type'      => $search_post_type,
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    's'              => $search_term,
    'orderby'        => 'date',
    'order'          => 'DESC',
];
$search_query = new WP_Query($search_args);
$is_blog_search = ($search_post_type === 'post');

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

$blog_page_url = get_permalink(get_option('page_for_posts')) ?: home_url('/');
?>

<section class="blog-grid-section" style="padding:100px 0;" aria-label="<?= esc_attr__('Výsledky vyhledávání', 'cba') ?>">
    <div class="container max-w-content mx-auto">

        <div class="text-center mb-10 lg:mb-14">
            <h1 class="font-semibold text-dark mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;">
                <?= sprintf(esc_html__('Výsledky pro „%s"', 'cba'), esc_html($search_term)) ?>
            </h1>

            <?php if (!empty($filter_cats)) : ?>
                <div class="flex flex-wrap justify-center gap-2.5 mt-8">
                    <a href="<?= esc_url($blog_page_url) ?>" class="inline-block px-5 py-2.5 rounded-full border border-dark text-dark text-sm no-underline hover:border-primary hover:text-primary transition-colors duration-200" style="font-family:Montserrat,sans-serif;">
                        <?= esc_html__('Vše', 'cba') ?>
                    </a>
                    <?php foreach ($filter_cats as $cat) : ?>
                        <a href="<?= esc_url(get_category_link($cat->term_id)) ?>" class="inline-block px-5 py-2.5 rounded-full border border-dark text-dark text-sm no-underline hover:border-primary hover:text-primary transition-colors duration-200" style="font-family:Montserrat,sans-serif;">
                            <?= esc_html($cat->name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= esc_url(home_url('/')) ?>" method="get" class="flex justify-center mt-6">
                <div class="flex w-full" style="max-width:550px;">
                    <?php if ($is_blog_search) : ?>
                        <input type="hidden" name="post_type" value="post">
                    <?php endif; ?>
                    <input type="text" name="s" value="<?= esc_attr($search_term) ?>" placeholder="<?= esc_attr__('Vyhledejte článek na konkrétní téma', 'cba') ?>" class="flex-1 bg-white rounded-l-full px-5 text-sm text-dark outline-none placeholder:text-dark/25" style="font-family:Montserrat,sans-serif;height:37px;">
                    <button type="submit" class="bg-dark text-white rounded-r-full px-5 text-sm hover:opacity-80 transition-opacity flex-shrink-0" style="font-family:Montserrat,sans-serif;height:37px;">
                        <?= esc_html__('Hledat', 'cba') ?>
                    </button>
                </div>
            </form>
        </div>

        <?php if ($search_query->have_posts()) : ?>
            <div class="blog-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="blog-grid">
                <?php while ($search_query->have_posts()) : $search_query->the_post();
                    get_template_part('parts/repeats/loop', 'blog-card');
                endwhile; ?>
            </div>

            <?php if ($search_query->max_num_pages > 1) : ?>
                <div class="text-center mt-12 blog-load-more-wrap">
                    <button type="button" id="blog-load-more" class="inline-flex items-center justify-center bg-primary px-8 py-4 rounded-full text-white text-sm font-semibold uppercase tracking-[0.7px] hover:opacity-80 transition-opacity cursor-pointer" style="font-family:Montserrat,sans-serif;" data-page="1" data-max="<?= $search_query->max_num_pages ?>">
                        <?= esc_html__('Načíst další články', 'cba') ?>
                    </button>
                </div>

                <script>
                (function() {
                    var btn = document.getElementById('blog-load-more');
                    if (!btn) return;

                    var gridAjax = <?= json_encode([
                        'ajaxurl'    => admin_url('admin-ajax.php'),
                        'query_vars' => json_encode($search_args),
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
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

        <?php else : ?>
            <p class="text-center text-dark/60 text-lg" style="font-family:Montserrat,sans-serif;">
                <?= sprintf(esc_html__('Pro „%s" nebyly nalezeny žádné výsledky.', 'cba'), esc_html($search_term)) ?>
            </p>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
