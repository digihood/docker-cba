<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id    = get_the_ID();
$config     = budget_planner_get_config( $post_id );
$categories = array_values( array_filter( $config['categories'], function( $c ) { return ! empty( $c['active'] ); } ) );
$items      = array_values( array_filter( $config['items'], function( $i ) { return ! empty( $i['active'] ); } ) );

usort( $categories, function( $a, $b ) { return ( $a['order'] ?? 0 ) - ( $b['order'] ?? 0 ); } );
usort( $items, function( $a, $b ) { return ( $a['order'] ?? 0 ) - ( $b['order'] ?? 0 ); } );

// Group items by category
$items_by_cat = array();
foreach ( $categories as $cat ) {
    $items_by_cat[ $cat['id'] ] = array();
}
foreach ( $items as $item ) {
    $cat_id = $item['category'] ?? '';
    if ( isset( $items_by_cat[ $cat_id ] ) ) {
        $items_by_cat[ $cat_id ][] = $item;
    }
}

$is_logged_in = is_user_logged_in();
?>

<div class="budget-planner">

    <!-- Hero section -->
    <div class="budget-planner__hero">
        <div class="budget-planner__hero-inner">
            <h1 class="budget-planner__title"><?php the_title(); ?></h1>
            <p class="budget-planner__intro">
                Projděte seznam svých příjmů a výdajů. U každé položky, která se vás týká, vyplňte průměrnou měsíční částku v Kč. Ostatní nechte prázdné. Výsledky se počítají okamžitě.
            </p>
        </div>
    </div>

    <div class="budget-planner__layout">

        <!-- Form column -->
        <div class="budget-planner__form-col">
            <form id="budget-planner-form" class="budget-planner__form" novalidate>

                <?php foreach ( $categories as $cat ) : ?>
                    <?php
                    $cat_id    = $cat['id'];
                    $cat_items = $items_by_cat[ $cat_id ] ?? array();
                    if ( empty( $cat_items ) ) continue;
                    ?>
                    <section class="budget-planner__section" data-category="<?php echo esc_attr( $cat_id ); ?>">
                        <h2 class="budget-planner__section-title"><?php echo esc_html( $cat['name'] ); ?></h2>
                        <div class="budget-planner__items">
                            <?php foreach ( $cat_items as $item ) : ?>
                                <div class="budget-planner__item" data-item-id="<?php echo esc_attr( $item['slug'] ); ?>" data-type="<?php echo esc_attr( $item['type'] ); ?>" data-category="<?php echo esc_attr( $cat_id ); ?>">
                                    <label class="budget-planner__item-label" for="bp-<?php echo esc_attr( $item['slug'] ); ?>">
                                        <?php echo esc_html( $item['name'] ); ?>
                                        <?php if ( ! empty( $item['tooltip'] ) ) : ?>
                                            <span class="budget-planner__tooltip" aria-label="<?php echo esc_attr( $item['tooltip'] ); ?>">
                                                <span class="budget-planner__tooltip-icon">?</span>
                                                <span class="budget-planner__tooltip-text"><?php echo esc_html( $item['tooltip'] ); ?></span>
                                            </span>
                                        <?php endif; ?>
                                    </label>
                                    <div class="budget-planner__input-wrap">
                                        <input
                                            type="number"
                                            id="bp-<?php echo esc_attr( $item['slug'] ); ?>"
                                            name="<?php echo esc_attr( $item['slug'] ); ?>"
                                            class="budget-planner__input"
                                            min="0"
                                            step="1"
                                            placeholder="0"
                                            value=""
                                            data-item-slug="<?php echo esc_attr( $item['slug'] ); ?>"
                                            data-item-type="<?php echo esc_attr( $item['type'] ); ?>"
                                            data-item-category="<?php echo esc_attr( $cat_id ); ?>"
                                        >
                                        <span class="budget-planner__input-suffix">Kč</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>

            </form>
        </div>

        <!-- Summary column (sticky on desktop) -->
        <aside class="budget-planner__sidebar" id="bp-sidebar">

            <!-- Summary panel -->
            <div class="budget-planner__summary" id="bp-summary">
                <h3 class="budget-planner__summary-title">Váš přehled</h3>

                <div class="budget-planner__summary-row">
                    <span class="budget-planner__summary-label">Celkové příjmy</span>
                    <span class="budget-planner__summary-value budget-planner__summary-value--income" id="bp-total-income">0 Kč</span>
                </div>
                <div class="budget-planner__summary-row">
                    <span class="budget-planner__summary-label">Celkové výdaje</span>
                    <span class="budget-planner__summary-value budget-planner__summary-value--expense" id="bp-total-expenses">0 Kč</span>
                </div>

                <div class="budget-planner__monthly-saving" id="bp-monthly-saving-wrap">
                    <span class="budget-planner__monthly-saving-label" id="bp-saving-label">Vaše měsíční úspora:</span>
                    <strong class="budget-planner__monthly-saving-value" id="bp-monthly-saving">0 Kč</strong>
                </div>

                <!-- Health indicator -->
                <div class="budget-planner__health" id="bp-health">
                    <div class="budget-planner__health-indicator" id="bp-health-indicator">
                        <span class="budget-planner__health-dot" id="bp-health-dot"></span>
                        <div>
                            <strong class="budget-planner__health-title" id="bp-health-title">Vyplňte formulář</strong>
                            <p class="budget-planner__health-text" id="bp-health-text">Výsledky se zobrazí po zadání hodnot.</p>
                        </div>
                    </div>
                </div>

                <!-- Savings simulator -->
                <div class="budget-planner__simulator" id="bp-simulator">
                    <h4 class="budget-planner__simulator-title">Simulátor úspor</h4>
                    <p class="budget-planner__simulator-text" id="bp-simulator-text">
                        Zadejte variabilní výdaje a zobrazte potenciál úspor.
                    </p>
                </div>

                <!-- Chart -->
                <div class="budget-planner__chart-wrap" id="bp-chart-wrap">
                    <h4 class="budget-planner__chart-title">Rozložení výdajů</h4>
                    <div class="budget-planner__chart-container">
                        <canvas id="bp-expenses-chart" width="300" height="300"></canvas>
                    </div>
                </div>

                <!-- Email report button -->
                <div class="budget-planner__email-section">
                    <button type="button" class="budget-planner__btn budget-planner__btn--primary" id="bp-send-email-btn">
                        Poslat výsledky na e-mail
                    </button>
                </div>

                <!-- Registration CTA for non-logged users -->
                <?php if ( ! $is_logged_in ) : ?>
                    <div class="budget-planner__register-cta" id="bp-register-cta">
                        <p>
                            <strong>Chcete si výsledky uložit i pro příště?</strong><br>
                            <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="budget-planner__link">Vytvořte si účet</a> nebo se <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="budget-planner__link">přihlaste</a>.
                        </p>
                    </div>
                <?php endif; ?>
            </div>

        </aside>

    </div><!-- /.budget-planner__layout -->

    <!-- Sticky bottom bar (mobile) -->
    <div class="budget-planner__sticky-bar" id="bp-sticky-bar">
        <div class="budget-planner__sticky-bar-inner">
            <div class="budget-planner__sticky-saving">
                <span id="bp-sticky-label">Měsíční úspora:</span>
                <strong id="bp-sticky-value">0 Kč</strong>
            </div>
            <div class="budget-planner__sticky-health" id="bp-sticky-dot"></div>
        </div>
    </div>

</div><!-- /.budget-planner -->

<!-- Email modal -->
<div class="budget-planner__modal-overlay" id="bp-modal-overlay" aria-hidden="true">
    <div class="budget-planner__modal" role="dialog" aria-modal="true" aria-labelledby="bp-modal-title">
        <button class="budget-planner__modal-close" id="bp-modal-close" aria-label="Zavřít">&times;</button>
        <h3 class="budget-planner__modal-title" id="bp-modal-title">Odeslat výsledky na e-mail</h3>
        <p class="budget-planner__modal-desc">Zadejte vaši e-mailovou adresu a my vám pošleme přehledný souhrn vašich financí.</p>
        <div class="budget-planner__modal-form">
            <label for="bp-email-input" class="budget-planner__modal-label">E-mailová adresa</label>
            <input type="email" id="bp-email-input" class="budget-planner__modal-input" placeholder="vas@email.cz" autocomplete="email">
            <p class="budget-planner__modal-error" id="bp-email-error"></p>
        </div>
        <div class="budget-planner__modal-actions">
            <button type="button" class="budget-planner__btn budget-planner__btn--primary" id="bp-modal-send-btn">Odeslat report</button>
            <button type="button" class="budget-planner__btn budget-planner__btn--secondary" id="bp-modal-cancel-btn">Zrušit</button>
        </div>
        <div class="budget-planner__modal-feedback" id="bp-modal-feedback"></div>
    </div>
</div>
