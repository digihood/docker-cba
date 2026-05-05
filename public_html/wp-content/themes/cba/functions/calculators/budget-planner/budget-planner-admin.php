<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function budget_planner_add_metaboxes() {
    add_meta_box(
        'budget_planner_config',
        __( 'Konfigurace Plánovače rozpočtu', 'cba' ),
        'budget_planner_render_admin_metabox',
        'calculator',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'budget_planner_add_metaboxes' );

function budget_planner_metabox_is_applicable( $post_id ) {
    $slug  = get_post_field( 'post_name', $post_id );
    $title = get_the_title( $post_id );

    if ( $slug === 'planovac-rozpoctu' ) {
        return true;
    }
    if ( empty( $slug ) && ( strpos( $title, 'Plánovač' ) !== false || strpos( $title, 'planovac' ) !== false ) ) {
        return true;
    }
    return false;
}

function budget_planner_render_admin_metabox( $post ) {
    if ( ! budget_planner_metabox_is_applicable( $post->ID ) ) {
        echo '<p>' . esc_html__( 'Tato konfigurace je dostupná pouze pro příspěvek se slugem "planovac-rozpoctu".', 'cba' ) . '</p>';
        return;
    }

    wp_nonce_field( 'budget_planner_save_meta', 'budget_planner_nonce' );

    $config   = budget_planner_get_config( $post->ID );
    $cats_val = json_encode( $config['categories'],      JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    $items_val = json_encode( $config['items'],          JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    $msgs_val  = json_encode( $config['result_messages'],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    ?>
    <style>
        .bp-admin-section { margin-bottom: 24px; }
        .bp-admin-section h4 { margin: 0 0 8px; font-size: 14px; font-weight: 600; }
        .bp-admin-textarea { width: 100%; font-family: monospace; font-size: 12px; border: 1px solid #ccc; padding: 8px; border-radius: 4px; }
        .bp-admin-notice { background: #fff8e1; border-left: 4px solid #ffc107; padding: 10px 14px; margin-bottom: 16px; font-size: 13px; }
        .bp-admin-schema { background: #f0f4f8; border: 1px solid #d0d9e2; border-radius: 4px; padding: 10px 14px; font-size: 12px; font-family: monospace; white-space: pre-wrap; color: #333; }
    </style>

    <div class="bp-admin-notice">
        <strong>JSON editory</strong> – upravte data přímo v JSON formátu. Při nesprávném formátu nebudou data uložena.
    </div>

    <div class="bp-admin-section">
        <h4>Kategorie (<code>_budget_planner_categories</code>)</h4>
        <p style="font-size:12px;color:#666;margin:0 0 6px;">Schéma: <code>id, name, slug, order, active</code></p>
        <textarea name="budget_planner_categories" class="bp-admin-textarea" rows="10"><?php echo esc_textarea( $cats_val ); ?></textarea>
    </div>

    <div class="bp-admin-section">
        <h4>Položky (<code>_budget_planner_items</code>)</h4>
        <p style="font-size:12px;color:#666;margin:0 0 6px;">Schéma: <code>id, name, slug, type (income|fixed_expense|variable_expense|savings), category, tooltip, default_value, order, active</code></p>
        <textarea name="budget_planner_items" class="bp-admin-textarea" rows="20"><?php echo esc_textarea( $items_val ); ?></textarea>
    </div>

    <div class="bp-admin-section">
        <h4>Textace výsledků (<code>_budget_planner_result_messages</code>)</h4>
        <p style="font-size:12px;color:#666;margin:0 0 6px;">Schéma: <code>min (null=neomezeno), max (null=neomezeno), status (red|orange|green), title, text</code></p>
        <textarea name="budget_planner_result_messages" class="bp-admin-textarea" rows="8"><?php echo esc_textarea( $msgs_val ); ?></textarea>
    </div>

    <div class="bp-admin-section">
        <h4>Nápověda – schéma kategorie</h4>
        <pre class="bp-admin-schema">{ "id": "income", "name": "Příjmy", "slug": "income", "order": 1, "active": true }</pre>
        <h4 style="margin-top:12px;">Nápověda – schéma položky</h4>
        <pre class="bp-admin-schema">{ "id": "salary", "name": "Výplata / mzda", "slug": "salary", "type": "income", "category": "income", "tooltip": "Popis...", "default_value": 0, "order": 1, "active": true }</pre>
        <h4 style="margin-top:12px;">Nápověda – schéma textace výsledku</h4>
        <pre class="bp-admin-schema">{ "min": null, "max": -1, "status": "red", "title": "Nadpis", "text": "Popis..." }</pre>
    </div>
    <?php
}

function budget_planner_save_meta( $post_id ) {
    if ( ! isset( $_POST['budget_planner_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( sanitize_key( $_POST['budget_planner_nonce'] ), 'budget_planner_save_meta' ) ) {
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
        'budget_planner_categories'      => '_budget_planner_categories',
        'budget_planner_items'           => '_budget_planner_items',
        'budget_planner_result_messages' => '_budget_planner_result_messages',
    );

    foreach ( $fields as $post_key => $meta_key ) {
        if ( ! isset( $_POST[ $post_key ] ) ) {
            continue;
        }
        $raw     = wp_unslash( $_POST[ $post_key ] );
        $decoded = json_decode( $raw, true );

        if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $decoded ) ) {
            continue;
        }

        $sanitized = budget_planner_sanitize_config_array( $decoded, $post_key );
        update_post_meta( $post_id, $meta_key, wp_slash( json_encode( $sanitized, JSON_UNESCAPED_UNICODE ) ) );
    }
}
add_action( 'save_post', 'budget_planner_save_meta' );

function budget_planner_sanitize_config_array( $data, $type ) {
    $clean = array();

    foreach ( $data as $item ) {
        if ( ! is_array( $item ) ) {
            continue;
        }
        if ( $type === 'budget_planner_categories' ) {
            $clean[] = array(
                'id'     => sanitize_key( $item['id'] ?? '' ),
                'name'   => sanitize_text_field( $item['name'] ?? '' ),
                'slug'   => sanitize_key( $item['slug'] ?? '' ),
                'order'  => absint( $item['order'] ?? 0 ),
                'active' => ! empty( $item['active'] ),
            );
        } elseif ( $type === 'budget_planner_items' ) {
            $allowed_types = array( 'income', 'fixed_expense', 'variable_expense', 'savings' );
            $item_type     = in_array( $item['type'] ?? '', $allowed_types, true ) ? $item['type'] : 'variable_expense';
            $clean[] = array(
                'id'            => sanitize_key( $item['id'] ?? '' ),
                'name'          => sanitize_text_field( $item['name'] ?? '' ),
                'slug'          => sanitize_key( $item['slug'] ?? '' ),
                'type'          => $item_type,
                'category'      => sanitize_key( $item['category'] ?? '' ),
                'tooltip'       => sanitize_text_field( $item['tooltip'] ?? '' ),
                'default_value' => budget_planner_sanitize_money_value( $item['default_value'] ?? 0 ),
                'order'         => absint( $item['order'] ?? 0 ),
                'active'        => ! empty( $item['active'] ),
            );
        } elseif ( $type === 'budget_planner_result_messages' ) {
            $allowed_statuses = array( 'red', 'orange', 'green' );
            $status           = in_array( $item['status'] ?? '', $allowed_statuses, true ) ? $item['status'] : 'green';
            $clean[] = array(
                'min'    => isset( $item['min'] ) && $item['min'] !== null ? (int) $item['min'] : null,
                'max'    => isset( $item['max'] ) && $item['max'] !== null ? (int) $item['max'] : null,
                'status' => $status,
                'title'  => sanitize_text_field( $item['title'] ?? '' ),
                'text'   => sanitize_textarea_field( $item['text'] ?? '' ),
            );
        }
    }

    return $clean;
}
