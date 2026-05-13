<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/** @var FoxoDecisionTree $tree */
$tree = $args['tree'] ?? null;

if ( ! $tree instanceof FoxoDecisionTree ) return;
if ( ! $tree->active ) {
    echo '<p class="foxo-notice">' . esc_html__( 'Tento průvodce momentálně není dostupný.', 'cba' ) . '</p>';
    return;
}
if ( empty( $tree->nodes ) ) {
    echo '<p class="foxo-notice">' . esc_html__( 'Průvodce zatím neobsahuje žádné uzly.', 'cba' ) . '</p>';
    return;
}
?>

<div class="foxo-tree" id="foxo-tree-<?php echo esc_attr( $tree->id ); ?>" data-tree-id="<?php echo esc_attr( $tree->id ); ?>">

    <!-- Úvodní sekce -->
    <div class="foxo-tree__intro-wrap">
        <?php if ( $tree->introText ) : ?>
            <p class="foxo-tree__intro-text"><?php echo wp_kses_post( $tree->introText ); ?></p>
        <?php endif; ?>

        <button type="button" id="foxo-tree-start" class="<?php echo d1g1B::btn_class( 'primary' ); ?>">
            <?php esc_html_e( 'Spustit průvodce', 'cba' ); ?>
        </button>
    </div>

    <!-- Progress bar -->
    <?php if ( $tree->progressEnabled ) : ?>
        <div class="foxo-tree__progress" aria-live="polite" hidden></div>
    <?php endif; ?>

    <!-- Navigace zpět -->
    <button type="button" id="foxo-tree-back" class="foxo-tree__back-btn" hidden>
        &larr; <?php esc_html_e( 'Zpět', 'cba' ); ?>
    </button>

    <!-- Dynamicky plněný obsah uzlu -->
    <div class="foxo-tree__node-wrap" hidden></div>

</div>
