<?php
if (!defined('ABSPATH')) exit;

$bg_image  = get_field('banner_bg_image');
$heading   = get_field('banner_heading');
$text      = get_field('banner_text');
$btn1      = get_field('banner_btn_primary');
$features  = get_field('banner_features');

if (!$heading) return;
?>
<section class="banner-section relative overflow-hidden"  aria-label="<?= esc_attr($heading) ?>">

    <!-- Background photo – bez overlay -->
    <?php if ($bg_image) : ?>
        <div class="absolute inset-0" aria-hidden="true">
            <?= wp_get_attachment_image($bg_image['ID'], 'large', false, [
                'class'   => 'w-full h-full object-cover',
                'alt'     => '',
                'loading' => 'lazy',
            ]) ?>
        </div>
    <?php else : ?>
        <div class="absolute inset-0 bg-dark" aria-hidden="true"></div>
    <?php endif; ?>

    <!-- Union shape – salmon filled -->
    <style>
    .banner-union { height:67%; bottom:5%; right:-35%; left:auto; top:auto; }
    @media (min-width:1024px) { .banner-union { height:100%; top:-20%; left:-20%; bottom:auto; right:auto; } }
    </style>
    <div class="absolute pointer-events-none banner-union" style="aspect-ratio:1/1;" aria-hidden="true">
        <svg viewBox="-228 -124 1106 1106" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M-159.078 -55.1933C-90.0007 -124.269 21.9954 -124.269 91.0725 -55.1924L324.514 178.246L557.93 -55.166C627.007 -124.242 739.003 -124.242 808.08 -55.166L808.195 -55.0527C877.272 14.0237 877.272 126.019 808.195 195.096L574.779 428.508L808.179 661.905C877.256 730.982 877.256 842.976 808.179 912.053L808.066 912.167C738.988 981.243 626.992 981.243 557.914 912.167L324.514 678.77L91.0881 912.193C22.0109 981.27 -89.986 981.27 -159.063 912.193L-159.177 912.08C-228.254 843.004 -228.254 731.008 -159.177 661.932L74.2492 428.508L-159.192 195.069C-228.269 125.993 -228.269 13.9973 -159.192 -55.0791L-159.078 -55.1933Z" fill="#FF6B6B"/>
        </svg>
    </div>

    <!-- Content -->
    <div class="relative z-10 h-full flex flex-col" style="padding-top:445px;">
        <div class="container max-w-content mx-auto px-[50px]">
            <h2 class="font-bold text-white text-center tracking-[-6px]" style="font-size:clamp(4rem,8vw,120px);font-family:Montserrat,sans-serif;line-height:1;">
                <?= esc_html($heading) ?>
            </h2>
        </div>

        <!-- Frosted glass panel -->
        <div class="mx-auto mt-8 mb-[84px]" style="max-width:1120px;padding:0 20px;">
        <div class="flex flex-col items-center text-center lg:flex-row lg:items-center lg:text-left gap-8 lg:gap-16 rounded-[10px] px-8 py-8 lg:px-12 lg:py-9 w-full backdrop-blur-md bg-white/10 lg:h-[216px]">

            <!-- Description + CTA -->
            <div class="flex flex-col items-center lg:items-start gap-5 flex-1 max-w-[392px]">
                <?php if ($text) : ?>
                    <p class="text-white text-lg leading-relaxed font-medium" style="font-family:Montserrat,sans-serif;">
                        <?= esc_html($text) ?>
                    </p>
                <?php endif; ?>
                <?php if ($btn1) : ?>
                    <?php d1g1B::primary_link(
                        esc_html( $btn1['title'] ),
                        esc_url( $btn1['url'] ),
                        ! empty( $btn1['target'] ) ? [ 'target' => esc_attr( $btn1['target'] ) ] : []
                    ); ?>
                <?php else : ?>
                    <?php d1g1B::primary_link( esc_html__( 'Chci se vzdělávat', 'cba' ), esc_url( home_url( '/akademie' ) ) ); ?>
                <?php endif; ?>
            </div>

            <!-- Vertical divider -->
            <div class="hidden lg:block w-px self-stretch" style="background:rgba(255,255,255,0.2);"></div>

            <!-- Feature blocks (hidden on mobile) -->
            <?php
            $default_features = [
                ['title' => 'PRÉMIOVÉ ČLÁNKY'],
                ['title' => 'VĚDOMOSTNÍ KVÍZY'],
                ['title' => 'CERTIFIKÁT DOKONČENÍ'],
            ];
            $feat_list = !empty($features) ? $features : $default_features;
            foreach ($feat_list as $feat) :
                if (empty($feat['title'])) continue;
            ?>
                <div class="hidden lg:flex flex-col items-center gap-2 text-white text-center" style="min-width:108px;">
                    <div class="w-[60px] h-[60px] rounded-[10px] bg-primary flex-shrink-0"></div>
                    <div class="font-semibold text-center uppercase tracking-[0.06em]" style="font-size:16px;font-family:Montserrat,sans-serif;line-height:1.2;">
                        <?= esc_html($feat['title']) ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
        </div>
    </div>
</section>
