(function () {
    'use strict';

    const tabs   = document.querySelectorAll('.auth-tab');
    const panels = document.querySelectorAll('.auth-panel');

    if (!tabs.length) return;

    function activateTab(tabName) {
        tabs.forEach(function (tab) {
            const active = tab.dataset.tab === tabName;
            tab.classList.toggle('auth-tab--active', active);
            tab.setAttribute('aria-selected', String(active));
        });
        panels.forEach(function (panel) {
            const active = panel.id === 'auth-panel-' + tabName;
            panel.classList.toggle('auth-panel--active', active);
            panel.setAttribute('aria-hidden', String(!active));
        });
        history.replaceState(null, '', '#' + tabName);
    }

    // Tab click
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activateTab(tab.dataset.tab);
        });
    });

    // URL hash on load (#register, #forgotten, #login)
    const hash = window.location.hash.replace('#', '');
    if (['login', 'register', 'forgotten'].includes(hash)) {
        activateTab(hash);
    }
})();
