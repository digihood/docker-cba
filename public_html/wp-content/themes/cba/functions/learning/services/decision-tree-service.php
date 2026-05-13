<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class FoxoDecisionTreeService {

    // ── Načtení stromu ────────────────────────────────────────────────────────

    public static function get_start_node( FoxoDecisionTree $tree ): ?FoxoDecisionTreeNode {
        if ( $tree->startNodeId ) {
            $node = self::get_node_by_uid( $tree, $tree->startNodeId );
            if ( $node ) return $node;
        }
        return $tree->nodes[0] ?? null;
    }

    public static function get_node_by_uid( FoxoDecisionTree $tree, string $uid ): ?FoxoDecisionTreeNode {
        foreach ( $tree->nodes as $node ) {
            if ( $node->uid === $uid ) return $node;
        }
        return null;
    }

    // ── Zpracování kroku ──────────────────────────────────────────────────────

    /**
     * Validuje odpověď, uloží krok do DB a vrátí data dalšího uzlu.
     *
     * @return array{node?: array, error?: string, status?: int}
     */
    public static function process_step(
        int    $tree_id,
        string $node_uid,
        string $answer_uid,
        string $session_uid,
        int    $user_id,
        int    $path_order
    ): array {
        $post = get_post( $tree_id );
        if ( ! $post || $post->post_type !== 'foxo_decision_tree' ) {
            return [ 'error' => __( 'Rozhodovací strom nenalezen.', 'cba' ), 'status' => 404 ];
        }

        $tree = FoxoDecisionTree::from_post( $post );

        if ( ! $tree->active ) {
            return [ 'error' => __( 'Tento strom momentálně není dostupný.', 'cba' ), 'status' => 403 ];
        }

        $node = self::get_node_by_uid( $tree, $node_uid );
        if ( ! $node ) {
            return [ 'error' => __( 'Uzel nenalezen.', 'cba' ), 'status' => 404 ];
        }

        $answer = null;
        foreach ( $node->answers as $a ) {
            if ( $a->uid === $answer_uid ) {
                $answer = $a;
                break;
            }
        }
        if ( ! $answer ) {
            return [ 'error' => __( 'Odpověď nenalezena nebo nepatří k tomuto uzlu.', 'cba' ), 'status' => 404 ];
        }

        $target = self::get_node_by_uid( $tree, $answer->targetNodeUid );
        if ( ! $target ) {
            return [
                'error'  => __( 'Cílový uzel neexistuje. Kontaktujte správce webu.', 'cba' ),
                'status' => 422,
            ];
        }

        $is_final = ( $target->type === 'result' );

        if ( $user_id ) {
            FoxoLearningDB::save_tree_step( [
                'user_id'          => $user_id,
                'tree_id'          => $tree_id,
                'node_unique_id'   => $node_uid,
                'answer_unique_id' => $answer_uid,
                'target_node_uid'  => $target->uid,
                'session_uid'      => $session_uid,
                'path_order'       => $path_order,
                'is_final'         => $is_final,
                'result_node_uid'  => $is_final ? $target->uid : '',
            ] );

            if ( $is_final ) {
                self::update_user_meta( $user_id, $tree, $target );
            }
        }

        return [
            'node'      => self::format_node_for_frontend( $target ),
            'treeId'    => $tree_id,
            'stepSaved' => (bool) $user_id,
        ];
    }

    // ── Formátování uzlu pro frontend ─────────────────────────────────────────

    public static function format_node_for_frontend( FoxoDecisionTreeNode $node ): array {
        $data = [
            'uid'         => $node->uid,
            'type'        => $node->type,
            'title'       => $node->title,
            'text'        => $node->text,
            'image'       => $node->image,
            'description' => $node->description,
        ];

        if ( $node->type === 'question' ) {
            $data['answers'] = array_map( fn( $a ) => [
                'uid'         => $a->uid,
                'text'        => $a->text,
                'description' => $a->description,
                'ctaLabel'    => $a->ctaLabel,
            ], $node->answers );
        }

        if ( $node->type === 'result' ) {
            $data['resultTitle'] = $node->resultTitle;
            $data['resultText']  = wp_kses_post( $node->resultText );
            $data['ctaLabel']    = $node->resultCtaLabel;
            $data['ctaUrl']      = esc_url_raw( $node->resultCtaUrl );
            $data['relatedContent'] = array_map( fn( $p ) => [
                'id'    => $p->ID,
                'title' => $p->post_title,
                'url'   => get_permalink( $p->ID ),
            ], $node->resultRelatedContent );
        }

        return $data;
    }

    // ── Návštěva ──────────────────────────────────────────────────────────────

    public static function record_visit( int $user_id, int $tree_id ): void {
        if ( ! $user_id ) return;
        $post = get_post( $tree_id );
        if ( ! $post ) return;

        $data = FoxoUserLearningService::get_user_learning_data( $user_id );
        $data['lastVisited']['decisionTree'] = [
            'id'                => $tree_id,
            'title'             => $post->post_title,
            'url'               => get_permalink( $tree_id ),
            'visitedAt'         => current_time( 'mysql' ),
            'lastResultNodeUid' => $data['lastVisited']['decisionTree']['lastResultNodeUid'] ?? '',
        ];
        FoxoUserLearningService::save_user_learning_data( $user_id, $data );
    }

    // ── Usermeta po dokončení ─────────────────────────────────────────────────

    private static function update_user_meta(
        int $user_id,
        FoxoDecisionTree $tree,
        FoxoDecisionTreeNode $result_node
    ): void {
        $data = FoxoUserLearningService::get_user_learning_data( $user_id );
        $data['lastVisited']['decisionTree'] = [
            'id'                => $tree->id,
            'title'             => $tree->title,
            'url'               => $tree->url,
            'visitedAt'         => current_time( 'mysql' ),
            'lastResultNodeUid' => $result_node->uid,
        ];
        FoxoUserLearningService::save_user_learning_data( $user_id, $data );
    }
}
