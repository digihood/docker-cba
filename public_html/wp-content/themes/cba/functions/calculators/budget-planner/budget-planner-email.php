<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function budget_planner_send_report_email( $email, $payload ) {
    if ( ! is_email( $email ) ) {
        return false;
    }

    $site_name     = get_bloginfo( 'name' );
    $subject       = sprintf( __( 'Váš Plánovač rozpočtu – výsledky z %s', 'cba' ), $site_name );
    $from_email    = get_option( 'admin_email' );
    $from_name     = $site_name;

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        sprintf( 'From: %s <%s>', $from_name, $from_email ),
    );

    $html = budget_planner_build_email_html( $payload );

    return wp_mail( $email, $subject, $html, $headers );
}

function budget_planner_build_email_html( $payload ) {
    $total_income   = number_format( (float) $payload['total_income'],   0, ',', ' ' );
    $total_expenses = number_format( (float) $payload['total_expenses'], 0, ',', ' ' );
    $monthly_saving = (float) $payload['monthly_saving'];
    $savings_5y     = number_format( (float) $payload['savings_5y'],     0, ',', ' ' );
    $health_title   = esc_html( $payload['health_title'] ?? '' );
    $health_status  = $payload['health_status'] ?? 'orange';

    $saving_label   = $monthly_saving >= 0
        ? 'Měsíční úspora'
        : 'Měsíční schodek';
    $saving_display = number_format( abs( $monthly_saving ), 0, ',', ' ' );

    $status_colors = array(
        'red'    => '#e53e3e',
        'orange' => '#dd6b20',
        'green'  => '#38a169',
    );
    $status_color = $status_colors[ $health_status ] ?? '#dd6b20';

    $items_by_category = array();
    $categories_map    = array();

    if ( ! empty( $payload['categories'] ) ) {
        foreach ( $payload['categories'] as $cat ) {
            $categories_map[ $cat['id'] ?? $cat['slug'] ?? '' ] = esc_html( $cat['name'] ?? '' );
        }
    }

    if ( ! empty( $payload['items'] ) ) {
        foreach ( $payload['items'] as $item ) {
            $value = (float) ( $item['value'] ?? 0 );
            if ( $value <= 0 ) {
                continue;
            }
            $cat_id   = $item['category'] ?? 'other';
            $cat_name = $categories_map[ $cat_id ] ?? esc_html( ucfirst( $cat_id ) );
            if ( ! isset( $items_by_category[ $cat_id ] ) ) {
                $items_by_category[ $cat_id ] = array( 'name' => $cat_name, 'rows' => array() );
            }
            $items_by_category[ $cat_id ]['rows'][] = array(
                'name'  => esc_html( $item['name'] ?? '' ),
                'value' => number_format( $value, 0, ',', ' ' ),
                'type'  => $item['type'] ?? '',
            );
        }
    }

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="cs">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Plánovač rozpočtu – výsledky</title>
    </head>
    <body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#333;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f6f8;padding:24px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:8px;overflow:hidden;max-width:600px;">

                    <!-- Header -->
                    <tr>
                        <td style="background:#1a365d;padding:28px 32px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;">Plánovač rozpočtu</h1>
                            <p style="margin:6px 0 0;color:#90cdf4;font-size:13px;">Váš osobní finanční přehled</p>
                        </td>
                    </tr>

                    <!-- Summary -->
                    <tr>
                        <td style="padding:28px 32px 0;">
                            <h2 style="margin:0 0 16px;font-size:16px;color:#1a365d;border-bottom:2px solid #e2e8f0;padding-bottom:8px;">Souhrn výsledků</h2>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding:8px 0;font-size:14px;color:#555;">Celkové příjmy</td>
                                    <td style="padding:8px 0;font-size:14px;font-weight:700;text-align:right;color:#38a169;"><?php echo $total_income; ?> Kč</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;font-size:14px;color:#555;">Celkové výdaje</td>
                                    <td style="padding:8px 0;font-size:14px;font-weight:700;text-align:right;color:#e53e3e;"><?php echo $total_expenses; ?> Kč</td>
                                </tr>
                                <tr style="background:#f7fafc;">
                                    <td style="padding:12px 8px;font-size:15px;font-weight:700;color:#1a365d;"><?php echo esc_html( $saving_label ); ?></td>
                                    <td style="padding:12px 8px;font-size:15px;font-weight:700;text-align:right;color:<?php echo esc_attr( $status_color ); ?>;"><?php echo $saving_display; ?> Kč</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Health indicator -->
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <div style="background:<?php echo esc_attr( $status_color ); ?>;color:#fff;padding:14px 18px;border-radius:6px;font-size:14px;font-weight:600;">
                                <?php echo $health_title; ?>
                            </div>
                        </td>
                    </tr>

                    <!-- Simulator -->
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <div style="background:#ebf8ff;border:1px solid #bee3f8;border-radius:6px;padding:14px 18px;">
                                <p style="margin:0;font-size:13px;color:#2b6cb0;">
                                    <strong>Simulátor úspor:</strong> Pokud byste snížili variabilní výdaje o 10&nbsp;%, mohli byste za 5 let ušetřit přibližně <strong><?php echo $savings_5y; ?> Kč</strong>.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Item breakdown -->
                    <?php if ( ! empty( $items_by_category ) ) : ?>
                    <tr>
                        <td style="padding:28px 32px 0;">
                            <h2 style="margin:0 0 16px;font-size:16px;color:#1a365d;border-bottom:2px solid #e2e8f0;padding-bottom:8px;">Vyplněné položky</h2>
                        </td>
                    </tr>
                    <?php foreach ( $items_by_category as $cat_data ) : ?>
                    <tr>
                        <td style="padding:0 32px 16px;">
                            <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#4a5568;text-transform:uppercase;letter-spacing:0.5px;"><?php echo $cat_data['name']; ?></p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                                <?php foreach ( $cat_data['rows'] as $row ) : ?>
                                <tr>
                                    <td style="padding:5px 0;font-size:13px;color:#555;border-bottom:1px solid #f0f0f0;"><?php echo $row['name']; ?></td>
                                    <td style="padding:5px 0;font-size:13px;text-align:right;color:#333;border-bottom:1px solid #f0f0f0;font-weight:600;"><?php echo $row['value']; ?> Kč</td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:28px 32px;background:#f7fafc;border-top:1px solid #e2e8f0;text-align:center;">
                            <p style="margin:0;font-size:12px;color:#a0aec0;">
                                Tento e-mail byl vygenerován kalkulačkou Plánovač rozpočtu na webu <strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong>.<br>
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
