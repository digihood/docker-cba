<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('stories_heading');
$subheading = get_field('stories_subheading');
$count      = intval(get_field('stories_count') ?: 3);
$category   = get_field('stories_category');
$btn        = get_field('stories_btn');

$args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => max(3, $count),
    'orderby'        => 'date',
    'order'          => 'DESC',
];
if ($category) $args['cat'] = intval($category);

$posts = new WP_Query($args);
if (!$posts->have_posts()) return;

$all_stories = [];
while ($posts->have_posts()) {
    $posts->the_post();
    $cats = get_the_category();
    $all_stories[] = [
        'id'        => get_the_ID(),
        'title'     => get_the_title(),
        'permalink' => get_permalink(),
        'thumb_id'  => get_post_thumbnail_id(),
        'cat'       => !empty($cats) ? $cats[0] : null,
        'author'    => get_the_author(),
        'author_id' => get_the_author_meta('ID'),
        'date'      => get_the_date(),
    ];
}
wp_reset_postdata();
$real_count = count($all_stories);
while (count($all_stories) < 8) {
    $all_stories = array_merge($all_stories, array_slice($all_stories, 0, 8 - count($all_stories)));
}
?>
<section class="stories-section" style="background:#fff3db;padding:100px 0;" aria-label="<?= esc_attr($heading ?: 'Příběhy a inspirace') ?>">
    <div class="container max-w-content mx-auto">
        <div class="text-center mb-12 lg:mb-16">
            <?php if ($heading) : ?>
                <h2 class="font-semibold text-dark mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;"><?= esc_html($heading) ?></h2>
            <?php endif; ?>
            <?php if ($subheading) : ?>
                <p class="text-dark/70 text-lg" style="font-family:Montserrat,sans-serif;"><?= esc_html($subheading) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="stories-slider-wrap">
        <div class="flex items-center justify-center gap-6">
            <button class="stories-prev hidden lg:flex items-center justify-center w-10 h-10 opacity-60 hover:opacity-100 transition-opacity flex-shrink-0 rotate-180" aria-label="Předchozí">
                <?php d1g1B::icon('sipka-stories', 'w-[13px] h-[15px]'); ?>
            </button>

            <div class="swiper stories-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($all_stories as $story) : ?>
                    <div class="swiper-slide">
                        <article class="story-card relative flex flex-col justify-end gap-4 p-7 lg:p-10 rounded-[20px] overflow-hidden h-full">
                            <?php if ($story['thumb_id']) : ?>
                                <div class="absolute inset-0" aria-hidden="true">
                                    <?= wp_get_attachment_image($story['thumb_id'], 'medium_large', false, ['class' => 'w-full h-full object-cover', 'alt' => '', 'loading' => 'lazy']) ?>
                                    <div class="absolute inset-0" style="background:linear-gradient(to top,#13576b 0%,rgba(19,87,107,0) 60%);"></div>
                                </div>
                            <?php else : ?>
                                <div class="absolute inset-0 bg-dark" aria-hidden="true"></div>
                            <?php endif; ?>
                            <div class="relative z-10 flex flex-col gap-4">
                                <h3 class="font-bold text-white leading-tight" style="font-size:24px;font-family:Montserrat,sans-serif;">
                                    <a href="<?= esc_url($story['permalink']) ?>" class="text-white no-underline hover:opacity-80"><?= esc_html($story['title']) ?></a>
                                </h3>
                                <div class="inline-flex items-center justify-center bg-primary px-4 py-1.5 text-white text-sm font-semibold w-fit" style="font-family:Montserrat,sans-serif;">
                                    <?= esc_html($story['author']) ?>
                                </div>
                                <?php if ($story['cat']) : ?>
                                    <p class="text-white/80 text-sm leading-snug" style="font-family:Montserrat,sans-serif;"><?= esc_html($story['cat']->name) ?></p>
                                <?php endif; ?>
                            </div>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="stories-next hidden lg:flex items-center justify-center w-10 h-10 opacity-60 hover:opacity-100 transition-opacity flex-shrink-0" aria-label="Další">
                <?php d1g1B::icon('sipka-stories', 'w-[13px] h-[15px]'); ?>
            </button>
        </div>
    </div>

    <div class="container max-w-content mx-auto">
        <?php if ($real_count > 1) : ?>
            <div class="stories-pagination flex justify-center items-center gap-[6px]" style="margin-top:24px;">
                <?php for ($d = 0; $d < $real_count; $d++) : ?>
                    <button type="button" class="stories-dot<?= $d === 0 ? ' active' : '' ?>" data-index="<?= $d ?>" aria-label="Slide <?= $d + 1 ?>"></button>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <?php if ($btn) : ?>
            <div class="text-center mt-8">
                <?php d1g1B::primary_link(
                    esc_html( $btn['title'] ),
                    esc_url( $btn['url'] ),
                    ! empty( $btn['target'] ) ? [ 'target' => esc_attr( $btn['target'] ) ] : []
                ); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.stories-slider-wrap { max-width:1200px; margin:0 auto; }
.stories-swiper { overflow:hidden; padding:40px 0; }
.stories-swiper .swiper-wrapper { align-items:center; }
.stories-swiper .swiper-slide {
    height:400px;
    transition:transform 0.5s ease, opacity 0.5s ease;
    transform:scale(0.95);
    opacity:0.4;
}
.stories-swiper .swiper-slide-active {
    transform:scale(1);
    opacity:1;
}
@media (min-width:1024px) {
    .stories-swiper .swiper-slide {
        width:350px;
        height:530px;
        transform:scale(0.82);
        opacity:0.5;
    }
    .stories-swiper .swiper-slide-active {
        transform:scale(1.12);
        opacity:1;
        z-index:2;
    }
}
.stories-dot { width:10px; height:10px; background:transparent; border:2px solid #FF6B6B; border-radius:9999px; transition:all 0.3s; cursor:pointer; padding:0; }
.stories-dot.active { width:20px; height:20px; background:#FF6B6B; border-color:#FF6B6B; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper === 'undefined') return;
    var realCount = <?= $real_count ?>;
    var dots = document.querySelectorAll('.stories-dot');

    var sw = new Swiper('.stories-swiper', {
        centeredSlides: true,
        loop: true,
        speed: 500,
        grabCursor: true,
        navigation: { prevEl: '.stories-prev', nextEl: '.stories-next' },
        breakpoints: {
            0: { slidesPerView: 1.2, spaceBetween: 10 },
            1024: { slidesPerView: 'auto', spaceBetween: 20 }
        },
        on: {
            slideChange: function() {
                var idx = this.realIndex % realCount;
                dots.forEach(function(d, i) { d.classList.toggle('active', i === idx); });
            }
        }
    });

    dots.forEach(function(dot) {
        dot.addEventListener('click', function() {
            sw.slideToLoop(parseInt(this.dataset.index));
        });
    });
});
</script>
