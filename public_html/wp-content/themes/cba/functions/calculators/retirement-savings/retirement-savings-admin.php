<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function retirement_savings_add_metaboxes() {
    add_meta_box(
        'retirement_savings_config',
        __( 'Konfigurace – Spoření na důchod', 'cba' ),
        'retirement_savings_render_admin_metabox',
        'calculator',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'retirement_savings_add_metaboxes' );

function retirement_savings_metabox_is_applicable( $post_id ) {
    $slug  = get_post_field( 'post_name', $post_id );
    $title = get_the_title( $post_id );

    if ( $slug === 'sporeni-na-duchod' ) {
        return true;
    }
    if ( empty( $slug ) && strpos( $title, 'Spoření na důchod' ) !== false ) {
        return true;
    }
    return false;
}

function retirement_savings_render_admin_metabox( $post ) {
    if ( ! retirement_savings_metabox_is_applicable( $post->ID ) ) {
        echo '<p>' . esc_html__( 'Tato konfigurace je dostupná pouze pro příspěvek se slugem "sporeni-na-duchod".', 'cba' ) . '</p>';
        return;
    }

    wp_nonce_field( 'retirement_savings_metabox_action', 'retirement_savings_metabox_nonce' );

    $default        = retirement_savings_get_default_config();
    $inputs_raw     = get_post_meta( $post->ID, '_retirement_savings_default_inputs', true );
    $messages_raw   = get_post_meta( $post->ID, '_retirement_savings_result_messages', true );

    $inputs_decoded   = ! empty( $inputs_raw )   ? json_decode( $inputs_raw, true )   : null;
    $messages_decoded = ! empty( $messages_raw ) ? json_decode( $messages_raw, true ) : null;

    $inputs_val   = json_encode(
        ( is_array( $inputs_decoded ) && ! empty( $inputs_decoded ) ) ? $inputs_decoded : $default['default_inputs'],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );
    $messages_val = json_encode(
        ( is_array( $messages_decoded ) && ! empty( $messages_decoded ) ) ? $messages_decoded : $default['result_messages'],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );
    ?>
    <style>
        .rs-admin-section { margin-bottom: 24px; }
        .rs-admin-section h4 { margin: 0 0 8px; font-size: 14px; font-weight: 600; }
        .rs-admin-textarea { width: 100%; font-family: monospace; font-size: 12px; border: 1px solid #ccc; padding: 8px; border-radius: 4px; }
        .rs-admin-notice { background: #fff8e1; border-left: 4px solid #ffc107; padding: 10px 14px; margin-bottom: 16px; font-size: 13px; }
        .rs-admin-schema { background: #f0f4f8; border: 1px solid #d0d9e2; border-radius: 4px; padding: 10px 14px; font-size: 12px; font-family: monospace; white-space: pre-wrap; color: #333; }
    </style>

    <div class="rs-admin-notice">
        <strong>JSON editory</strong> – upravte data přímo v JSON formátu. Při nesprávném formátu nebudou data uložena.
    </div>

    <div class="rs-admin-section">
        <h4>Vstupní pole (<code>_retirement_savings_default_inputs</code>)</h4>
        <p style="font-size:12px;color:#666;margin:0 0 6px;">Schéma: <code>key, type, label, default, min, max, step, unit, tooltip</code></p>
        <textarea name="retirement_savings_default_inputs" class="rs-admin-textarea" rows="30"><?php echo esc_textarea( $inputs_val ); ?></textarea>
    </div>

    <div class="rs-admin-section">
        <h4>Textace výsledků (<code>_retirement_savings_result_messages</code>)</h4>
        <p style="font-size:12px;color:#666;margin:0 0 6px;">Schéma: <code>key, status (red|orange|green), title, text</code></p>
        <textarea name="retirement_savings_result_messages" class="rs-admin-textarea" rows="12"><?php echo esc_textarea( $messages_val ); ?></textarea>
    </div>

    <div class="rs-admin-section">
        <h4>Nápověda – schéma vstupního pole</h4>
        <pre class="rs-admin-schema">{ "key": "current_age", "type": "slider_number", "label": "Současný věk", "default": 45, "min": 18, "max": 80, "step": 1, "unit": "let", "tooltip": "Popis..." }</pre>
        <h4 style="margin-top:12px;">Nápověda – schéma textace výsledku</h4>
        <pre class="rs-admin-schema">{ "key": "goal_reached", "status": "green", "title": "Nadpis", "text": "Popis..." }</pre>
    </div>
    <?php
}

function retirement_savings_save_meta( $post_id ) {
    if ( ! isset( $_POST['retirement_savings_metabox_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( sanitize_key( $_POST['retirement_savings_metabox_nonce'] ), 'retirement_savings_metabox_action' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( get_post_type( $post_id ) !== 'calculator' ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save default inputs
    if ( isset( $_POST['retirement_savings_default_inputs'] ) ) {
        $raw     = wp_unslash( $_POST['retirement_savings_default_inputs'] );
        $decoded = json_decode( $raw, true );

        if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
            $sanitized = retirement_savings_sanitize_inputs_config( $decoded );
            update_post_meta( $post_id, '_retirement_savings_default_inputs', wp_slash( json_encode( $sanitized, JSON_UNESCAPED_UNICODE ) ) );
        }
    }

    // Save result messages
    if ( isset( $_POST['retirement_savings_result_messages'] ) ) {
        $raw     = wp_unslash( $_POST['retirement_savings_result_messages'] );
        $decoded = json_decode( $raw, true );

        if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
            $sanitized = retirement_savings_sanitize_messages_config( $decoded );
            update_post_meta( $post_id, '_retirement_savings_result_messages', wp_slash( json_encode( $sanitized, JSON_UNESCAPED_UNICODE ) ) );
        }
    }
}
add_action( 'save_post', 'retirement_savings_save_meta' );

function retirement_savings_sanitize_inputs_config( $data ) {
    $clean         = array();
    $allowed_types = array( 'slider_number', 'number' );

    foreach ( $data as $key => $item ) {
        if ( ! is_array( $item ) ) {
            continue;
        }
        $item_key    = sanitize_key( $item['key'] ?? $key );
        $item_type   = in_array( $item['type'] ?? '', $allowed_types, true ) ? $item['type'] : 'number';
        $clean[ $item_key ] = array(
            'key'     => $item_key,
            'type'    => $item_type,
            'label'   => sanitize_text_field( $item['label'] ?? '' ),
            'default' => floatval( $item['default'] ?? 0 ),
            'min'     => floatval( $item['min'] ?? 0 ),
            'max'     => floatval( $item['max'] ?? 100 ),
            'step'    => floatval( $item['step'] ?? 1 ),
            'unit'    => sanitize_text_field( $item['unit'] ?? '' ),
            'tooltip' => sanitize_text_field( $item['tooltip'] ?? '' ),
        );
    }

    return $clean;
}

function retirement_savings_sanitize_messages_config( $data ) {
    $clean            = array();
    $allowed_statuses = array( 'red', 'orange', 'green' );

    foreach ( $data as $key => $item ) {
        if ( ! is_array( $item ) ) {
            continue;
        }
        $item_key = sanitize_key( $item['key'] ?? $key );
        $status   = in_array( $item['status'] ?? '', $allowed_statuses, true ) ? $item['status'] : 'green';
        $clean[ $item_key ] = array(
            'key'    => $item_key,
            'status' => $status,
            'title'  => sanitize_text_field( $item['title'] ?? '' ),
            'text'   => sanitize_textarea_field( $item['text'] ?? '' ),
        );
    }

    return $clean;
}
