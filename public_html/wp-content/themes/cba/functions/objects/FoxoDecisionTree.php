<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class FoxoDecisionTreeAnswer {
    public string $uid           = '';
    public string $text          = '';
    public string $description   = '';
    public string $targetNodeUid = '';
    public string $ctaLabel      = '';
}

class FoxoDecisionTreeNode {
    public string $uid         = '';
    public string $type        = 'question'; // question | result
    public string $title       = '';
    public string $text        = '';
    public string $image       = '';
    public string $description = '';

    /** @var FoxoDecisionTreeAnswer[] — pouze u question uzlů */
    public array $answers = [];

    // Pole pouze pro result uzly
    public string $resultTitle            = '';
    public string $resultText             = '';
    public string $resultCtaLabel         = '';
    public string $resultCtaUrl           = '';
    public array  $resultRelatedContent   = [];
    public string $resultEmailSummaryText = '';
}

class FoxoDecisionTree {
    public int    $id                  = 0;
    public string $title               = '';
    public string $slug                = '';
    public string $url                 = '';
    public string $introText           = '';
    public bool   $active              = true;
    public string $startNodeId         = '';
    public bool   $progressEnabled     = true;
    public bool   $emailReportEnabled  = false;
    public array  $relatedContent      = [];

    /** @var FoxoDecisionTreeNode[] */
    public array $nodes = [];

    public static function from_post( WP_Post $post ): self {
        $t = new self();
        $t->id                 = $post->ID;
        $t->title              = $post->post_title;
        $t->slug               = $post->post_name;
        $t->url                = get_permalink( $post->ID );
        $t->introText          = (string) get_field( 'tree_intro_text', $post->ID );
        $t->active             = (bool)   get_field( 'tree_active', $post->ID );
        $t->startNodeId        = (string) get_field( 'tree_start_node_id', $post->ID );
        $t->progressEnabled    = (bool)   get_field( 'tree_progress_enabled', $post->ID );
        $t->emailReportEnabled = (bool)   get_field( 'tree_email_report_enabled', $post->ID );

        $related          = get_field( 'tree_related_content', $post->ID );
        $t->relatedContent = is_array( $related ) ? $related : [];

        $raw_nodes = get_field( 'tree_nodes', $post->ID );
        if ( is_array( $raw_nodes ) ) {
            foreach ( $raw_nodes as $rn ) {
                $node              = new FoxoDecisionTreeNode();
                $node->uid         = (string) ( $rn['node_unique_id'] ?? '' );
                $node->type        = (string) ( $rn['node_type'] ?? 'question' );
                $node->title       = (string) ( $rn['node_title'] ?? '' );
                $node->text        = (string) ( $rn['node_text'] ?? '' );
                $node->description = (string) ( $rn['node_description'] ?? '' );
                $node->image       = is_array( $rn['node_image'] ?? null )
                    ? (string) ( $rn['node_image']['url'] ?? '' )
                    : '';

                if ( $node->type === 'question' ) {
                    $raw_answers = $rn['node_answers'] ?? [];
                    if ( is_array( $raw_answers ) ) {
                        foreach ( $raw_answers as $ra ) {
                            $a                = new FoxoDecisionTreeAnswer();
                            $a->uid           = (string) ( $ra['answer_unique_id'] ?? '' );
                            $a->text          = (string) ( $ra['answer_text'] ?? '' );
                            $a->description   = (string) ( $ra['answer_description'] ?? '' );
                            $a->targetNodeUid = (string) ( $ra['target_node_unique_id'] ?? '' );
                            $a->ctaLabel      = (string) ( $ra['answer_cta_label'] ?? '' );
                            $node->answers[]  = $a;
                        }
                    }
                } else {
                    $node->resultTitle            = (string) ( $rn['result_title'] ?? '' );
                    $node->resultText             = (string) ( $rn['result_text'] ?? '' );
                    $node->resultCtaLabel         = (string) ( $rn['result_cta_label'] ?? '' );
                    $node->resultCtaUrl           = (string) ( $rn['result_cta_url'] ?? '' );
                    $node->resultEmailSummaryText = (string) ( $rn['result_email_summary_text'] ?? '' );
                    $rel                          = $rn['result_related_content'] ?? [];
                    $node->resultRelatedContent   = is_array( $rel ) ? $rel : [];
                }

                $t->nodes[] = $node;
            }
        }

        return $t;
    }
}
