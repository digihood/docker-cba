<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('slider_heading');
$subheading = get_field('slider_subheading');
$items      = get_field('slider_items');

if (empty($items)) return;

$unique_id = 'info-slider-' . uniqid();
$total     = count($items);
?>
<section class="info-slider-section py-16 lg:py-24 bg-gray-light overflow-hidden" aria-label="<?= esc_attr($heading ?: 'Informace') ?>">
    <div class="container max-w-content mx-auto">

        <?php if ($heading || $subheading) : ?>
            <div class="section-header text-center mb-12 lg:mb-16">
                <?php if ($heading) : ?>
                    <h2 class="text-dark font-bold mb-4 text-h2-sm md:text-h2-md"><?= esc_html($heading) ?></h2>
                <?php endif; ?>
                <?php if ($subheading) : ?>
                    <p class="text-gray-dark text-lg max-w-2xl mx-auto"><?= esc_html($subheading) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Slider with faded side previews -->
        <div class="info-slider-wrapper relative" id="<?= esc_attr($unique_id) ?>">

            <!-- Side fade overlays -->
            <div class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-gray-light to-transparent z-10 pointer-events-none"></div>
            <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-gray-light to-transparent z-10 pointer-events-none"></div>

            <div class="info-slider-track overflow-hidden">
                <div class="info-slider-inner flex transition-transform duration-500 ease-in-out" data-slider-track>

                    <?php foreach ($items as $index => $item) :
                        if (empty($item['title'])) continue;
                    ?>
                        <div class="info-slide flex-none w-full flex justify-center px-4 lg:px-12">
                            <div class="bg-white rounded-2xl p-10 lg:p-14 shadow-card max-w-2xl w-full text-center">

                                <!-- Icon -->
                                <?php if (!empty($item['icon'])) : ?>
                                    <div class="flex justify-center mb-8">
                                        <div class="w-20 h-20 rounded-2xl bg-primary/10 flex items-center justify-center">
                                            <?= wp_get_attachment_image($item['icon']['ID'], [48, 48], false, [
                                                'class' => 'w-12 h-12 object-contain',
                                                'alt'   => '',
                                            ]) ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="flex justify-center mb-8">
                                        <div class="w-20 h-20 rounded-2xl bg-primary/10 flex items-center justify-center">
                                            <span class="text-primary text-3xl font-bold"><?= ($index + 1) ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Stat/title text -->
                                <h3 class="text-dark font-bold text-2xl lg:text-3xl leading-snug mb-5">
                                    <?= esc_html($item['title']) ?>
                                </h3>

                                <!-- Description -->
                                <?php if (!empty($item['text'])) : ?>
                                    <p class="text-gray-dark text-lg leading-relaxed mb-9">
                                        <?= esc_html($item['text']) ?>
                                    </p>
                                <?php endif; ?>

                                <!-- CTA button -->
                                <?php if (!empty($item['link'])) : ?>
                                    <a
                                        href="<?= esc_url($item['link']['url']) ?>"
                                        class="button primary rounded-full !py-3.5 !px-8 font-semibold no-underline hover:no-underline inline-flex items-center gap-2 uppercase text-sm tracking-wide"
                                        <?= !empty($item['link']['target']) ? 'target="' . esc_attr($item['link']['target']) . '"' : '' ?>
                                    >
                                        <?= esc_html($item['link']['title'] ?: __('Zjistit více', 'cba')) ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Dots navigation -->
            <?php if ($total > 1) : ?>
                <div class="flex items-center justify-center gap-4 mt-10">
                    <button class="w-10 h-10 rounded-full border border-gray-mid bg-white hover:bg-primary hover:border-primary hover:text-white flex items-center justify-center transition-all duration-300" aria-label="Předchozí" data-slider-prev>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="flex gap-2" data-slider-dots>
                        <?php for ($i = 0; $i < $total; $i++) : ?>
                            <button
                                class="h-2.5 rounded-full transition-all duration-300 <?= $i === 0 ? 'bg-primary w-6' : 'bg-gray-mid w-2.5' ?>"
                                data-dot="<?= $i ?>"
                                aria-label="Slide <?= ($i + 1) ?>"
                            ></button>
                        <?php endfor; ?>
                    </div>
                    <button class="w-10 h-10 rounded-full border border-gray-mid bg-white hover:bg-primary hover:border-primary hover:text-white flex items-center justify-center transition-all duration-300" aria-label="Další" data-slider-next>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
(function() {
    const slider = document.getElementById('<?= esc_js($unique_id) ?>');
    if (!slider) return;
    const track = slider.querySelector('[data-slider-track]');
    const dots = slider.querySelectorAll('[data-dot]');
    const prevBtn = slider.querySelector('[data-slider-prev]');
    const nextBtn = slider.querySelector('[data-slider-next]');
    if (!track) return;

    let current = 0;
    const total = track.children.length;

    function goTo(index) {
        current = ((index % total) + total) % total;
        const slideWidth = track.parentElement.offsetWidth;
        track.style.transform = 'translateX(-' + (current * slideWidth) + 'px)';
        dots.forEach(function(d, i) {
            d.classList.toggle('bg-primary', i === current);
            d.classList.toggle('bg-gray-mid', i !== current);
            d.style.width = i === current ? '24px' : '10px';
        });
    }

    if (prevBtn) prevBtn.addEventListener('click', function() { goTo(current - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function() { goTo(current + 1); });
    dots.forEach(function(d) { d.addEventListener('click', function() { goTo(parseInt(d.dataset.dot)); }); });

    // Auto-advance every 6 seconds
    setInterval(function() { goTo(current + 1); }, 6000);
})();
</script>
