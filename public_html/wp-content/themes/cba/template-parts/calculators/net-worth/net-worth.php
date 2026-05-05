<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$nw_config     = net_worth_get_config( get_the_ID() );
$nw_categories = $nw_config['categories'] ?? array();
$nw_items      = $nw_config['items'] ?? array();
?>

<div class="net-worth">

    <!-- Hero -->
    <div class="net-worth__hero">
        <h1 class="net-worth__hero-title"><?php the_title(); ?></h1>
        <p class="net-worth__hero-desc">Zjistěte svou skutečnou finanční sílu.</p>
    </div>

    <!-- Main layout: form left, results panel right -->
    <div class="net-worth__layout">
        <div class="net-worth__form-col">

            <!-- Monthly expenses input -->
            <div class="net-worth__section" id="nw-section-expenses">
                <h2 class="net-worth__section-title">Měsíční výdaje</h2>
                <p>Zadejte průměrné měsíční výdaje pro výpočet krizové odolnosti.</p>
                <div class="net-worth__item net-worth__item--expenses">
                    <label for="nw-monthly-expenses">Měsíční výdaje</label>
                    <div class="net-worth__input-row">
                        <input type="number" id="nw-monthly-expenses" name="monthly_expenses" value="30000" min="0" max="1000000" step="1000">
                        <span class="net-worth__unit">Kč</span>
                        <button type="button" class="net-worth__tooltip-btn" data-tooltip="Odhad vašich běžných měsíčních výdajů. Používáme jej pro výpočet, na kolik měsíců by vám stačily likvidní prostředky při výpadku příjmů.">?</button>
                    </div>
                </div>
            </div>

            <!-- Scale visual: assets vs liabilities -->
            <div class="net-worth__scale" id="nw-scale">
                <div class="net-worth__scale-side net-worth__scale-side--assets">
                    <span class="net-worth__scale-label">Aktiva</span>
                    <span class="net-worth__scale-amount" id="nw-scale-assets">0 Kč</span>
                </div>
                <div class="net-worth__scale-bar">
                    <div class="net-worth__scale-fill net-worth__scale-fill--assets" id="nw-scale-bar-assets"></div>
                    <div class="net-worth__scale-fill net-worth__scale-fill--liabilities" id="nw-scale-bar-liabilities"></div>
                </div>
                <div class="net-worth__scale-side net-worth__scale-side--liabilities">
                    <span class="net-worth__scale-label">Závazky</span>
                    <span class="net-worth__scale-amount" id="nw-scale-liabilities">0 Kč</span>
                </div>
            </div>

            <!-- ASSETS section -->
            <div class="net-worth__section net-worth__section--assets">
                <h2 class="net-worth__section-title net-worth__section-title--assets">&#x1F7E2; Aktiva</h2>

                <?php
                $sorted_categories = (array) $nw_categories;
                usort( $sorted_categories, function( $a, $b ) {
                    return ( $a['order'] ?? 0 ) <=> ( $b['order'] ?? 0 );
                } );

                foreach ( $sorted_categories as $cat ) :
                    if ( ! ( $cat['active'] ?? true ) ) continue;
                    if ( ( $cat['type'] ?? '' ) !== 'asset' ) continue;
                    $cat_slug  = $cat['slug'] ?? $cat['id'] ?? '';
                    $cat_items = array_filter( $nw_items, function( $i ) use ( $cat_slug ) {
                        return ( $i['category'] ?? '' ) === $cat_slug
                            && ( $i['active'] ?? true )
                            && ( $i['type'] ?? '' ) === 'asset';
                    } );
                    if ( empty( $cat_items ) ) continue;
                    usort( $cat_items, function( $a, $b ) {
                        return ( $a['order'] ?? 0 ) <=> ( $b['order'] ?? 0 );
                    } );
                ?>
                <div class="net-worth__category" data-category="<?php echo esc_attr( $cat_slug ); ?>" data-type="asset">
                    <h3 class="net-worth__category-title">
                        <button type="button" class="net-worth__category-toggle">
                            <span><?php echo esc_html( $cat['name'] ?? '' ); ?></span>
                            <span class="net-worth__category-total" id="nw-cat-<?php echo esc_attr( $cat_slug ); ?>">0 Kč</span>
                            <span class="net-worth__toggle-icon">&#9660;</span>
                        </button>
                    </h3>
                    <div class="net-worth__category-items">
                        <?php foreach ( $cat_items as $item ) : ?>
                        <div class="net-worth__item"
                            data-slug="<?php echo esc_attr( $item['slug'] ?? $item['id'] ?? '' ); ?>"
                            data-type="asset"
                            data-is-liquid="<?php echo ( $item['is_liquid'] ?? false ) ? 'true' : 'false'; ?>">
                            <div class="net-worth__item-header">
                                <label for="nw-<?php echo esc_attr( $item['slug'] ?? $item['id'] ?? '' ); ?>" class="net-worth__item-label">
                                    <?php echo esc_html( $item['name'] ?? '' ); ?>
                                </label>
                                <?php if ( ! empty( $item['tooltip'] ) ) : ?>
                                <button type="button" class="net-worth__tooltip-btn" data-tooltip="<?php echo esc_attr( $item['tooltip'] ); ?>">?</button>
                                <?php endif; ?>
                            </div>
                            <div class="net-worth__input-row">
                                <input
                                    type="number"
                                    id="nw-<?php echo esc_attr( $item['slug'] ?? $item['id'] ?? '' ); ?>"
                                    name="<?php echo esc_attr( $item['slug'] ?? $item['id'] ?? '' ); ?>"
                                    value="<?php echo esc_attr( $item['default_value'] ?? 0 ); ?>"
                                    min="0"
                                    step="1000"
                                    class="net-worth__input"
                                    placeholder="0">
                                <span class="net-worth__unit">Kč</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- LIABILITIES section -->
            <div class="net-worth__section net-worth__section--liabilities">
                <h2 class="net-worth__section-title net-worth__section-title--liabilities">&#x1F534; Závazky</h2>

                <?php foreach ( $sorted_categories as $cat ) :
                    if ( ! ( $cat['active'] ?? true ) ) continue;
                    if ( ( $cat['type'] ?? '' ) !== 'liability' ) continue;
                    $cat_slug  = $cat['slug'] ?? $cat['id'] ?? '';
                    $cat_items = array_filter( $nw_items, function( $i ) use ( $cat_slug ) {
                        return ( $i['category'] ?? '' ) === $cat_slug
                            && ( $i['active'] ?? true )
                            && ( $i['type'] ?? '' ) === 'liability';
                    } );
                    if ( empty( $cat_items ) ) continue;
                    usort( $cat_items, function( $a, $b ) {
                        return ( $a['order'] ?? 0 ) <=> ( $b['order'] ?? 0 );
                    } );
                ?>
                <div class="net-worth__category" data-category="<?php echo esc_attr( $cat_slug ); ?>" data-type="liability">
                    <h3 class="net-worth__category-title">
                        <button type="button" class="net-worth__category-toggle">
                            <span><?php echo esc_html( $cat['name'] ?? '' ); ?></span>
                            <span class="net-worth__category-total" id="nw-cat-<?php echo esc_attr( $cat_slug ); ?>">0 Kč</span>
                            <span class="net-worth__toggle-icon">&#9660;</span>
                        </button>
                    </h3>
                    <div class="net-worth__category-items">
                        <?php foreach ( $cat_items as $item ) : ?>
                        <div class="net-worth__item"
                            data-slug="<?php echo esc_attr( $item['slug'] ?? $item['id'] ?? '' ); ?>"
                            data-type="liability">
                            <div class="net-worth__item-header">
                                <label for="nw-<?php echo esc_attr( $item['slug'] ?? $item['id'] ?? '' ); ?>" class="net-worth__item-label">
                                    <?php echo esc_html( $item['name'] ?? '' ); ?>
                                </label>
                                <?php if ( ! empty( $item['tooltip'] ) ) : ?>
                                <button type="button" class="net-worth__tooltip-btn" data-tooltip="<?php echo esc_attr( $item['tooltip'] ); ?>">?</button>
                                <?php endif; ?>
                            </div>
                            <div class="net-worth__input-row">
                                <input
                                    type="number"
                                    id="nw-<?php echo esc_attr( $item['slug'] ?? $item['id'] ?? '' ); ?>"
                                    name="<?php echo esc_attr( $item['slug'] ?? $item['id'] ?? '' ); ?>"
                                    value="0"
                                    min="0"
                                    step="1000"
                                    class="net-worth__input"
                                    placeholder="0">
                                <span class="net-worth__unit">Kč</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Actions -->
            <div class="net-worth__actions">
                <button type="button" id="nw-email-btn" class="net-worth__btn net-worth__btn--secondary">&#128231; Poslat výsledky na e-mail</button>
                <?php if ( is_user_logged_in() ) : ?>
                <button type="button" id="nw-snapshot-btn" class="net-worth__btn net-worth__btn--outline">&#128248; Uložit snapshot</button>
                <?php endif; ?>
            </div>

            <!-- Snapshot history for logged-in users -->
            <?php if ( is_user_logged_in() ) : ?>
            <div class="net-worth__snapshot" id="nw-snapshot-section" style="display:none;">
                <h3>Vývoj čistého jmění v čase</h3>
                <canvas id="nw-snapshot-chart" height="120"></canvas>
            </div>
            <div id="nw-snapshot-prompt" style="display:none;"></div>
            <?php else : ?>
            <!-- CTA for guests -->
            <div class="net-worth__cta-register">
                <p>&#x1F4A1; <strong>Přihlaste se</strong> a váš výpočet se automaticky uloží. Budete moci sledovat vývoj čistého jmění v čase.</p>
                <a href="/prihlaseni/" class="net-worth__btn net-worth__btn--primary">Přihlásit se</a>
            </div>
            <?php endif; ?>

        </div><!-- /.net-worth__form-col -->

        <!-- Results panel (sticky right column) -->
        <div class="net-worth__results-col">
            <div class="net-worth__results-panel" id="nw-results-panel">

                <!-- Main net worth -->
                <div class="net-worth__result-header">
                    <span class="net-worth__result-header-label">Čisté jmění</span>
                    <span class="net-worth__result-header-value" id="nw-net-worth">0 Kč</span>
                </div>

                <!-- Summary cards -->
                <div class="net-worth__card">
                    <span class="net-worth__card-label">CELKOVÁ AKTIVA</span>
                    <span class="net-worth__card-value" id="nw-total-assets">0 Kč</span>
                </div>
                <div class="net-worth__card">
                    <span class="net-worth__card-label">CELKOVÉ ZÁVAZKY</span>
                    <span class="net-worth__card-value" id="nw-total-liabilities">0 Kč</span>
                </div>

                <!-- Equity ratio -->
                <div class="net-worth__card">
                    <span class="net-worth__card-label">POMĚR VLASTNÍHO MAJETKU</span>
                    <span class="net-worth__card-value" id="nw-equity-ratio">0 %</span>
                    <span class="net-worth__card-hint">Kolik procent vašeho majetku je skutečně vaše.</span>
                </div>

                <!-- Debt barometer -->
                <div class="net-worth__barometer">
                    <div class="net-worth__barometer-header">
                        <span>Poměr dluhu k majetku</span>
                        <span id="nw-debt-ratio-pct">0 %</span>
                    </div>
                    <div class="net-worth__barometer-track">
                        <div class="net-worth__barometer-fill" id="nw-barometer-fill"></div>
                    </div>
                    <div class="net-worth__barometer-status" id="nw-debt-status"></div>
                </div>

                <!-- Liquidity -->
                <div class="net-worth__card">
                    <span class="net-worth__card-label">INDEX LIKVIDITY</span>
                    <span class="net-worth__card-value" id="nw-liquidity-index">0 %</span>
                    <span class="net-worth__card-hint">Jaká část majetku je rychle dostupná.</span>
                </div>

                <!-- Crisis resilience -->
                <div class="net-worth__resilience" id="nw-resilience-block">
                    <span class="net-worth__card-label">KRIZOVÁ ODOLNOST</span>
                    <span class="net-worth__resilience-value" id="nw-resilience-value">&#8211; měsíců</span>
                    <span class="net-worth__resilience-hint" id="nw-resilience-hint"></span>
                </div>

                <!-- Donut chart -->
                <div class="net-worth__chart" id="nw-chart-section">
                    <h4 class="net-worth__chart-title">Rozložení aktiv</h4>
                    <canvas id="nw-donut-chart" height="200"></canvas>
                </div>

                <!-- Diversification warning -->
                <div class="net-worth__warning" id="nw-diversification-warning" style="display:none;">
                    &#x26A0;&#xFE0F; <strong>Nízká diverzifikace</strong>
                    <p id="nw-diversification-text"></p>
                </div>

                <!-- Result message -->
                <div class="net-worth__message" id="nw-result-message" style="display:none;">
                    <h4 id="nw-message-title"></h4>
                    <p id="nw-message-text"></p>
                </div>

                <!-- Recommended content -->
                <div class="net-worth__recommendation" id="nw-recommendations" style="display:none;">
                    <h4>Doporučené čtení</h4>
                    <div id="nw-recommendation-list"></div>
                </div>

                <!-- Save status -->
                <div id="nw-save-status" style="display:none;"></div>

            </div>
        </div><!-- /.net-worth__results-col -->

    </div><!-- /.net-worth__layout -->

    <!-- Email modal -->
    <div class="net-worth__modal" id="nw-email-modal" style="display:none;" role="dialog" aria-modal="true">
        <div class="net-worth__modal-inner">
            <button type="button" class="net-worth__modal-close" id="nw-modal-close">&times;</button>
            <h3>Poslat výsledky na e-mail</h3>
            <p>Zadejte e-mailovou adresu, na kterou vám zašleme přehled vašeho čistého jmění.</p>
            <input type="email" id="nw-modal-email" placeholder="vas@email.cz" class="net-worth__modal-input">
            <button type="button" id="nw-modal-send" class="net-worth__btn net-worth__btn--primary">Odeslat</button>
            <div id="nw-modal-status"></div>
        </div>
    </div>
    <div class="net-worth__modal-overlay" id="nw-modal-overlay" style="display:none;"></div>

    <!-- Tooltip popup -->
    <div class="net-worth__tooltip-popup" id="nw-tooltip-popup" style="display:none;"></div>

</div><!-- /.net-worth -->
