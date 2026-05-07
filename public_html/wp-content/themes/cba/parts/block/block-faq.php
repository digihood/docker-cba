<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('faq_heading');
$subheading = get_field('faq_subheading');
$items      = get_field('faq_items');

if (empty($items)) return;

$unique_id = 'faq-' . uniqid();
?>
<section class="faq-section py-16 lg:py-24 bg-gray-light" aria-label="<?= esc_attr($heading ?: 'FAQ') ?>">
    <div class="container max-w-content mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- Levá část: header -->
            <div class="faq-header lg:sticky lg:top-24 lg:self-start">
                <?php if ($heading) : ?>
                    <h2 class="text-dark font-bold text-h2-sm md:text-h2-md mb-4"><?= esc_html($heading) ?></h2>
                <?php endif; ?>
                <?php if ($subheading) : ?>
                    <p class="text-gray-dark text-lg leading-relaxed"><?= esc_html($subheading) ?></p>
                <?php endif; ?>
                <div class="mt-6 w-16 h-1 bg-primary rounded-full"></div>
            </div>

            <!-- Pravá část: akordeon -->
            <div class="faq-accordion lg:col-span-2 space-y-3" id="<?= esc_attr($unique_id) ?>">
                <?php foreach ($items as $index => $item) :
                    if (empty($item['question'])) continue;
                    $item_id = $unique_id . '-item-' . $index;
                    $is_open = ($index === 0);
                ?>
                    <div class="faq-item bg-white rounded-xl shadow-sm hover:shadow-card transition-shadow duration-300" data-faq-item>
                        <button
                            class="faq-question w-full flex items-center justify-between gap-4 px-6 py-5 text-left font-semibold text-dark transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 rounded-xl"
                            aria-expanded="<?= $is_open ? 'true' : 'false' ?>"
                            aria-controls="<?= esc_attr($item_id) ?>"
                            data-faq-trigger
                        >
                            <span class="text-[15px] lg:text-base leading-snug pr-2"><?= esc_html($item['question']) ?></span>
                            <span class="faq-icon flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center transition-transform duration-300 text-gray-dark <?= $is_open ? 'rotate-180 text-primary' : '' ?>">
                                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </button>
                        <div
                            id="<?= esc_attr($item_id) ?>"
                            class="faq-answer overflow-hidden transition-[max-height] duration-300"
                            data-faq-answer
                            style="max-height: <?= $is_open ? '2000px' : '0px' ?>;"
                        >
                            <div class="px-6 pb-6 text-gray-dark leading-relaxed text-[15px]">
                                <?php if (!empty($item['answer'])) : ?>
                                    <div class="prose prose-sm max-w-none">
                                        <?= wp_kses_post($item['answer']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    const faq = document.getElementById('<?= esc_js($unique_id) ?>');
    if (!faq) return;

    function measure(answer) {
        // Dočasně rozbalit, abychom dostali skutečnou výšku obsahu
        const prev = answer.style.maxHeight;
        answer.style.maxHeight = 'none';
        const h = answer.scrollHeight;
        answer.style.maxHeight = prev;
        return h;
    }

    function setOpen(item, open) {
        const btn = item.querySelector('[data-faq-trigger]');
        const answer = item.querySelector('[data-faq-answer]');
        const icon = btn.querySelector('.faq-icon');
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) {
            answer.style.maxHeight = measure(answer) + 'px';
            icon.classList.add('rotate-180', 'text-primary');
            icon.classList.remove('text-gray-dark');
        } else {
            answer.style.maxHeight = '0px';
            icon.classList.remove('rotate-180', 'text-primary');
            icon.classList.add('text-gray-dark');
        }
    }

    faq.querySelectorAll('[data-faq-trigger]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const item = btn.closest('[data-faq-item]');
            const isOpen = btn.getAttribute('aria-expanded') === 'true';
            faq.querySelectorAll('[data-faq-item]').forEach(function(other) {
                setOpen(other, false);
            });
            if (!isOpen) setOpen(item, true);
        });
    });

    // Inicializace prvního otevřeného – počkáme na DOM ready a fonts ready
    function init() {
        const firstItem = faq.querySelector('[data-faq-item]');
        if (firstItem) setOpen(firstItem, true);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    // Recalculate on resize (text-wrap may change height)
    let raf;
    window.addEventListener('resize', function() {
        cancelAnimationFrame(raf);
        raf = requestAnimationFrame(function() {
            const open = faq.querySelector('[data-faq-trigger][aria-expanded="true"]');
            if (open) setOpen(open.closest('[data-faq-item]'), true);
        });
    });
})();
</script>
