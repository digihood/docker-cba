<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function retirement_savings_get_default_config() {
    $default_inputs = array(
        'current_age' => array(
            'key'     => 'current_age',
            'type'    => 'slider_number',
            'label'   => 'Současný věk',
            'default' => 45,
            'min'     => 18,
            'max'     => 80,
            'step'    => 1,
            'unit'    => 'let',
            'tooltip' => 'Váš aktuální věk v celých letech. Ovlivňuje, kolik let zbývá do důchodu.',
        ),
        'retirement_age' => array(
            'key'     => 'retirement_age',
            'type'    => 'slider_number',
            'label'   => 'Věk odchodu do důchodu',
            'default' => 65,
            'min'     => 40,
            'max'     => 90,
            'step'    => 1,
            'unit'    => 'let',
            'tooltip' => 'Plánovaný věk, ve kterém chcete odejít do důchodu.',
        ),
        'payout_years' => array(
            'key'     => 'payout_years',
            'type'    => 'number',
            'label'   => 'Délka čerpání renty',
            'default' => 20,
            'min'     => 1,
            'max'     => 40,
            'step'    => 1,
            'unit'    => 'let',
            'tooltip' => 'Kolik let plánujete v důchodu čerpat naspořené prostředky. Počítejte rezervu.',
        ),
        'target_pension_now' => array(
            'key'     => 'target_pension_now',
            'type'    => 'number',
            'label'   => 'Cílová měsíční renta (dnešní Kč)',
            'default' => 10000,
            'min'     => 0,
            'max'     => 500000,
            'step'    => 500,
            'unit'    => 'Kč',
            'tooltip' => 'Kolik peněz měsíčně chcete mít v důchodu vyjádřeno v dnešních cenách.',
        ),
        'current_savings' => array(
            'key'     => 'current_savings',
            'type'    => 'number',
            'label'   => 'Aktuálně naspořeno',
            'default' => 0,
            'min'     => 0,
            'max'     => 100000000,
            'step'    => 1000,
            'unit'    => 'Kč',
            'tooltip' => 'Celková výše vašich aktuálních úspor určených na důchod (penzijní spoření, investice, spořicí účty).',
        ),
        'monthly_contribution' => array(
            'key'     => 'monthly_contribution',
            'type'    => 'number',
            'label'   => 'Měsíční příspěvek',
            'default' => 1000,
            'min'     => 0,
            'max'     => 1000000,
            'step'    => 100,
            'unit'    => 'Kč',
            'tooltip' => 'Částka, kterou každý měsíc odkládáte na spoření na důchod.',
        ),
        'annual_return' => array(
            'key'     => 'annual_return',
            'type'    => 'number',
            'label'   => 'Roční výnos investic',
            'default' => 5,
            'min'     => 0,
            'max'     => 30,
            'step'    => 0.1,
            'unit'    => '%',
            'tooltip' => 'Očekávaný průměrný roční výnos vašich investic (nominální, před inflací). Historický průměr diverzifikovaného portfolia bývá 5–8 %.',
        ),
        'inflation_rate' => array(
            'key'     => 'inflation_rate',
            'type'    => 'number',
            'label'   => 'Roční inflace',
            'default' => 3,
            'min'     => 0,
            'max'     => 20,
            'step'    => 0.1,
            'unit'    => '%',
            'tooltip' => 'Předpokládaná průměrná roční inflace po dobu spoření. Ovlivňuje reálnou kupní sílu budoucích úspor.',
        ),
    );

    $result_messages = array(
        'critical_gap' => array(
            'key'    => 'critical_gap',
            'status' => 'red',
            'title'  => 'Důchodová mezera je kritická',
            'text'   => 'Při současném tempu spoření vám do cílové renty výrazně chybí. Zvažte výrazné navýšení měsíčního příspěvku nebo úpravu cílů.',
        ),
        'moderate_gap' => array(
            'key'    => 'moderate_gap',
            'status' => 'orange',
            'title'  => 'Malá důchodová mezera',
            'text'   => 'Jste blízko cíli, ale ještě tam nejste. Mírné navýšení příspěvku nebo delší spoření vám pomůže mezeru uzavřít.',
        ),
        'goal_reached' => array(
            'key'    => 'goal_reached',
            'status' => 'green',
            'title'  => 'Cíl dosažitelný',
            'text'   => 'Skvělá práce! Při současném tempu spoření byste měli na svoji cílovou rentu dosáhnout nebo ji překročit.',
        ),
    );

    return array(
        'default_inputs'  => $default_inputs,
        'result_messages' => $result_messages,
    );
}

function retirement_savings_get_config( $post_id ) {
    $default = retirement_savings_get_default_config();

    $inputs_raw   = get_post_meta( $post_id, '_retirement_savings_default_inputs', true );
    $messages_raw = get_post_meta( $post_id, '_retirement_savings_result_messages', true );

    $inputs   = ! empty( $inputs_raw )   ? json_decode( $inputs_raw, true )   : null;
    $messages = ! empty( $messages_raw ) ? json_decode( $messages_raw, true ) : null;

    return array(
        'default_inputs'  => ( is_array( $inputs ) && ! empty( $inputs ) )   ? $inputs   : $default['default_inputs'],
        'result_messages' => ( is_array( $messages ) && ! empty( $messages ) ) ? $messages : $default['result_messages'],
    );
}

function retirement_savings_get_default_inputs( $post_id ) {
    $config = retirement_savings_get_config( $post_id );
    return $config['default_inputs'];
}

function retirement_savings_get_result_messages( $post_id ) {
    $config = retirement_savings_get_config( $post_id );
    return $config['result_messages'];
}

function retirement_savings_get_saved_user_data( $post_id, $user_id ) {
    if ( ! $user_id ) {
        return null;
    }
    $data = get_user_meta( $user_id, '_retirement_savings_data', true );
    if ( empty( $data ) ) {
        return null;
    }
    $decoded = json_decode( $data, true );
    if ( ! is_array( $decoded ) ) {
        return null;
    }
    if ( isset( $decoded['calculator_id'] ) && (int) $decoded['calculator_id'] !== (int) $post_id ) {
        return null;
    }
    return isset( $decoded['values'] ) ? $decoded['values'] : null;
}

function retirement_savings_sanitize_input_value( $key, $value ) {
    $int_keys = array( 'current_age', 'retirement_age', 'payout_years' );
    if ( in_array( $key, $int_keys, true ) ) {
        return absint( $value );
    }
    return floatval( $value );
}

function retirement_savings_sanitize_values( $values ) {
    $keys = array(
        'current_age',
        'retirement_age',
        'payout_years',
        'target_pension_now',
        'current_savings',
        'monthly_contribution',
        'annual_return',
        'inflation_rate',
    );

    $clean = array();
    foreach ( $keys as $key ) {
        $raw         = isset( $values[ $key ] ) ? $values[ $key ] : 0;
        $clean[ $key ] = retirement_savings_sanitize_input_value( $key, $raw );
    }
    return $clean;
}

function retirement_savings_validate_values( $values ) {
    $errors = array();

    $current_age    = isset( $values['current_age'] )    ? (int) $values['current_age']    : 0;
    $retirement_age = isset( $values['retirement_age'] ) ? (int) $values['retirement_age'] : 0;
    $payout_years   = isset( $values['payout_years'] )   ? (int) $values['payout_years']   : 0;

    if ( $current_age < 18 || $current_age > 80 ) {
        $errors[] = 'Současný věk musí být mezi 18 a 80 lety.';
    }
    if ( $retirement_age < 40 || $retirement_age > 90 ) {
        $errors[] = 'Věk odchodu do důchodu musí být mezi 40 a 90 lety.';
    }
    if ( $retirement_age <= $current_age ) {
        $errors[] = 'Věk odchodu do důchodu musí být vyšší než současný věk.';
    }
    if ( $payout_years < 1 || $payout_years > 40 ) {
        $errors[] = 'Délka čerpání renty musí být mezi 1 a 40 lety.';
    }

    $target_pension_now    = isset( $values['target_pension_now'] )    ? floatval( $values['target_pension_now'] )    : 0;
    $current_savings       = isset( $values['current_savings'] )       ? floatval( $values['current_savings'] )       : 0;
    $monthly_contribution  = isset( $values['monthly_contribution'] )  ? floatval( $values['monthly_contribution'] )  : 0;
    $annual_return         = isset( $values['annual_return'] )         ? floatval( $values['annual_return'] )         : 0;
    $inflation_rate        = isset( $values['inflation_rate'] )        ? floatval( $values['inflation_rate'] )        : 0;

    if ( $target_pension_now < 0 || $target_pension_now > 500000 ) {
        $errors[] = 'Cílová renta musí být mezi 0 a 500 000 Kč.';
    }
    if ( $current_savings < 0 ) {
        $errors[] = 'Aktuálně naspořená částka nemůže být záporná.';
    }
    if ( $monthly_contribution < 0 ) {
        $errors[] = 'Měsíční příspěvek nemůže být záporný.';
    }
    if ( $annual_return < 0 || $annual_return > 30 ) {
        $errors[] = 'Roční výnos musí být mezi 0 a 30 %.';
    }
    if ( $inflation_rate < 0 || $inflation_rate > 20 ) {
        $errors[] = 'Inflace musí být mezi 0 a 20 %.';
    }

    return $errors;
}

function retirement_savings_calculate_results( $values ) {
    $current_age           = (int) $values['current_age'];
    $retirement_age        = (int) $values['retirement_age'];
    $payout_years          = (int) $values['payout_years'];
    $target_pension_now    = (float) $values['target_pension_now'];
    $current_savings       = (float) $values['current_savings'];
    $monthly_contribution  = (float) $values['monthly_contribution'];
    $annual_return         = (float) $values['annual_return'];
    $inflation_rate        = (float) $values['inflation_rate'];

    $n = $retirement_age - $current_age;
    if ( $n <= 0 ) {
        return array( 'error' => 'Věk odchodu do důchodu musí být vyšší než současný věk.' );
    }

    $EPS = 0.000001;

    // Real interest rate (Fisher equation)
    $r_real = ( ( 1 + $annual_return / 100 ) / ( 1 + $inflation_rate / 100 ) ) - 1;

    // Target capital needed at retirement (in today's Kč, real terms)
    if ( abs( $r_real ) < $EPS ) {
        $s_target = $target_pension_now * 12 * $payout_years;
    } else {
        $s_target = $target_pension_now * 12 * ( ( 1 - pow( 1 + $r_real, -$payout_years ) ) / $r_real );
    }

    // Projected capital at retirement (in today's Kč, real terms)
    if ( abs( $r_real ) < $EPS ) {
        $v1    = $current_savings;
        $v2    = $monthly_contribution * 12 * $n;
    } else {
        $v1 = $current_savings * pow( 1 + $r_real, $n );
        $v2 = $monthly_contribution * 12 * ( ( pow( 1 + $r_real, $n ) - 1 ) / $r_real );
    }
    $s_real = $v1 + $v2;

    $gap = $s_target - $s_real;

    // Future nominal value of monthly pension (for display)
    $future_monthly_pension_nominal = $target_pension_now * pow( 1 + $inflation_rate / 100, $n );

    // Additional monthly contribution needed to close the gap
    $additional_monthly_contribution = 0;
    if ( $gap > 0 ) {
        if ( abs( $r_real ) < $EPS ) {
            $additional_monthly_contribution = $gap / ( 12 * $n );
        } else {
            $additional_monthly_contribution = ( $gap / ( ( pow( 1 + $r_real, $n ) - 1 ) / $r_real ) ) / 12;
        }
    }

    // Status
    if ( $gap <= 0 ) {
        $status = 'goal_reached';
    } elseif ( $s_target > 0 && ( $gap / $s_target ) > 0.25 ) {
        $status = 'critical_gap';
    } else {
        $status = 'moderate_gap';
    }

    return array(
        'target_amount'                  => round( $s_target ),
        'projected_amount'               => round( $s_real ),
        'retirement_gap'                 => round( $gap ),
        'years_to_retirement'            => $n,
        'future_monthly_pension_nominal' => round( $future_monthly_pension_nominal ),
        'additional_monthly_contribution'=> round( $additional_monthly_contribution ),
        'status'                         => $status,
        'r_real'                         => $r_real,
    );
}
