<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('slider_heading');
$subheading = get_field('slider_subheading');
$items      = get_field('slider_items');

if (empty($items)) return;

$count      = count($items);
// Aktivní = prostřední karta (index floor((n-1)/2))
$active_idx = (int) floor(($count - 1) / 2);

// Vždy zobrazujeme PŘESNĚ 3 sloty: vlevo–střed–vpravo (jako Figma peek efekt)
$left_idx   = ($active_idx - 1 + $count) % $count;
$right_idx  = ($active_idx + 1) % $count;

$display = [
    ['item' => $items[$left_idx],  'active' => false],
    ['item' => $items[$active_idx],'active' => true],
    ['item' => $items[$right_idx], 'active' => false],
];
?>
<section class="stats-section py-16 lg:py-24 overflow-hidden" style="background:#fff3db;" aria-label="<?= esc_attr($heading ?: 'Statistiky') ?>">

    <?php if ($heading || $subheading) : ?>
        <div class="container max-w-content mx-auto text-center mb-10 lg:mb-14">
            <?php if ($heading) : ?>
                <h2 class="font-semibold text-dark leading-none mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;"><?= esc_html($heading) ?></h2>
            <?php endif; ?>
            <?php if ($subheading) : ?>
                <p class="text-dark/70 text-lg" style="font-family:Montserrat,sans-serif;"><?= esc_html($subheading) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!--
        Peek slider: 3 karty (598px + 25px mezery) = 1844px celkem
        Sekce overflow-hidden ořízne kraje → outer karty zčásti přesahují
        justify-center centruje skupinu, klíčem je overflow-hidden na sekci
    -->
    <div class="flex items-center justify-center pt-5 pb-10" style="gap:25px;">

        <?php foreach ($display as $slot) :
            $item      = $slot['item'];
            $is_active = $slot['active'];
            if (empty($item['title'])) continue;

            // Extrahuj číslo z nadpisu pro velký stat (př. "8 z 10 lidí")
            preg_match('/^(\d+)/', $item['title'], $num_match);
            $number = $num_match[1] ?? '';
            $rest   = $number ? ltrim(substr($item['title'], strlen($number))) : $item['title'];

            $bg_image = !empty($item['bg_image']) ? $item['bg_image'] : null;
        ?>

        <?php if ($is_active) : ?>
            <!-- AKTIVNÍ KARTA – bílá, stín -->
            <div class="flex-shrink-0 bg-white rounded-[25px] p-10 flex flex-col justify-between relative"
                 style="width:598px;height:380px;box-shadow:0 4px 24px rgba(19,87,107,0.10);">

                <!-- Ikona / bar-chart vizualizace -->
                <?php if (!empty($item['icon'])) : ?>
                    <div class="h-[54px] flex items-start">
                        <?= wp_get_attachment_image($item['icon']['ID'], [54, 54], false, [
                            'class'   => 'h-full w-auto object-contain',
                            'alt'     => '',
                        ]) ?>
                    </div>
                <?php else : ?>
                    <!-- Placeholder bar chart -->
                    <div class="flex items-end h-[54px] w-full gap-[3px]" aria-hidden="true">
                        <?php
                        $heights = [18,26,16,32,24,38,20,44,30,22,34,40,28,36,16,32];
                        foreach ($heights as $h) : ?>
                            <div class="rounded-sm flex-1" style="height:<?= $h ?>px;background:<?= $h >= 36 ? '#ff6b6b' : 'rgba(19,87,107,0.12)' ?>;"></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Stat text -->
                <div class="flex flex-col gap-[10px]">
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

                <!-- CTA odkaz -->
                <?php if (!empty($item['link'])) : ?>
                    <a href="<?= esc_url($item['link']['url']) ?>"
                       class="inline-flex items-center justify-center bg-primary text-white rounded-[50px] px-8 py-4 text-xs font-semibold uppercase tracking-[0.05em] no-underline hover:bg-primary-dark transition-colors w-fit"
                       style="font-family:Montserrat,sans-serif;"
                       <?= !empty($item['link']['target']) ? 'target="' . esc_attr($item['link']['target']) . '"' : '' ?>>
                        <?= esc_html($item['link']['title'] ?: 'Zjistit více') ?>
                    </a>
                <?php else : ?>
                    <div></div>
                <?php endif; ?>
            </div>

        <?php else : ?>
            <!-- NEAKTIVNÍ KARTA – faded, vanilla bg, volitelné fotopozadí -->
            <div class="flex-shrink-0 relative rounded-[25px] p-10 flex items-end"
                 style="width:598px;height:380px;opacity:0.5;">

                <?php if ($bg_image) : ?>
                    <div class="absolute inset-0 rounded-[25px] overflow-hidden pointer-events-none" aria-hidden="true">
                        <?= wp_get_attachment_image($bg_image['ID'], 'medium_large', false, [
                            'class'   => 'w-full h-full object-cover',
                            'alt'     => '',
                            'loading' => 'lazy',
                        ]) ?>
                        <div class="absolute inset-0" style="background:rgba(255,243,219,0.8);"></div>
                        <div class="absolute inset-0" style="background:#fff3db;mix-blend-mode:color;"></div>
                    </div>
                <?php else : ?>
                    <!-- Bez fotky: bílé pozadí se stínem → viditelné proti vanilla pozadí sekce -->
                    <div class="absolute inset-0 rounded-[25px]" style="background:white;box-shadow:0 4px 24px rgba(19,87,107,0.07);" aria-hidden="true"></div>
                <?php endif; ?>

                <div class="relative z-10 opacity-50">
                    <?php if ($number) : ?>
                        <p style="font-family:Montserrat,sans-serif;font-size:44px;font-weight:600;line-height:1;letter-spacing:-2.2px;color:white;width:218px;">
                            <?= esc_html($number) ?><span style="color:white;"><?= esc_html($rest) ?></span>
                        </p>
                        <?php if (!empty($item['text'])) : ?>
                            <p style="font-family:Montserrat,sans-serif;font-size:16px;line-height:1.4;color:white;margin-top:54px;max-width:341px;">
                                <?= esc_html($item['text']) ?>
                            </p>
                        <?php endif; ?>
                    <?php else : ?>
                        <p class="font-semibold" style="font-family:Montserrat,sans-serif;font-size:20px;line-height:1.4;color:white;"><?= esc_html($item['title']) ?></p>
                        <?php if (!empty($item['text'])) : ?>
                            <p style="font-family:Montserrat,sans-serif;font-size:16px;line-height:1.4;color:white;margin-top:12px;max-width:341px;">
                                <?= esc_html($item['text']) ?>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php endforeach; ?>
    </div>

    <!-- Navigation dots -->
    <?php if ($count > 1) : ?>
        <div class="flex justify-center gap-2 mt-2">
            <?php for ($i = 0; $i < $count; $i++) : ?>
                <span class="inline-block rounded-full"
                      style="<?= $i === $active_idx
                          ? 'width:24px;height:10px;background:#13576b;'
                          : 'width:10px;height:10px;background:rgba(19,87,107,0.3);' ?>"></span>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</section>
