/* Foxo Decision Tree – frontend logic */
(function ($) {
    'use strict';

    const D       = window.FoxoDecisionTreeData || {};
    const $tree   = $('.foxo-tree');
    const treeId  = $tree.data('tree-id');

    if (!treeId) return;

    const $intro    = $tree.find('.foxo-tree__intro-wrap');
    const $nodeWrap = $tree.find('.foxo-tree__node-wrap');
    const $progress = $tree.find('.foxo-tree__progress');
    const $backBtn  = $tree.find('#foxo-tree-back');

    let sessionUid = null;
    let stepCount  = 0;
    const history  = []; // [{html, stepCount}, …]

    // ── Tlačítka ─────────────────────────────────────────────────────────────

    $tree.find('#foxo-tree-start').on('click', startTree);
    $backBtn.on('click', goBack);
    $tree.on('click', '#foxo-tree-restart', restartTree);

    // Delegace na dynamicky vygenerované odpovědi
    $tree.on('click', '.foxo-tree__answer-btn', function () {
        const answerUid = $(this).data('answer-uid');
        const nodeUid   = $nodeWrap.find('[data-node-uid]').data('node-uid');
        if (answerUid && nodeUid) {
            submitAnswer(String(nodeUid), String(answerUid));
        }
    });

    // ── Spuštění stromu ───────────────────────────────────────────────────────

    function startTree() {
        $intro.hide();
        showLoading();

        $.ajax({
            url: D.restUrl + treeId + '/start',
            method: 'POST',
            headers: { 'X-WP-Nonce': D.nonce },
            success: function (res) {
                sessionUid = res.sessionUid;
                stepCount  = 1;
                renderNode(res.node);
                updateProgress();

                if (D.userId) {
                    recordVisit();
                }
            },
            error: function (xhr) {
                showError(xhr.responseJSON?.message || D.i18n.error);
            },
        });
    }

    // ── Odeslání odpovědi ─────────────────────────────────────────────────────

    function submitAnswer(nodeUid, answerUid) {
        history.push({ html: $nodeWrap.html(), stepCount: stepCount });
        showLoading();
        stepCount++;
        $backBtn.show();

        $.ajax({
            url: D.restUrl + treeId + '/step',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                node_uid:    nodeUid,
                answer_uid:  answerUid,
                session_uid: sessionUid,
                path_order:  stepCount,
            }),
            headers: { 'X-WP-Nonce': D.nonce },
            success: function (res) {
                renderNode(res.node);
                updateProgress();
            },
            error: function (xhr) {
                // Vrátíme se k předchozímu uzlu při chybě
                if (history.length) {
                    const prev = history.pop();
                    $nodeWrap.html(prev.html);
                    stepCount = prev.stepCount;
                    updateProgress();
                }
                showInlineError(xhr.responseJSON?.message || D.i18n.error);
            },
        });
    }

    // ── Navigace zpět ─────────────────────────────────────────────────────────

    function goBack() {
        if (!history.length) return;
        const prev = history.pop();
        $nodeWrap.html(prev.html);
        stepCount = prev.stepCount;
        updateProgress();
        if (!history.length) $backBtn.hide();
    }

    // ── Restart ───────────────────────────────────────────────────────────────

    function restartTree() {
        sessionUid = null;
        stepCount  = 0;
        history.length = 0;
        $backBtn.hide();
        $progress.hide();
        $nodeWrap.empty().hide();
        $intro.show();
        $intro[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ── Vykreslení uzlu ───────────────────────────────────────────────────────

    function renderNode(node) {
        if (!node) {
            showError(D.i18n.nodeError || 'Uzel nenalezen.');
            return;
        }
        if (node.type === 'question') {
            renderQuestion(node);
        } else {
            renderResult(node);
        }
        $nodeWrap.show();
        $nodeWrap[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function renderQuestion(node) {
        let html = '<div class="foxo-tree__node foxo-tree__node--question" data-node-uid="' + esc(node.uid) + '">';

        if (node.image) {
            html += '<img src="' + esc(node.image) + '" alt="" class="foxo-tree__node-img" loading="lazy">';
        }

        html += '<h2 class="foxo-tree__question-text">' + esc(node.text) + '</h2>';

        if (node.description) {
            html += '<p class="foxo-tree__question-desc">' + esc(node.description) + '</p>';
        }

        html += '<div class="foxo-tree__answers">';
        (node.answers || []).forEach(function (answer) {
            const label = answer.ctaLabel || answer.text;
            html += '<button type="button" class="foxo-tree__answer-btn" data-answer-uid="' + esc(answer.uid) + '">';
            html += '<span class="foxo-tree__answer-label">' + esc(label) + '</span>';
            if (answer.description) {
                html += '<span class="foxo-tree__answer-desc">' + esc(answer.description) + '</span>';
            }
            html += '</button>';
        });
        html += '</div>';
        html += '</div>';

        $nodeWrap.html(html);
    }

    function renderResult(node) {
        const title = node.resultTitle || node.title || (D.i18n.result || 'Váš výsledek');

        let html = '<div class="foxo-tree__node foxo-tree__node--result" data-node-uid="' + esc(node.uid) + '">';

        if (node.image) {
            html += '<img src="' + esc(node.image) + '" alt="" class="foxo-tree__node-img" loading="lazy">';
        }

        html += '<h2 class="foxo-tree__result-title">' + esc(title) + '</h2>';

        if (node.resultText) {
            // resultText je wp_kses_post HTML z backendu
            html += '<div class="foxo-tree__result-text">' + node.resultText + '</div>';
        } else if (node.text) {
            html += '<p class="foxo-tree__result-text">' + esc(node.text) + '</p>';
        }

        if (node.description) {
            html += '<p class="foxo-tree__result-desc">' + esc(node.description) + '</p>';
        }

        if (node.ctaLabel && node.ctaUrl) {
            html += '<a href="' + esc(node.ctaUrl) + '" class="foxo-tree__result-cta">' + esc(node.ctaLabel) + '</a>';
        }

        if (node.relatedContent && node.relatedContent.length) {
            html += '<div class="foxo-tree__related">';
            html += '<h3 class="foxo-tree__related-title">' + esc(D.i18n.related || 'Doporučujeme') + '</h3>';
            html += '<ul class="foxo-tree__related-list">';
            node.relatedContent.forEach(function (item) {
                html += '<li><a href="' + esc(item.url) + '">' + esc(item.title) + '</a></li>';
            });
            html += '</ul></div>';
        }

        html += '<button type="button" id="foxo-tree-restart" class="foxo-tree__restart-btn">';
        html += esc(D.i18n.restart || 'Začít znovu') + '</button>';
        html += '</div>';

        $nodeWrap.html(html);
        $backBtn.hide();
    }

    // ── Progress ──────────────────────────────────────────────────────────────

    function updateProgress() {
        if (!D.progressEnabled || !stepCount) {
            $progress.hide();
            return;
        }
        const label = (D.i18n.step || 'Krok %d').replace('%d', stepCount);
        $progress.text(label).show();
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    function showLoading() {
        $nodeWrap.html('<p class="foxo-tree__loading">' + esc(D.i18n.loading || 'Načítám…') + '</p>').show();
    }

    function showError(msg) {
        $nodeWrap.html('<p class="foxo-notice foxo-notice--error">' + esc(msg || D.i18n.error || 'Chyba.') + '</p>').show();
    }

    function showInlineError(msg) {
        const $err = $('<p class="foxo-notice foxo-notice--error"></p>').text(msg || D.i18n.error || 'Chyba.');
        $nodeWrap.find('.foxo-tree__node').append($err);
    }

    function esc(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function recordVisit() {
        $.ajax({
            url: D.restUrl + treeId + '/visit',
            method: 'POST',
            headers: { 'X-WP-Nonce': D.nonce },
        });
    }

})(jQuery);
