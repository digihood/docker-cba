<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('slider_heading');
$subheading = get_field('slider_subheading');
$items      = get_field('slider_items');

if (empty($items)) return;
$real_count = count($items);
while (count($items) < 8) {
    $items = array_merge($items, array_slice($items, 0, 8 - count($items)));
}
$count = count($items);
?>
<section class="stats-section overflow-hidden" style="background:#fff3db;padding-top:100px;padding-bottom:40px;" aria-label="<?= esc_attr($heading ?: 'Statistiky') ?>">

    <?php if ($heading || $subheading) : ?>
        <div class="container max-w-content mx-auto text-center mb-6">
            <?php if ($heading) : ?>
                <h2 class="font-semibold text-dark leading-none mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;"><?= esc_html($heading) ?></h2>
            <?php endif; ?>
            <?php if ($subheading) : ?>
                <p class="text-dark/70 text-lg" style="font-family:Montserrat,sans-serif;"><?= esc_html($subheading) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="swiper stats-swiper pt-5" style="overflow:visible;">
        <div class="swiper-wrapper">

            <?php foreach ($items as $i => $item) :
                if (empty($item['title'])) continue;
                preg_match('/^(\d+)/', $item['title'], $num_match);
                $number = $num_match[1] ?? '';
                $rest   = $number ? ltrim(substr($item['title'], strlen($number))) : $item['title'];
                $bg_image = !empty($item['bg_image']) ? $item['bg_image'] : null;
            ?>
            <div class="swiper-slide" style="width:598px;height:380px;">
                <div class="stats-card relative rounded-[25px] p-10 flex flex-col justify-between w-full h-full transition-all duration-300"
                     style="background:white;box-shadow:0 4px 24px rgba(19,87,107,0.10);">

                    <?php if ($bg_image) : ?>
                        <div class="absolute inset-0 rounded-[25px] overflow-hidden pointer-events-none stats-card-bg" aria-hidden="true">
                            <?= wp_get_attachment_image($bg_image['ID'], 'medium_large', false, [
                                'class'   => 'w-full h-full object-cover',
                                'alt'     => '',
                                'loading' => 'lazy',
                            ]) ?>
                            <div class="absolute inset-0" style="background:rgba(255,243,219,0.8);"></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($item['icon'])) : ?>
                        <div class="h-[54px] flex items-start relative z-10">
                            <?= wp_get_attachment_image($item['icon']['ID'], [54, 54], false, [
                                'class'   => 'h-full w-auto object-contain',
                                'alt'     => '',
                            ]) ?>
                        </div>
                    <?php else : ?>
                        <div class="flex items-end h-[54px] w-full gap-[3px] relative z-10" aria-hidden="true">
                            <?php
                            $heights = [18,26,16,32,24,38,20,44,30,22,34,40,28,36,16,32];
                            foreach ($heights as $h) : ?>
                                <div class="rounded-sm flex-1" style="height:<?= $h ?>px;background:<?= $h >= 36 ? '#ff6b6b' : 'rgba(19,87,107,0.12)' ?>;"></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="flex flex-col gap-[10px] relative z-10">
                        <?php if ($number) : ?>
                            <p style="font-family:Montserrat,sans-serif;font-weight:600;line-height:1;letter-spacing:-3.2px;font-size:0;">
                                <span style="font-size:64px;color:#ff6b6b;"><?= esc_html($number) ?></span><span class="text-dark" style="font-size:64px;"><?= esc_html($rest) ?></span>
                            </p>
                        <?php else : ?>
                            <p class="font-semibold text-dark" style="font-family:Montserrat,sans-serif;font-size:44px;line-height:1;"><?= esc_html($item['title']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($item['text'])) : ?>
                            <p class="text-dark" style="font-size:16px;line-height:1.4;font-family:Montserrat,sans-serif;max-width:341px;">
                                <?= nl2br(esc_html($item['text'])) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($item['link'])) : ?>
                        <a href="<?= esc_url($item['link']['url']) ?>"
                           class="inline-flex items-center justify-center bg-primary text-white rounded-[50px] px-8 py-4 text-xs font-semibold uppercase tracking-[0.05em] no-underline hover:bg-primary-dark transition-colors w-fit relative z-10"
                           style="font-family:Montserrat,sans-serif;"
                           <?= !empty($item['link']['target']) ? 'target="' . esc_attr($item['link']['target']) . '"' : '' ?>>
                            <?= esc_html($item['link']['title'] ?: 'Zjistit více') ?>
                        </a>
                    <?php else : ?>
                        <div></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <?php if ($real_count > 1) : ?>
            <div class="stats-pagination flex justify-center items-center gap-[6px]" style="margin-top:24px;">
                <?php for ($d = 0; $d < $real_count; $d++) : ?>
                    <button type="button" class="stats-dot<?= $d === 0 ? ' active' : '' ?>" data-index="<?= $d ?>" aria-label="Slide <?= $d + 1 ?>"></button>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

</section>

<style>
.stats-swiper .swiper-slide { opacity:0.5; transform:scale(0.92); transition:opacity 0.3s,transform 0.3s; }
.stats-swiper .swiper-slide-active { opacity:1; transform:scale(1); }
.stats-swiper .swiper-slide:not(.swiper-slide-active) .stats-card { background:transparent; box-shadow:none; }
.stats-dot { width:10px; height:10px; background:transparent; border:2px solid #FF6B6B; border-radius:9999px; transition:all 0.3s; cursor:pointer; padding:0; }
.stats-dot.active { width:20px; height:20px; background:#FF6B6B; border-color:#FF6B6B; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper === 'undefined') return;
    var realCount = <?= $real_count ?>;
    var dots = document.querySelectorAll('.stats-dot');

    var sw = new Swiper('.stats-swiper', {
        slidesPerView: 'auto',
        centeredSlides: true,
        spaceBetween: 25,
        loop: true,
        speed: 500,
        grabCursor: true,
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
