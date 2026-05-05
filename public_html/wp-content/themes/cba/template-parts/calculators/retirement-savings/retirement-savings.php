<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id      = get_the_ID();
$config       = retirement_savings_get_config( $post_id );
$inputs       = $config['default_inputs'];
$is_logged_in = is_user_logged_in();

// Helper to get field config safely
function rs_field( $inputs, $key, $attr, $fallback = '' ) {
    return isset( $inputs[ $key ][ $attr ] ) ? $inputs[ $key ][ $attr ] : $fallback;
}
?>

<div class="retirement-savings">

    <!-- Hero -->
    <div class="retirement-savings__hero">
        <div class="container">
            <h1 class="retirement-savings__hero-title"><?php the_title(); ?></h1>
            <p class="retirement-savings__hero-desc">Zjistěte, zda při současném tempu spoření dosáhnete na cílovou rentu v důchodu.</p>
        </div>
    </div>

    <!-- Validation error banner -->
    <div id="rs-error-banner" class="retirement-savings__error-banner" hidden>
        <div class="container"><span id="rs-error-text"></span></div>
    </div>

    <!-- Main layout -->
    <div class="retirement-savings__main">
        <div class="container">
            <div class="retirement-savings__layout">

                <!-- LEFT: Form -->
                <div class="retirement-savings__form-col">

                    <!-- Section: Základní údaje -->
                    <section class="retirement-savings__section">
                        <h2 class="retirement-savings__section-title">Základní údaje</h2>

                        <!-- current_age -->
                        <div class="retirement-savings__field" data-field="current_age">
                            <div class="retirement-savings__field-header">
                                <label for="rs-current-age" class="retirement-savings__field-label">
                                    <?php echo esc_html( rs_field( $inputs, 'current_age', 'label', 'Současný věk' ) ); ?>
                                </label>
                                <span class="retirement-savings__unit"><?php echo esc_html( rs_field( $inputs, 'current_age', 'unit', 'let' ) ); ?></span>
                                <?php if ( rs_field( $inputs, 'current_age', 'tooltip' ) ) : ?>
                                    <button type="button" class="retirement-savings__tooltip-btn" data-tip="<?php echo esc_attr( rs_field( $inputs, 'current_age', 'tooltip' ) ); ?>">?</button>
                                <?php endif; ?>
                            </div>
                            <div class="retirement-savings__slider-row">
                                <input
                                    type="range"
                                    id="rs-current-age-slider"
                                    min="<?php echo esc_attr( rs_field( $inputs, 'current_age', 'min', 18 ) ); ?>"
                                    max="<?php echo esc_attr( rs_field( $inputs, 'current_age', 'max', 80 ) ); ?>"
                                    step="<?php echo esc_attr( rs_field( $inputs, 'current_age', 'step', 1 ) ); ?>"
                                    value="<?php echo esc_attr( rs_field( $inputs, 'current_age', 'default', 45 ) ); ?>"
                                    class="retirement-savings__slider"
                                    aria-label="<?php echo esc_attr( rs_field( $inputs, 'current_age', 'label', 'Současný věk' ) ); ?>"
                                >
                                <input
                                    type="number"
                                    id="rs-current-age"
                                    name="current_age"
                                    min="<?php echo esc_attr( rs_field( $inputs, 'current_age', 'min', 18 ) ); ?>"
                                    max="<?php echo esc_attr( rs_field( $inputs, 'current_age', 'max', 80 ) ); ?>"
                                    step="<?php echo esc_attr( rs_field( $inputs, 'current_age', 'step', 1 ) ); ?>"
                                    value="<?php echo esc_attr( rs_field( $inputs, 'current_age', 'default', 45 ) ); ?>"
                                    class="retirement-savings__number-input"
                                >
                            </div>
                        </div>

                        <!-- retirement_age -->
                        <div class="retirement-savings__field" data-field="retirement_age">
                            <div class="retirement-savings__field-header">
                                <label for="rs-retirement-age" class="retirement-savings__field-label">
                                    <?php echo esc_html( rs_field( $inputs, 'retirement_age', 'label', 'Věk odchodu do důchodu' ) ); ?>
                                </label>
                                <span class="retirement-savings__unit"><?php echo esc_html( rs_field( $inputs, 'retirement_age', 'unit', 'let' ) ); ?></span>
                                <?php if ( rs_field( $inputs, 'retirement_age', 'tooltip' ) ) : ?>
                                    <button type="button" class="retirement-savings__tooltip-btn" data-tip="<?php echo esc_attr( rs_field( $inputs, 'retirement_age', 'tooltip' ) ); ?>">?</button>
                                <?php endif; ?>
                            </div>
                            <div class="retirement-savings__slider-row">
                                <input
                                    type="range"
                                    id="rs-retirement-age-slider"
                                    min="<?php echo esc_attr( rs_field( $inputs, 'retirement_age', 'min', 40 ) ); ?>"
                                    max="<?php echo esc_attr( rs_field( $inputs, 'retirement_age', 'max', 90 ) ); ?>"
                                    step="<?php echo esc_attr( rs_field( $inputs, 'retirement_age', 'step', 1 ) ); ?>"
                                    value="<?php echo esc_attr( rs_field( $inputs, 'retirement_age', 'default', 65 ) ); ?>"
                                    class="retirement-savings__slider"
                                    aria-label="<?php echo esc_attr( rs_field( $inputs, 'retirement_age', 'label', 'Věk odchodu do důchodu' ) ); ?>"
                                >
                                <input
                                    type="number"
                                    id="rs-retirement-age"
                                    name="retirement_age"
                                    min="<?php echo esc_attr( rs_field( $inputs, 'retirement_age', 'min', 40 ) ); ?>"
                                    max="<?php echo esc_attr( rs_field( $inputs, 'retirement_age', 'max', 90 ) ); ?>"
                                    step="<?php echo esc_attr( rs_field( $inputs, 'retirement_age', 'step', 1 ) ); ?>"
                                    value="<?php echo esc_attr( rs_field( $inputs, 'retirement_age', 'default', 65 ) ); ?>"
                                    class="retirement-savings__number-input"
                                >
                            </div>
                        </div>

                        <!-- payout_years -->
                        <div class="retirement-savings__field" data-field="payout_years">
                            <div class="retirement-savings__field-header">
                                <label for="rs-payout-years" class="retirement-savings__field-label">
                                    <?php echo esc_html( rs_field( $inputs, 'payout_years', 'label', 'Délka čerpání renty' ) ); ?>
                                </label>
                                <span class="retirement-savings__unit"><?php echo esc_html( rs_field( $inputs, 'payout_years', 'unit', 'let' ) ); ?></span>
                                <?php if ( rs_field( $inputs, 'payout_years', 'tooltip' ) ) : ?>
                                    <button type="button" class="retirement-savings__tooltip-btn" data-tip="<?php echo esc_attr( rs_field( $inputs, 'payout_years', 'tooltip' ) ); ?>">?</button>
                                <?php endif; ?>
                            </div>
                            <div class="retirement-savings__input-row">
                                <input
                                    type="number"
                                    id="rs-payout-years"
                                    name="payout_years"
                                    min="<?php echo esc_attr( rs_field( $inputs, 'payout_years', 'min', 1 ) ); ?>"
                                    max="<?php echo esc_attr( rs_field( $inputs, 'payout_years', 'max', 40 ) ); ?>"
                                    step="<?php echo esc_attr( rs_field( $inputs, 'payout_years', 'step', 1 ) ); ?>"
                                    value="<?php echo esc_attr( rs_field( $inputs, 'payout_years', 'default', 20 ) ); ?>"
                                    class="retirement-savings__number-input"
                                >
                            </div>
                        </div>

                    </section>

                    <!-- Section: Cíl v důchodu -->
                    <section class="retirement-savings__section">
                        <h2 class="retirement-savings__section-title">Cíl v důchodu</h2>

                        <!-- target_pension_now -->
                        <div class="retirement-savings__field" data-field="target_pension_now">
                            <div class="retirement-savings__field-header">
                                <label for="rs-target-pension-now" class="retirement-savings__field-label">
                                    <?php echo esc_html( rs_field( $inputs, 'target_pension_now', 'label', 'Cílová měsíční renta (dnešní Kč)' ) ); ?>
                                </label>
                                <span class="retirement-savings__unit"><?php echo esc_html( rs_field( $inputs, 'target_pension_now', 'unit', 'Kč' ) ); ?></span>
                                <?php if ( rs_field( $inputs, 'target_pension_now', 'tooltip' ) ) : ?>
                                    <button type="button" class="retirement-savings__tooltip-btn" data-tip="<?php echo esc_attr( rs_field( $inputs, 'target_pension_now', 'tooltip' ) ); ?>">?</button>
                                <?php endif; ?>
                            </div>
                            <div class="retirement-savings__input-row">
                                <input
                                    type="number"
                                    id="rs-target-pension-now"
                                    name="target_pension_now"
                                    min="<?php echo esc_attr( rs_field( $inputs, 'target_pension_now', 'min', 0 ) ); ?>"
                                    max="<?php echo esc_attr( rs_field( $inputs, 'target_pension_now', 'max', 500000 ) ); ?>"
                                    step="<?php echo esc_attr( rs_field( $inputs, 'target_pension_now', 'step', 500 ) ); ?>"
                                    value="<?php echo esc_attr( rs_field( $inputs, 'target_pension_now', 'default', 10000 ) ); ?>"
                                    class="retirement-savings__number-input retirement-savings__number-input--wide"
                                >
                            </div>
                        </div>

                    </section>

                    <!-- Section: Aktuální spoření -->
                    <section class="retirement-savings__section">
                        <h2 class="retirement-savings__section-title">Aktuální spoření</h2>

                        <!-- current_savings -->
                        <div class="retirement-savings__field" data-field="current_savings">
                            <div class="retirement-savings__field-header">
                                <label for="rs-current-savings" class="retirement-savings__field-label">
                                    <?php echo esc_html( rs_field( $inputs, 'current_savings', 'label', 'Aktuálně naspořeno' ) ); ?>
                                </label>
                                <span class="retirement-savings__unit"><?php echo esc_html( rs_field( $inputs, 'current_savings', 'unit', 'Kč' ) ); ?></span>
                                <?php if ( rs_field( $inputs, 'current_savings', 'tooltip' ) ) : ?>
                                    <button type="button" class="retirement-savings__tooltip-btn" data-tip="<?php echo esc_attr( rs_field( $inputs, 'current_savings', 'tooltip' ) ); ?>">?</button>
                                <?php endif; ?>
                            </div>
                            <div class="retirement-savings__input-row">
                                <input
                                    type="number"
                                    id="rs-current-savings"
                                    name="current_savings"
                                    min="<?php echo esc_attr( rs_field( $inputs, 'current_savings', 'min', 0 ) ); ?>"
                                    max="<?php echo esc_attr( rs_field( $inputs, 'current_savings', 'max', 100000000 ) ); ?>"
                                    step="<?php echo esc_attr( rs_field( $inputs, 'current_savings', 'step', 1000 ) ); ?>"
                                    value="<?php echo esc_attr( rs_field( $inputs, 'current_savings', 'default', 0 ) ); ?>"
                                    class="retirement-savings__number-input retirement-savings__number-input--wide"
                                >
                            </div>
                        </div>

                        <!-- monthly_contribution -->
                        <div class="retirement-savings__field" data-field="monthly_contribution">
                            <div class="retirement-savings__field-header">
                                <label for="rs-monthly-contribution" class="retirement-savings__field-label">
                                    <?php echo esc_html( rs_field( $inputs, 'monthly_contribution', 'label', 'Měsíční příspěvek' ) ); ?>
                                </label>
                                <span class="retirement-savings__unit"><?php echo esc_html( rs_field( $inputs, 'monthly_contribution', 'unit', 'Kč' ) ); ?></span>
                                <?php if ( rs_field( $inputs, 'monthly_contribution', 'tooltip' ) ) : ?>
                                    <button type="button" class="retirement-savings__tooltip-btn" data-tip="<?php echo esc_attr( rs_field( $inputs, 'monthly_contribution', 'tooltip' ) ); ?>">?</button>
                                <?php endif; ?>
                            </div>
                            <div class="retirement-savings__input-row">
                                <input
                                    type="number"
                                    id="rs-monthly-contribution"
                                    name="monthly_contribution"
                                    min="<?php echo esc_attr( rs_field( $inputs, 'monthly_contribution', 'min', 0 ) ); ?>"
                                    max="<?php echo esc_attr( rs_field( $inputs, 'monthly_contribution', 'max', 1000000 ) ); ?>"
                                    step="<?php echo esc_attr( rs_field( $inputs, 'monthly_contribution', 'step', 100 ) ); ?>"
                                    value="<?php echo esc_attr( rs_field( $inputs, 'monthly_contribution', 'default', 1000 ) ); ?>"
                                    class="retirement-savings__number-input retirement-savings__number-input--wide"
                                >
                            </div>
                        </div>

                    </section>

                    <!-- Section: Předpoklady výpočtu -->
                    <section class="retirement-savings__section">
                        <h2 class="retirement-savings__section-title">Předpoklady výpočtu</h2>
                        <p class="retirement-savings__section-note">Tyto hodnoty ovlivňují přesnost výpočtu. Upravte je podle svých očekávání.</p>

                        <!-- annual_return -->
                        <div class="retirement-savings__field" data-field="annual_return">
                            <div class="retirement-savings__field-header">
                                <label for="rs-annual-return" class="retirement-savings__field-label">
                                    <?php echo esc_html( rs_field( $inputs, 'annual_return', 'label', 'Roční výnos investic' ) ); ?>
                                </label>
                                <span class="retirement-savings__unit"><?php echo esc_html( rs_field( $inputs, 'annual_return', 'unit', '%' ) ); ?></span>
                                <?php if ( rs_field( $inputs, 'annual_return', 'tooltip' ) ) : ?>
                                    <button type="button" class="retirement-savings__tooltip-btn" data-tip="<?php echo esc_attr( rs_field( $inputs, 'annual_return', 'tooltip' ) ); ?>">?</button>
                                <?php endif; ?>
                            </div>
                            <div class="retirement-savings__input-row">
                                <input
                                    type="number"
                                    id="rs-annual-return"
                                    name="annual_return"
                                    min="<?php echo esc_attr( rs_field( $inputs, 'annual_return', 'min', 0 ) ); ?>"
                                    max="<?php echo esc_attr( rs_field( $inputs, 'annual_return', 'max', 30 ) ); ?>"
                                    step="<?php echo esc_attr( rs_field( $inputs, 'annual_return', 'step', 0.1 ) ); ?>"
                                    value="<?php echo esc_attr( rs_field( $inputs, 'annual_return', 'default', 5 ) ); ?>"
                                    class="retirement-savings__number-input retirement-savings__number-input--wide"
                                >
                            </div>
                        </div>

                        <!-- inflation_rate -->
                        <div class="retirement-savings__field" data-field="inflation_rate">
                            <div class="retirement-savings__field-header">
                                <label for="rs-inflation-rate" class="retirement-savings__field-label">
                                    <?php echo esc_html( rs_field( $inputs, 'inflation_rate', 'label', 'Roční inflace' ) ); ?>
                                </label>
                                <span class="retirement-savings__unit"><?php echo esc_html( rs_field( $inputs, 'inflation_rate', 'unit', '%' ) ); ?></span>
                                <?php if ( rs_field( $inputs, 'inflation_rate', 'tooltip' ) ) : ?>
                                    <button type="button" class="retirement-savings__tooltip-btn" data-tip="<?php echo esc_attr( rs_field( $inputs, 'inflation_rate', 'tooltip' ) ); ?>">?</button>
                                <?php endif; ?>
                            </div>
                            <div class="retirement-savings__input-row">
                                <input
                                    type="number"
                                    id="rs-inflation-rate"
                                    name="inflation_rate"
                                    min="<?php echo esc_attr( rs_field( $inputs, 'inflation_rate', 'min', 0 ) ); ?>"
                                    max="<?php echo esc_attr( rs_field( $inputs, 'inflation_rate', 'max', 20 ) ); ?>"
                                    step="<?php echo esc_attr( rs_field( $inputs, 'inflation_rate', 'step', 0.1 ) ); ?>"
                                    value="<?php echo esc_attr( rs_field( $inputs, 'inflation_rate', 'default', 3 ) ); ?>"
                                    class="retirement-savings__number-input retirement-savings__number-input--wide"
                                >
                            </div>
                        </div>

                    </section>

                    <!-- Share -->
                    <div class="retirement-savings__share">
                        <button type="button" id="rs-share-btn" class="retirement-savings__share-btn">
                            Zkopírovat odkaz na výpočet
                        </button>
                        <span id="rs-share-msg" class="retirement-savings__share-msg" hidden>Odkaz na výpočet byl zkopírován.</span>
                    </div>

                </div><!-- /.form-col -->

                <!-- RIGHT: Results sticky panel -->
                <div class="retirement-savings__results-col">

                    <div class="retirement-savings__results-panel" id="rs-results-panel">

                        <!-- Years to retirement -->
                        <div class="retirement-savings__result-header">
                            <span class="retirement-savings__years-label">Let do důchodu:</span>
                            <strong id="rs-years-to-retirement" class="retirement-savings__years-value">—</strong>
                        </div>

                        <!-- Target amount -->
                        <div class="retirement-savings__result-card">
                            <div class="retirement-savings__result-label">Potřebujete naspořit</div>
                            <div id="rs-target-amount" class="retirement-savings__result-amount">— Kč</div>
                            <div class="retirement-savings__result-hint">Cílová částka pro čerpání renty</div>
                        </div>

                        <!-- Projected amount -->
                        <div class="retirement-savings__result-card retirement-savings__result-card--projected">
                            <div class="retirement-savings__result-label">Pravděpodobně budete mít</div>
                            <div id="rs-projected-amount" class="retirement-savings__result-amount">— Kč</div>
                            <div class="retirement-savings__result-hint">Při současném tempu spoření</div>
                        </div>

                        <!-- Gap -->
                        <div id="rs-gap-block" class="retirement-savings__gap">
                            <div class="retirement-savings__gap-label">Důchodová mezera</div>
                            <div id="rs-gap-amount" class="retirement-savings__gap-amount">— Kč</div>
                            <div id="rs-gap-status-text" class="retirement-savings__gap-status"></div>
                        </div>

                        <!-- Chart -->
                        <div class="retirement-savings__chart">
                            <canvas id="rs-chart" height="200"></canvas>
                        </div>

                        <!-- Future value block -->
                        <div class="retirement-savings__future-value" id="rs-future-value-block">
                            <h3 class="retirement-savings__future-value-title">Budoucí hodnota peněz</h3>
                            <p id="rs-future-value-text" class="retirement-savings__future-value-text"></p>
                            <p class="retirement-savings__future-value-note">Neznamená to, že budete chtít vyšší životní úroveň. Znamená to, že kvůli inflaci budou stejné nákupy pravděpodobně stát více peněz.</p>
                        </div>

                        <!-- CTA recommendation -->
                        <div class="retirement-savings__cta" id="rs-cta-block">
                            <h3 id="rs-cta-title" class="retirement-savings__cta-title"></h3>
                            <p id="rs-cta-text" class="retirement-savings__cta-text"></p>
                            <p id="rs-additional-contribution" class="retirement-savings__cta-contribution" hidden></p>
                        </div>

                        <!-- Guest CTA -->
                        <?php if ( ! $is_logged_in ) : ?>
                            <div class="retirement-savings__guest-cta" id="rs-guest-cta">
                                <p>Uložte si výpočet — <a href="/prihlaseni/#register">vytvořte si účet zdarma</a> nebo <a href="/prihlaseni/">se přihlaste</a>.</p>
                            </div>
                        <?php endif; ?>

                    </div><!-- /.results-panel -->

                </div><!-- /.results-col -->

            </div><!-- /.layout -->
        </div><!-- /.container -->
    </div><!-- /.main -->

</div><!-- /.retirement-savings -->
