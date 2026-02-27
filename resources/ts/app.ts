/**
 * Entrypoint TypeScript per aggsnovate.
 */

import { initBlockEditor } from './block-editor';

// ─── Block Editor (admin: form pagine) ────────────────────────────────────────
const blockEditorEl = document.getElementById('block-editor');
if (blockEditorEl) {
    const hiddenInput = document.getElementById('contenuto') as HTMLInputElement;

    // ─── Auto-slug da titolo (solo su create, quando slug è vuoto) ───────────
    const titleInput = document.getElementById('titolo') as HTMLInputElement | null;
    const slugInput  = document.getElementById('slug')   as HTMLInputElement | null;
    if (titleInput && slugInput && !slugInput.value) {
        titleInput.addEventListener('input', () => {
            slugInput.value = titleInput.value
                .toLowerCase()
                .normalize('NFD').replace(/\p{Diacritic}/gu, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });
    }

    initBlockEditor(blockEditorEl, hiddenInput);
}

// ─── Conferma prima di eliminare (form con method DELETE) ─────────────────────
document.querySelectorAll<HTMLFormElement>('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (e) => {
        const message = form.dataset.confirm ?? 'Sei sicuro?';
        if (!window.confirm(message)) {
            e.preventDefault();
        }
    });
});
