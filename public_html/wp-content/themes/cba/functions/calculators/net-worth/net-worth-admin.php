<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function net_worth_add_metaboxes() {
    add_meta_box(
        'net_worth_config',
        __( 'Konfigurace – Čisté jmění', 'cba' ),
        'net_worth_render_admin_metabox',
        'calculator',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'net_worth_add_metaboxes' );

function net_worth_metabox_is_applicable( $post_id ) {
    $slug  = get_post_field( 'post_name', $post_id );
    $title = get_the_title( $post_id );

    if ( $slug === 'ciste-jmeni' ) {
        return true;
    }
    if ( empty( $slug ) && strpos( $title, 'Čisté jmění' ) !== false ) {
        return true;
    }
    return false;
}

function net_worth_render_admin_metabox( $post ) {
    if ( ! net_worth_metabox_is_applicable( $post->ID ) ) {
        echo '<p>' . esc_html__( 'Tato konfigurace je dostupná pouze pro příspěvek se slugem "ciste-jmeni".', 'cba' ) . '</p>';
        return;
    }

    wp_nonce_field( 'net_worth_metabox_action', 'net_worth_metabox_nonce' );

    $default = net_worth_get_default_config();

    $raw_categories          = get_post_meta( $post->ID, '_net_worth_categories', true );
    $raw_items               = get_post_meta( $post->ID, '_net_worth_items', true );
    $raw_benchmarks          = get_post_meta( $post->ID, '_net_worth_benchmarks', true );
    $raw_result_messages     = get_post_meta( $post->ID, '_net_worth_result_messages', true );
    $raw_recommended_content = get_post_meta( $post->ID, '_net_worth_recommended_content', true );

    $dec_categories          = ! empty( $raw_categories )          ? json_decode( $raw_categories, true )          : null;
    $dec_items               = ! empty( $raw_items )               ? json_decode( $raw_items, true )               : null;
    $dec_benchmarks          = ! empty( $raw_benchmarks )          ? json_decode( $raw_benchmarks, true )          : null;
    $dec_result_messages     = ! empty( $raw_result_messages )     ? json_decode( $raw_result_messages, true )     : null;
    $dec_recommended_content = ! empty( $raw_recommended_content ) ? json_decode( $raw_recommended_content, true ) : null;

    $val_categories          = json_encode(
        ( is_array( $dec_categories ) && ! empty( $dec_categories ) )                   ? $dec_categories          : $default['categories'],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );
    $val_items               = json_encode(
        ( is_array( $dec_items ) && ! empty( $dec_items ) )                             ? $dec_items               : $default['items'],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );
    $val_benchmarks          = json_encode(
        ( is_array( $dec_benchmarks ) && ! empty( $dec_benchmarks ) )                   ? $dec_benchmarks          : $default['benchmarks'],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );
    $val_result_messages     = json_encode(
        ( is_array( $dec_result_messages ) && ! empty( $dec_result_messages ) )         ? $dec_result_messages     : $default['result_messages'],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );
    $val_recommended_content = json_encode(
        ( is_array( $dec_recommended_content ) && ! empty( $dec_recommended_content ) ) ? $dec_recommended_content : $default['recommended_content'],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );
    ?>
    <style>
        .nw-admin-section { margin-bottom: 24px; }
        .nw-admin-section h4 { margin: 0 0 8px; font-size: 14px; font-weight: 600; }
        .nw-admin-textarea { width: 100%; font-family: monospace; font-size: 12px; border: 1px solid #ccc; padding: 8px; border-radius: 4px; }
        .nw-admin-notice { background: #fff8e1; border-left: 4px solid #ffc107; padding: 10px 14px; margin-bottom: 16px; font-size: 13px; }
    </style>

    <div class="nw-admin-notice">
        <strong>JSON editory</strong> – upravte data přímo v JSON formátu. Při nesprávném formátu nebudou data uložena.
    </div>

    <div class="nw-admin-section">
        <h4>Kategorie (<code>_net_worth_categories</code>)</h4>
        <p style="font-size:12px;color:#666;margin:0 0 6px;">Schéma: <code>id, name, slug, type (asset|liability), order, active</code></p>
        <textarea name="net_worth_categories" class="nw-admin-textarea" rows="20"><?php echo esc_textarea( $val_categories ); ?></textarea>
    </div>

    <div class="nw-admin-section">
        <h4>Položky (<code>_net_worth_items</code>)</h4>
        <p style="font-size:12px;color:#666;margin:0 0 6px;">Schéma: <code>id, name, slug, type, subtype, category, tooltip, default_value, order, active, is_liquid</code></p>
        <textarea name="net_worth_items" class="nw-admin-textarea" rows="40"><?php echo esc_textarea( $val_items ); ?></textarea>
    </div>

    <div class="nw-admin-section">
        <h4>Benchmarky (<code>_net_worth_benchmarks</code>)</h4>
        <p style="font-size:12px;color:#666;margin:0 0 6px;">Schéma: <code>debt_to_asset, crisis_resilience, diversification, monthly_expenses</code></p>
        <textarea name="net_worth_benchmarks" class="nw-admin-textarea" rows="16"><?php echo esc_textarea( $val_benchmarks ); ?></textarea>
    </div>

    <div class="nw-admin-section">
        <h4>Textace výsledků (<code>_net_worth_result_messages</code>)</h4>
        <p style="font-size:12px;color:#666;margin:0 0 6px;">Schéma: <code>key, status (red|orange|green), title, text</code></p>
        <textarea name="net_worth_result_messages" class="nw-admin-textarea" rows="16"><?php echo esc_textarea( $val_result_messages ); ?></textarea>
    </div>

    <div class="nw-admin-section">
        <h4>Doporučený obsah (<code>_net_worth_recommended_content</code>)</h4>
        <p style="font-size:12px;color:#666;margin:0 0 6px;">Schéma: <code>key, title, url, conditions[]</code></p>
        <textarea name="net_worth_recommended_content" class="nw-admin-textarea" rows="16"><?php echo esc_textarea( $val_recommended_content ); ?></textarea>
    </div>
    <?php
}

function net_worth_save_meta( $post_id ) {
    if ( ! isset( $_POST['net_worth_metabox_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( sanitize_key( $_POST['net_worth_metabox_nonce'] ), 'net_worth_metabox_action' ) ) {
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

    $fields = array(
        'net_worth_categories'          => '_net_worth_categories',
        'net_worth_items'               => '_net_worth_items',
        'net_worth_benchmarks'          => '_net_worth_benchmarks',
        'net_worth_result_messages'     => '_net_worth_result_messages',
        'net_worth_recommended_content' => '_net_worth_recommended_content',
    );

    foreach ( $fields as $post_key => $meta_key ) {
        if ( isset( $_POST[ $post_key ] ) ) {
            $raw     = wp_unslash( $_POST[ $post_key ] );
            $decoded = json_decode( $raw, true );
            if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
                update_post_meta(
                    $post_id,
                    $meta_key,
                    wp_slash( json_encode( $decoded, JSON_UNESCAPED_UNICODE ) )
                );
            }
        }
    }
}
add_action( 'save_post', 'net_worth_save_meta' );
