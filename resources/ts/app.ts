/**
 * Entrypoint TypeScript per aggsnovate.
 *
 * Tieni questo file snello: importa moduli separati per funzionalità
 * più complesse man mano che il progetto cresce.
 */

// Conferma prima di eliminare (form con method DELETE)
document.querySelectorAll<HTMLFormElement>('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (e) => {
        const message = form.dataset.confirm ?? 'Sei sicuro?';
        if (!window.confirm(message)) {
            e.preventDefault();
        }
    });
});
