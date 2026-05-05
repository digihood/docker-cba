<?php
if (!defined('ABSPATH')) exit;

// === SEKCE ODBORNÍKŮ (součást zápatí, zobrazuje se vždy) ===
$experts_heading = function_exists('get_field') ? get_field('experts_heading', 'option') : '';
$experts_desc    = function_exists('get_field') ? get_field('experts_desc', 'option') : '';
$experts_list    = function_exists('get_field') ? get_field('experts_list', 'option') : [];
$experts_form    = function_exists('get_field') ? get_field('experts_cf7_shortcode', 'option') : '';
$experts_form_h  = function_exists('get_field') ? get_field('experts_form_heading', 'option') : 'Zeptejte se';
?>

<!-- Sekce: Zeptejte se odborníků -->
<section class="experts-section bg-dark py-16 lg:py-24" aria-label="Zeptejte se odborníků">
    <div class="container max-w-content mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            <!-- Levá část: Info o odbornících -->
            <div class="experts-info">
                <?php if ($experts_heading) : ?>
                    <h2 class="text-white text-h2-sm md:text-h2-md font-bold mb-4 leading-tight">
                        <?= esc_html($experts_heading) ?>
                    </h2>
                <?php else : ?>
                    <h2 class="text-white text-h2-sm md:text-h2-md font-bold mb-4 leading-tight">
                        <?= esc_html__('Zeptejte se odborníků', 'cba') ?>
                    </h2>
                <?php endif; ?>

                <?php if ($experts_desc) : ?>
                    <p class="text-gray text-lg mb-8"><?= esc_html($experts_desc) ?></p>
                <?php else : ?>
                    <p class="text-gray text-lg mb-8"><?= esc_html__('Máte otázku k financím? Naši odborníci jsou tu pro vás.', 'cba') ?></p>
                <?php endif; ?>

                <!-- Fotky odborníků -->
                <?php if (!empty($experts_list)) : ?>
                    <div class="experts-avatars flex flex-wrap gap-6 mt-8">
                        <?php foreach ($experts_list as $expert) : ?>
                            <div class="expert-item flex flex-col items-center text-center">
                                <?php if (!empty($expert['photo'])) : ?>
                                    <div class="w-16 h-16 rounded-full overflow-hidden mb-2 border-2 border-primary">
                                        <?= wp_get_attachment_image($expert['photo']['ID'], [64, 64], false, [
                                            'class' => 'w-full h-full object-cover',
                                            'alt'   => esc_attr($expert['name']),
                                        ]) ?>
                                    </div>
                                <?php else : ?>
                                    <div class="w-16 h-16 rounded-full bg-dark-muted flex items-center justify-center mb-2 border-2 border-primary">
                                        <svg class="w-8 h-8 text-gray" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($expert['name'])) : ?>
                                    <span class="text-white text-sm font-semibold"><?= esc_html($expert['name']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($expert['role'])) : ?>
                                    <span class="text-gray text-xs"><?= esc_html($expert['role']) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pravá část: Formulář -->
            <div class="experts-form bg-dark-card rounded-2xl p-8 lg:p-10">
                <?php if ($experts_form_h) : ?>
                    <h3 class="text-white text-h3-sm font-bold mb-6"><?= esc_html($experts_form_h) ?></h3>
                <?php endif; ?>

                <?php if ($experts_form) : ?>
                    <div class="experts-cf7">
                        <?= do_shortcode($experts_form) ?>
                    </div>
                <?php else : ?>
                    <!-- Výchozí formulář pokud není CF7 -->
                    <form class="experts-default-form space-y-4" method="post">
                        <div>
                            <input type="text" name="expert_name" placeholder="<?= esc_attr__('Vaše jméno', 'cba') ?>" class="!bg-dark-muted !border-dark-muted !text-white placeholder-gray focus:!border-primary">
                        </div>
                        <div>
                            <input type="email" name="expert_email" placeholder="<?= esc_attr__('E-mailová adresa', 'cba') ?>" class="!bg-dark-muted !border-dark-muted !text-white placeholder-gray focus:!border-primary">
                        </div>
                        <div>
                            <textarea name="expert_message" placeholder="<?= esc_attr__('Vaše otázka...', 'cba') ?>" rows="4" class="!bg-dark-muted !border-dark-muted !text-white placeholder-gray focus:!border-primary !h-[120px]"></textarea>
                        </div>
                        <button type="submit" class="button primary w-full rounded-full font-semibold">
                            <?= esc_html__('Odeslat dotaz', 'cba') ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Zápatí – 4 sloupce s widgety -->
<div class="footer-widgets bg-dark border-t border-dark-muted py-12 lg:py-16">
    <div class="container max-w-content mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">

            <!-- Widget sloupec 1 -->
            <div class="footer-widget-col">
                <?php dynamic_sidebar('footer-1'); ?>
            </div>

            <!-- Widget sloupec 2 -->
            <div class="footer-widget-col">
                <?php dynamic_sidebar('footer-2'); ?>
            </div>

            <!-- Widget sloupec 3 -->
            <div class="footer-widget-col">
                <?php dynamic_sidebar('footer-3'); ?>
            </div>

            <!-- Widget sloupec 4 + sociální sítě -->
            <div class="footer-widget-col">
                <?php dynamic_sidebar('footer-4'); ?>

                <!-- Sociální ikony z ACF Options -->
                <?php
                $social_icons = function_exists('get_field') ? get_field('social_icons', 'option') : [];
                if (!empty($social_icons)) : ?>
                    <div class="footer-social mt-4">
                        <div class="flex flex-wrap gap-3">
                            <?php foreach ($social_icons as $social) :
                                if (empty($social['url'])) continue;
                            ?>
                                <a
                                    href="<?= esc_url($social['url']) ?>"
                                    class="social-link flex items-center justify-center w-10 h-10 rounded-full bg-dark-muted hover:bg-primary transition-colors duration-300 no-underline"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="<?= esc_attr($social['name'] ?? '') ?>"
                                >
                                    <?php if (!empty($social['icon'])) : ?>
                                        <?= wp_get_attachment_image($social['icon']['ID'], [20, 20], false, [
                                            'class' => 'w-5 h-5 object-contain filter invert',
                                            'alt'   => esc_attr($social['name'] ?? ''),
                                        ]) ?>
                                    <?php else : ?>
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"/>
                                        </svg>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
