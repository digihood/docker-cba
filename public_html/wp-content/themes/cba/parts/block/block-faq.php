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
                    <div class="faq-item bg-white rounded-xl overflow-hidden shadow-sm" data-faq-item>
                        <button
                            class="faq-question w-full flex items-center justify-between gap-4 px-6 py-5 text-left font-semibold text-dark hover:text-primary transition-colors duration-200 focus:outline-none"
                            aria-expanded="<?= $is_open ? 'true' : 'false' ?>"
                            aria-controls="<?= esc_attr($item_id) ?>"
                            data-faq-trigger
                        >
                            <span class="text-base leading-snug"><?= esc_html($item['question']) ?></span>
                            <span class="faq-icon flex-shrink-0 w-8 h-8 rounded-full bg-gray-light flex items-center justify-center transition-transform duration-300 <?= $is_open ? 'rotate-45 !bg-primary' : '' ?>">
                                <svg class="w-4 h-4 <?= $is_open ? 'text-white' : 'text-dark' ?>" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </span>
                        </button>
                        <div
                            id="<?= esc_attr($item_id) ?>"
                            class="faq-answer overflow-hidden transition-all duration-300 <?= $is_open ? 'max-h-screen' : 'max-h-0' ?>"
                            data-faq-answer
                        >
                            <div class="px-6 pb-6 text-gray-dark leading-relaxed text-base">
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

    faq.querySelectorAll('[data-faq-trigger]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const item = btn.closest('[data-faq-item]');
            const answer = item.querySelector('[data-faq-answer]');
            const icon = btn.querySelector('.faq-icon');
            const svgPath = btn.querySelector('svg');
            const isOpen = btn.getAttribute('aria-expanded') === 'true';

            // Zavřít všechny ostatní
            faq.querySelectorAll('[data-faq-item]').forEach(function(other) {
                const otherBtn = other.querySelector('[data-faq-trigger]');
                const otherAnswer = other.querySelector('[data-faq-answer]');
                const otherIcon = otherBtn.querySelector('.faq-icon');
                otherBtn.setAttribute('aria-expanded', 'false');
                otherAnswer.style.maxHeight = '0';
                otherIcon.classList.remove('rotate-45', '!bg-primary');
                otherIcon.querySelector('svg').classList.remove('text-white');
                otherIcon.querySelector('svg').classList.add('text-dark');
            });

            // Otevřít aktuální pokud byl zavřený
            if (!isOpen) {
                btn.setAttribute('aria-expanded', 'true');
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.classList.add('rotate-45', '!bg-primary');
                svgPath.classList.add('text-white');
                svgPath.classList.remove('text-dark');
            }
        });
    });

    // Inicializace prvního otevřeného
    const first = faq.querySelector('[data-faq-answer]');
    if (first) first.style.maxHeight = first.scrollHeight + 'px';
})();
</script>
