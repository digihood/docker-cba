<?php
/**
 * Saved calculators section for the "Můj účet" page.
 * Expects $cba_user (CbaUser) to be set in the calling template.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Receives $cba_user via get_template_part() args (WP 5.5+)
$cba_user = $args['cba_user'] ?? null;
if ( ! $cba_user instanceof CbaUser ) return;

$calculators = CbaCalculator::get_all();
?>

<div class="account-card account-calculators">

    <div class="account-card__header">
        <h2 class="account-card__title"><?php esc_html_e( 'Moje kalkulačky', 'cba' ); ?></h2>
    </div>

    <div class="account-card__body">

        <?php if ( empty( $calculators ) ) : ?>
            <p class="account-empty"><?php esc_html_e( 'Zatím nejsou žádné kalkulačky.', 'cba' ); ?></p>
        <?php else : ?>

            <div class="account-calc-list">
                <?php foreach ( $calculators as $calc ) :
                    $saved  = $calc->get_user_data( $cba_user );
                    $has    = ! empty( $saved );
                    $date   = $has && ! empty( $saved['updated_at'] ) ? $saved['updated_at'] : null;
                    $thumb  = $calc->get_thumbnail_url();
                ?>

                <div class="account-calc-item <?php echo $has ? 'account-calc-item--has-data' : ''; ?>">

                    <div class="account-calc-item__icon-wrap">
                        <?php if ( $thumb ) : ?>
                            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $calc->get_title() ); ?>" class="account-calc-item__thumb">
                        <?php else : ?>
                            <span class="account-calc-item__icon"><?php echo $calc->get_icon(); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="account-calc-item__info">
                        <h3 class="account-calc-item__title"><?php echo esc_html( $calc->get_title() ); ?></h3>

                        <?php if ( $has ) : ?>
                            <?php if ( $date ) : ?>
                                <p class="account-calc-item__date">
                                    <?php printf(
                                        esc_html__( 'Naposledy uloženo: %s', 'cba' ),
                                        esc_html( date_i18n( get_option( 'date_format' ) . ' H:i', strtotime( $date ) ) )
                                    ); ?>
                                </p>
                            <?php endif; ?>

                            <?php
                            // Budget planner: show brief financial summary
                            if ( $calc->get_slug() === 'planovac-rozpoctu' && ! empty( $saved['values'] ) ) :
                                $config  = $calc->get_config();
                                $totals  = function_exists( 'budget_planner_calculate_totals' )
                                    ? budget_planner_calculate_totals( $saved['values'], $config['items'] ?? [] )
                                    : null;
                            ?>
                            <?php if ( $totals ) : ?>
                            <div class="account-calc-item__summary">
                                <span class="account-calc-item__summary-item account-calc-item__summary-item--income">
                                    <?php esc_html_e( 'Příjmy:', 'cba' ); ?>
                                    <strong><?php echo number_format( $totals['total_income'], 0, ',', ' ' ); ?> Kč</strong>
                                </span>
                                <span class="account-calc-item__summary-item account-calc-item__summary-item--expense">
                                    <?php esc_html_e( 'Výdaje:', 'cba' ); ?>
                                    <strong><?php echo number_format( $totals['total_expenses'], 0, ',', ' ' ); ?> Kč</strong>
                                </span>
                                <span class="account-calc-item__summary-item <?php echo $totals['monthly_saving'] >= 0 ? 'account-calc-item__summary-item--positive' : 'account-calc-item__summary-item--negative'; ?>">
                                    <?php esc_html_e( 'Bilance:', 'cba' ); ?>
                                    <strong><?php echo number_format( $totals['monthly_saving'], 0, ',', ' ' ); ?> Kč</strong>
                                </span>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>

                        <?php else : ?>
                            <p class="account-calc-item__no-data">
                                <?php esc_html_e( 'Zatím žádná uložená data.', 'cba' ); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="account-calc-item__actions">
                        <a href="<?php echo esc_url( $calc->get_permalink() ); ?>" class="account-btn account-btn--outline">
                            <?php esc_html_e( $has ? 'Otevřít' : 'Spustit', 'cba' ); ?>
                        </a>
                    </div>

                </div>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </div>

</div>
