import './bootstrap';

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import Sortable from 'sortablejs';

function initSortables() {
    document.querySelectorAll('[data-sortable]:not([data-sortable-initialized])').forEach((el) => {
        el.setAttribute('data-sortable-initialized', 'true');

        Sortable.create(el, {
            handle: '[data-sortable-handle]',
            animation: 150,
            onEnd: () => {
                const method = el.dataset.sortableCall;
                const ids = Array.from(el.children).map((child) => child.dataset.sortableId);
                const parentId = el.dataset.sortableParent ?? null;
                const wireRoot = el.closest('[wire\\:id]');

                if (method && wireRoot && window.Livewire) {
                    window.Livewire.find(wireRoot.getAttribute('wire:id')).call(method, ids, parentId);
                }
            },
        });
    });
}

function onDomReady(callback) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback);
    } else {
        callback();
    }
}

onDomReady(initSortables);
document.addEventListener('livewire:navigated', initSortables);
document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', initSortables);
});

onDomReady(() => {
    const stored = localStorage.getItem('acco-theme');
    const preferred = stored ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    document.documentElement.setAttribute('data-bs-theme', preferred);

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-bs-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', next);
            localStorage.setItem('acco-theme', next);
        });
    });

    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelector('.admin-sidebar')?.classList.toggle('show');
        });
    });
});
