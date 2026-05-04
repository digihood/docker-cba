<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function net_worth_send_report_email( $email, $payload ) {
    if ( ! is_email( $email ) ) {
        return false;
    }

    $site_name  = get_bloginfo( 'name' );
    $subject    = sprintf( __( 'Vaše čisté jmění – přehled z %s', 'cba' ), $site_name );
    $from_email = get_option( 'admin_email' );
    $from_name  = $site_name;

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        sprintf( 'From: %s <%s>', $from_name, $from_email ),
    );

    $html = net_worth_build_email_html( $payload );

    return wp_mail( $email, $subject, $html, $headers );
}

function net_worth_build_email_html( $payload ) {
    $results             = $payload['results']             ?? array();
    $items               = $payload['items']               ?? array();
    $categories          = $payload['categories']          ?? array();
    $benchmarks          = $payload['benchmarks']          ?? array();
    $recommended_content = $payload['recommended_content'] ?? array();
    $values              = $payload['values']              ?? array();

    $total_assets      = isset( $results['total_assets'] )      ? (float) $results['total_assets']      : 0.0;
    $total_liabilities = isset( $results['total_liabilities'] ) ? (float) $results['total_liabilities'] : 0.0;
    $net_worth         = isset( $results['net_worth'] )         ? (float) $results['net_worth']         : 0.0;
    $equity_ratio      = isset( $results['equity_ratio'] )      ? (float) $results['equity_ratio']      : 0.0;
    $debt_ratio        = isset( $results['debt_to_asset_ratio'] ) ? (float) $results['debt_to_asset_ratio'] : 0.0;
    $liquidity_index   = isset( $results['liquidity_index'] )   ? (float) $results['liquidity_index']   : 0.0;
    $resilience_months = isset( $results['crisis_resilience_months'] ) ? $results['crisis_resilience_months'] : null;
    $divers_warning    = ! empty( $results['diversification_warning'] );
    $largest_cat       = $results['largest_asset_category'] ?? '';

    $fmt = function( $num ) {
        return number_format( (float) $num, 0, ',', ' ' );
    };

    // Determine net worth color
    $nw_color = $net_worth >= 0 ? '#38a169' : '#e53e3e';

    // Filled items grouped by category
    $cat_map = array();
    foreach ( $categories as $cat ) {
        $cat_slug = $cat['slug'] ?? $cat['id'] ?? '';
        $cat_map[ $cat_slug ] = esc_html( $cat['name'] ?? '' );
    }

    $items_by_category = array();
    foreach ( $items as $item ) {
        if ( ! ( $item['active'] ?? true ) ) {
            continue;
        }
        $slug  = $item['slug'] ?? $item['id'] ?? '';
        $value = isset( $values[ $slug ] ) ? floatval( $values[ $slug ] ) : 0.0;
        if ( $value <= 0 ) {
            continue;
        }
        $cat_slug = $item['category'] ?? 'other';
        $cat_name = $cat_map[ $cat_slug ] ?? esc_html( ucfirst( $cat_slug ) );
        if ( ! isset( $items_by_category[ $cat_slug ] ) ) {
            $items_by_category[ $cat_slug ] = array( 'name' => $cat_name, 'rows' => array() );
        }
        $items_by_category[ $cat_slug ]['rows'][] = array(
            'name'  => esc_html( $item['name'] ?? '' ),
            'value' => $fmt( $value ),
            'type'  => $item['type'] ?? '',
        );
    }

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="cs">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Čisté jmění – přehled</title>
    </head>
    <body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#333;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f6f8;padding:24px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:8px;overflow:hidden;max-width:600px;">

                    <!-- Header -->
                    <tr>
                        <td style="background:#1e3a5f;padding:28px 32px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;">Čisté jmění</h1>
                            <p style="margin:6px 0 0;color:#90cdf4;font-size:13px;">Váš osobní finanční přehled</p>
                        </td>
                    </tr>

                    <!-- Net worth highlight -->
                    <tr>
                        <td style="padding:24px 32px 0;text-align:center;">
                            <p style="margin:0 0 4px;font-size:13px;color:#666;">Čisté jmění</p>
                            <p style="margin:0;font-size:32px;font-weight:700;color:<?php echo esc_attr( $nw_color ); ?>;"><?php echo $fmt( $net_worth ); ?> Kč</p>
                        </td>
                    </tr>

                    <!-- Summary table -->
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <h2 style="margin:0 0 16px;font-size:16px;color:#1e3a5f;border-bottom:2px solid #e2e8f0;padding-bottom:8px;">Souhrn výsledků</h2>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding:8px 0;font-size:14px;color:#555;">Celková aktiva</td>
                                    <td style="padding:8px 0;font-size:14px;font-weight:700;text-align:right;color:#3498db;"><?php echo $fmt( $total_assets ); ?> Kč</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;font-size:14px;color:#555;">Celkové závazky</td>
                                    <td style="padding:8px 0;font-size:14px;font-weight:700;text-align:right;color:#e74c3c;"><?php echo $fmt( $total_liabilities ); ?> Kč</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;font-size:14px;color:#555;">Poměr vlastního majetku</td>
                                    <td style="padding:8px 0;font-size:14px;font-weight:700;text-align:right;color:#333;"><?php echo number_format( $equity_ratio, 1, ',', ' ' ); ?> %</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;font-size:14px;color:#555;">Poměr dluhu k majetku</td>
                                    <td style="padding:8px 0;font-size:14px;font-weight:700;text-align:right;color:#333;"><?php echo number_format( $debt_ratio, 1, ',', ' ' ); ?> %</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;font-size:14px;color:#555;">Index likvidity</td>
                                    <td style="padding:8px 0;font-size:14px;font-weight:700;text-align:right;color:#333;"><?php echo number_format( $liquidity_index, 1, ',', ' ' ); ?> %</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;font-size:14px;color:#555;">Krizová odolnost</td>
                                    <td style="padding:8px 0;font-size:14px;font-weight:700;text-align:right;color:#333;">
                                        <?php echo $resilience_months !== null ? number_format( (float) $resilience_months, 1, ',', ' ' ) . ' měs.' : '—'; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <?php if ( $divers_warning ) : ?>
                    <!-- Diversification warning -->
                    <tr>
                        <td style="padding:16px 32px 0;">
                            <div style="background:#fffbeb;border-left:4px solid #f39c12;padding:12px 16px;border-radius:4px;">
                                <p style="margin:0;font-size:13px;color:#92400e;">
                                    <strong>Nízká diverzifikace:</strong> Více než 80 % vašich aktiv tvoří jedna kategorie
                                    <?php if ( $largest_cat ) : ?>
                                        (<?php echo esc_html( $largest_cat ); ?>)
                                    <?php endif; ?>.
                                    Zvažte rozložení portfolia do více kategorií.
                                </p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <!-- Filled items breakdown -->
                    <?php if ( ! empty( $items_by_category ) ) : ?>
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <h2 style="margin:0 0 16px;font-size:16px;color:#1e3a5f;border-bottom:2px solid #e2e8f0;padding-bottom:8px;">Vyplněné položky</h2>
                        </td>
                    </tr>
                    <?php foreach ( $items_by_category as $cat_data ) : ?>
                    <tr>
                        <td style="padding:0 32px 16px;">
                            <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#4a5568;text-transform:uppercase;letter-spacing:0.5px;"><?php echo $cat_data['name']; ?></p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                                <?php foreach ( $cat_data['rows'] as $row ) :
                                    $row_color = ( $row['type'] === 'liability' ) ? '#e74c3c' : '#3498db';
                                ?>
                                <tr>
                                    <td style="padding:5px 0;font-size:13px;color:#555;border-bottom:1px solid #f0f0f0;"><?php echo $row['name']; ?></td>
                                    <td style="padding:5px 0;font-size:13px;text-align:right;color:<?php echo esc_attr( $row_color ); ?>;border-bottom:1px solid #f0f0f0;font-weight:600;"><?php echo $row['value']; ?> Kč</td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Recommended content -->
                    <?php if ( ! empty( $recommended_content ) ) : ?>
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <h2 style="margin:0 0 12px;font-size:16px;color:#1e3a5f;border-bottom:2px solid #e2e8f0;padding-bottom:8px;">Doporučené čtení</h2>
                            <?php foreach ( $recommended_content as $rec ) : ?>
                            <p style="margin:0 0 8px;">
                                <a href="<?php echo esc_url( $rec['url'] ?? '#' ); ?>" style="color:#1e3a5f;font-size:13px;"><?php echo esc_html( $rec['title'] ?? '' ); ?></a>
                            </p>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:28px 32px;background:#f7fafc;border-top:1px solid #e2e8f0;text-align:center;margin-top:24px;">
                            <p style="margin:0;font-size:12px;color:#a0aec0;">
                                Tento e-mail byl vygenerován kalkulačkou Čisté jmění na webu <strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong>.<br>
                                Jedná se o informativní přehled, nikoli finanční poradenství.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
    </body>
    </html>
    <?php
    return ob_get_clean();
}
