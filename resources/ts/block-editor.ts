/**
 * Block Editor — custom Gutenberg-like block editor for aggsnovate pages.
 *
 * Blocks are serialised as { blocks: Block[] } JSON into the hidden #contenuto input.
 *
 * Supported block types:
 *   heading  — level (H1-H5) + text
 *   text     — EasyMDE Markdown editor
 *   image    — file upload → src, alt, caption
 *   callout  — variant (info/warning/success/danger) + TipTap rich text
 *   columns  — 2+ columns, each containing nested blocks (no columns-in-columns)
 */

import Sortable from 'sortablejs';
import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';

// ─── Types ────────────────────────────────────────────────────────────────────

type BlockType = 'heading' | 'text' | 'image' | 'callout' | 'columns' | 'group' | 'button' | 'quote' | 'calendar_item' | 'notice_list';

interface BaseBlock {
    id: string;
    type: BlockType;
    class?: string;
    htmlId?: string;
}

interface HeadingBlock extends BaseBlock {
    type: 'heading';
    level: 1 | 2 | 3 | 4 | 5;
    text: string;
}

interface TextBlock extends BaseBlock {
    type: 'text';
    content: string | null;
}

interface ImageBlock extends BaseBlock {
    type: 'image';
    src?: string;
    alt?: string;
    caption?: string;
}

interface CalloutBlock extends BaseBlock {
    type: 'callout';
    variant: 'info' | 'warning' | 'success' | 'danger';
    content: object | null;
}

interface ColumnData {
    id: string;
    blocks: NestedBlock[];
}

interface ColumnsBlock extends BaseBlock {
    type: 'columns';
    columns: ColumnData[];
}

interface GroupBlock extends BaseBlock {
    type: 'group';
    style: 'default' | 'card' | 'accent' | 'dark';
    layout: 'stack' | 'grid-2' | 'grid-3' | 'side';
    blocks: NestedBlock[];
}

interface ButtonBlock extends BaseBlock {
    type: 'button';
    label: string;
    href: string;
    target: '_self' | '_blank';
    variant: 'primary' | 'secondary' | 'outline';
}

interface QuoteBlock extends BaseBlock {
    type: 'quote';
    content: string;
    author?: string;
    source?: string;
}

interface CalendarItemBlock extends BaseBlock {
    type: 'calendar_item';
    date: string;
    text: string;
}

interface NoticeListBlock extends BaseBlock {
    type: 'notice_list';
}

type NestedBlock = HeadingBlock | TextBlock | ImageBlock | CalloutBlock | ButtonBlock | QuoteBlock | CalendarItemBlock;
type Block = NestedBlock | ColumnsBlock | GroupBlock | NoticeListBlock;

// ─── State ────────────────────────────────────────────────────────────────────

/** TipTap instances: callout blocks only */
const tiptapInstances = new Map<string, Editor>();

/** EasyMDE instances: text blocks */
const easymdeInstances = new Map<string, EasyMDE>();

let uploadUrl  = '/admin/upload';
let csrfToken  = '';

// ─── Public API ───────────────────────────────────────────────────────────────

export function initBlockEditor(container: HTMLElement, hiddenInput: HTMLInputElement): void {
    uploadUrl = container.dataset.uploadUrl ?? uploadUrl;
    csrfToken = container.dataset.csrf ?? '';

    const blockList = container.querySelector<HTMLElement>('#block-list')!;

    // 1. Load existing blocks
    let existingBlocks: Block[] = [];
    try {
        const parsed = JSON.parse(hiddenInput.value || '{}');
        existingBlocks = parsed.blocks ?? [];
    } catch { /* empty */ }

    // 2. Render existing blocks
    existingBlocks.forEach(block => blockList.appendChild(renderBlock(block)));

    // 3. Init main sortable
    initSortable(blockList);

    // 4. Wire "Add block" buttons
    container.querySelectorAll<HTMLButtonElement>('[data-add-block]').forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.dataset.addBlock as BlockType;
            const block = makeDefaultBlock(type);
            const card  = renderBlock(block);
            blockList.appendChild(card);
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });

    // 5. Serialize on form submit
    const form = container.closest('form');
    if (form) {
        form.addEventListener('submit', () => {
            const blocks = serializeBlockList(blockList, false);
            hiddenInput.value = JSON.stringify({ blocks });
        });
    }
}

// ─── Block factory ───────────────────────────────────────────────────────────

function makeDefaultBlock(type: BlockType): Block {
    const id = crypto.randomUUID();
    switch (type) {
        case 'heading':  return { id, type, level: 2, text: '', class: '', htmlId: '' };
        case 'text':     return { id, type, content: '', class: '', htmlId: '' };
        case 'image':    return { id, type, src: '', alt: '', caption: '', class: '', htmlId: '' };
        case 'callout':  return { id, type, variant: 'info', content: null, class: '', htmlId: '' };
        case 'columns':  return {
            id, type,
            columns: [
                { id: crypto.randomUUID(), blocks: [] },
                { id: crypto.randomUUID(), blocks: [] },
            ],
            class: '', htmlId: '',
        };
        case 'group':        return { id, type, style: 'default', layout: 'stack', blocks: [], class: '', htmlId: '' };
        case 'button':       return { id, type, label: '', href: '', target: '_self', variant: 'primary', class: '', htmlId: '' };
        case 'quote':        return { id, type, content: '', author: '', source: '', class: '', htmlId: '' };
        case 'calendar_item': return { id, type, date: '', text: '', class: '', htmlId: '' };
        case 'notice_list':  return { id, type, class: '', htmlId: '' };
    }
}

// ─── Render ───────────────────────────────────────────────────────────────────

function renderBlock(block: Block, nested = false): HTMLElement {
    const card = document.createElement('div');
    card.className = `block-card block-card--${block.type}`;
    card.dataset.id   = block.id;
    card.dataset.type = block.type;

    card.innerHTML = `
        <div class="block-card__header">
            <span class="block-card__drag" title="Trascina per spostare">⠿</span>
            <span class="block-card__label">${blockLabel(block.type)}</span>
            <button type="button" class="block-card__delete" title="Elimina blocco">×</button>
        </div>
        <div class="block-card__body">
            ${renderBlockBody(block, nested)}
        </div>
    `;

    card.querySelector<HTMLButtonElement>('.block-card__delete')!.addEventListener('click', () => {
        destroyEditorInCard(card);
        card.remove();
    });

    afterInsert(card, block, nested);

    return card;
}

function blockLabel(type: BlockType): string {
    const labels: Record<BlockType, string> = {
        heading:       'Titolo',
        text:          'Testo',
        image:         'Immagine',
        callout:       'Callout',
        columns:       'Colonne',
        group:         'Gruppo',
        button:        'Bottone',
        quote:         'Citazione',
        calendar_item: 'Evento',
        notice_list:   'Lista avvisi',
    };
    return labels[type];
}

function renderBlockBody(block: Block, nested: boolean): string {
    const advancedFields = `
        <details class="block-advanced">
            <summary>Avanzato</summary>
            <div class="block-advanced__fields">
                <div class="block-field">
                    <label>CSS class</label>
                    <input type="text" class="block-input block-field-class" placeholder="mia-classe" value="${escHtml(block.class ?? '')}">
                </div>
                <div class="block-field">
                    <label>ID HTML</label>
                    <input type="text" class="block-input block-field-htmlid" placeholder="sezione-intro" value="${escHtml(block.htmlId ?? '')}">
                </div>
            </div>
        </details>`;

    switch (block.type) {
        case 'heading':
            return `
                <div class="block-fields-row">
                    <select class="block-input block-field-level">
                        ${[1,2,3,4,5].map(n => `<option value="${n}"${block.level === n ? ' selected' : ''}>H${n}</option>`).join('')}
                    </select>
                    <input type="text" class="block-input block-field-text" placeholder="Testo del titolo…" value="${escHtml(block.text)}">
                </div>
                ${advancedFields}`;

        case 'text':
            return `
                <textarea class="block-markdown" data-markdown></textarea>
                ${advancedFields}`;

        case 'image':
            return `
                <div class="block-image-wrap">
                    ${block.src ? `<img class="block-image-preview" src="${escHtml(block.src)}" alt="">` : '<span class="block-image-placeholder">Nessuna immagine</span>'}
                    <input type="hidden" class="block-field-src" value="${escHtml(block.src ?? '')}">
                </div>
                <div class="block-fields-row">
                    <label class="block-upload-btn">
                        <input type="file" class="block-file-input" accept="image/*" style="display:none">
                        Scegli immagine…
                    </label>
                    <span class="block-upload-status"></span>
                </div>
                <div class="block-fields-row">
                    <input type="text" class="block-input block-field-alt" placeholder="Testo alternativo (alt)" value="${escHtml(block.alt ?? '')}">
                    <input type="text" class="block-input block-field-caption" placeholder="Didascalia (facoltativa)" value="${escHtml(block.caption ?? '')}">
                </div>
                ${advancedFields}`;

        case 'callout':
            return `
                <div class="block-fields-row">
                    <select class="block-input block-field-variant">
                        ${(['info','warning','success','danger'] as const).map(v =>
                            `<option value="${v}"${block.variant === v ? ' selected' : ''}>${v}</option>`
                        ).join('')}
                    </select>
                </div>
                <div class="block-tiptap" data-tiptap></div>
                ${advancedFields}`;

        case 'button':
            return `
                <div class="block-fields-row">
                    <input type="text" class="block-input block-field-label" placeholder="Testo del bottone…" value="${escHtml(block.label)}">
                    <input type="text" class="block-input block-field-href" placeholder="URL (es. /chi-siamo o https://…)" value="${escHtml(block.href)}">
                </div>
                <div class="block-fields-row">
                    <select class="block-input block-field-target">
                        <option value="_self"${block.target === '_self' ? ' selected' : ''}>Stessa finestra</option>
                        <option value="_blank"${block.target === '_blank' ? ' selected' : ''}>Nuova finestra</option>
                    </select>
                    <select class="block-input block-field-variant">
                        ${(['primary','secondary','outline'] as const).map(v =>
                            `<option value="${v}"${block.variant === v ? ' selected' : ''}>${v}</option>`
                        ).join('')}
                    </select>
                </div>
                ${advancedFields}`;

        case 'quote':
            return `
                <textarea class="block-input block-field-content" rows="4" placeholder="Testo della citazione…">${escHtml(block.content)}</textarea>
                <div class="block-fields-row">
                    <input type="text" class="block-input block-field-author" placeholder="Autore (facoltativo)" value="${escHtml(block.author ?? '')}">
                    <input type="text" class="block-input block-field-source" placeholder="Fonte / opera (facoltativa)" value="${escHtml(block.source ?? '')}">
                </div>
                ${advancedFields}`;

        case 'calendar_item':
            return `
                <input type="text" class="block-input block-field-date" placeholder="Data (es. 15 marzo 2025)" value="${escHtml(block.date)}">
                <textarea class="block-input block-field-text" rows="3" placeholder="Testo dell'evento…">${escHtml(block.text)}</textarea>
                ${advancedFields}`;

        case 'notice_list':
            return `<p class="block-placeholder-info">Lista avvisi — renderizzata dinamicamente dal server.</p>
                ${advancedFields}`;

        case 'group': {
            const groupBlocksHtml = block.blocks.map(sub => renderBlock(sub, true).outerHTML).join('');
            return `
                <div class="block-fields-row">
                    <select class="block-input block-field-style">
                        ${(['default','card','accent','dark'] as const).map(v =>
                            `<option value="${v}"${block.style === v ? ' selected' : ''}>${v}</option>`
                        ).join('')}
                    </select>
                    <select class="block-input block-field-layout">
                        ${(['stack','grid-2','grid-3','side'] as const).map(v =>
                            `<option value="${v}"${block.layout === v ? ' selected' : ''}>${v}</option>`
                        ).join('')}
                    </select>
                </div>
                <div class="block-group-add-buttons">
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="heading">+Titolo</button>
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="text">+Testo</button>
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="image">+Immagine</button>
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="callout">+Callout</button>
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="button">+Bottone</button>
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="quote">+Citazione</button>
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="calendar_item">+Evento</button>
                </div>
                <div class="block-list block-list--nested" data-nested-list>
                    ${groupBlocksHtml}
                </div>
                ${advancedFields}`;
        }

        case 'columns': {
            const colsHtml = block.columns.map(col => `
                <div class="block-column-editor" data-col-id="${col.id}">
                    <div class="block-column-editor__header">
                        <span>Colonna</span>
                        ${!nested ? `<div class="block-column-add-buttons">
                            <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="heading">+H</button>
                            <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="text">+T</button>
                            <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="image">+I</button>
                            <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="callout">+C</button>
                        </div>` : ''}
                    </div>
                    <div class="block-list block-list--nested" data-nested-list>
                        ${col.blocks.map(sub => renderBlock(sub, true).outerHTML).join('')}
                    </div>
                </div>`).join('');
            return `
                <div class="block-columns-editor">${colsHtml}</div>
                <div class="block-column-actions">
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-column>+ Colonna</button>
                    <button type="button" class="block-add-btn block-add-btn--sm block-add-btn--danger" data-remove-column>− Colonna</button>
                </div>
                ${advancedFields}`;
        }
    }
}

// ─── Post-insert hooks ────────────────────────────────────────────────────────

function afterInsert(card: HTMLElement, block: Block, nested: boolean): void {
    if (block.type === 'text') {
        const ta = card.querySelector<HTMLTextAreaElement>('[data-markdown]')!;
        const raw = (block as TextBlock).content;
        const initialValue = typeof raw === 'string' ? raw : '';
        easymdeInstances.set(block.id, spawnEasyMDE(ta, initialValue));
    }

    if (block.type === 'callout') {
        const el = card.querySelector<HTMLElement>('[data-tiptap]')!;
        tiptapInstances.set(block.id, spawnTipTap(el, (block as CalloutBlock).content));
    }

    if (block.type === 'image') {
        wireImageUpload(card);
    }

    if (block.type === 'columns' && !nested) {
        wireColumnsBlock(card, block as ColumnsBlock);
    }

    if (block.type === 'group' && !nested) {
        wireGroupBlock(card, block as GroupBlock);
    }
}

function wireImageUpload(card: HTMLElement): void {
    const fileInput   = card.querySelector<HTMLInputElement>('.block-file-input')!;
    const srcField    = card.querySelector<HTMLInputElement>('.block-field-src')!;
    const imageWrap   = card.querySelector<HTMLElement>('.block-image-wrap')!;
    const statusEl    = card.querySelector<HTMLElement>('.block-upload-status')!;

    fileInput.addEventListener('change', async () => {
        const file = fileInput.files?.[0];
        if (!file) return;
        statusEl.textContent = 'Caricamento…';

        try {
            const url = await uploadImage(file);
            srcField.value = url;
            let img = imageWrap.querySelector<HTMLImageElement>('img');
            if (!img) {
                imageWrap.querySelector('.block-image-placeholder')?.remove();
                img = document.createElement('img');
                img.className = 'block-image-preview';
                imageWrap.prepend(img);
            }
            img.src = url;
            statusEl.textContent = '✓ Caricato';
        } catch {
            statusEl.textContent = '✗ Errore nel caricamento';
        }
    });
}

function wireColumnsBlock(card: HTMLElement, block: ColumnsBlock): void {
    card.querySelectorAll<HTMLElement>('[data-nested-list]').forEach(list => {
        initSortable(list, true);
    });

    card.querySelectorAll<HTMLElement>('.block-column-editor').forEach(colEl => {
        const nestedList = colEl.querySelector<HTMLElement>('[data-nested-list]')!;

        colEl.querySelectorAll<HTMLButtonElement>('[data-add-nested]').forEach(btn => {
            btn.addEventListener('click', () => {
                const type = btn.dataset.addNested as BlockType;
                if (type === 'columns') return;
                const nestedBlock = makeDefaultBlock(type) as NestedBlock;
                const nestedCard  = renderBlock(nestedBlock, true);
                nestedList.appendChild(nestedCard);
            });
        });
    });

    const addColBtn = card.querySelector<HTMLButtonElement>('[data-add-column]');
    addColBtn?.addEventListener('click', () => {
        const colsEditor = card.querySelector<HTMLElement>('.block-columns-editor')!;
        const colId = crypto.randomUUID();
        const colEl = document.createElement('div');
        colEl.className = 'block-column-editor';
        colEl.dataset.colId = colId;
        colEl.innerHTML = `
            <div class="block-column-editor__header">
                <span>Colonna</span>
                <div class="block-column-add-buttons">
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="heading">+H</button>
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="text">+T</button>
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="image">+I</button>
                    <button type="button" class="block-add-btn block-add-btn--sm" data-add-nested="callout">+C</button>
                </div>
            </div>
            <div class="block-list block-list--nested" data-nested-list></div>`;
        colsEditor.appendChild(colEl);

        const nestedList = colEl.querySelector<HTMLElement>('[data-nested-list]')!;
        initSortable(nestedList, true);

        colEl.querySelectorAll<HTMLButtonElement>('[data-add-nested]').forEach(btn => {
            btn.addEventListener('click', () => {
                const type = btn.dataset.addNested as BlockType;
                if (type === 'columns') return;
                const nestedBlock = makeDefaultBlock(type) as NestedBlock;
                nestedList.appendChild(renderBlock(nestedBlock, true));
            });
        });
    });

    const removeColBtn = card.querySelector<HTMLButtonElement>('[data-remove-column]');
    removeColBtn?.addEventListener('click', () => {
        const colsEditor = card.querySelector<HTMLElement>('.block-columns-editor')!;
        const cols = colsEditor.querySelectorAll<HTMLElement>('.block-column-editor');
        if (cols.length > 1) {
            const last = cols[cols.length - 1];
            last.querySelectorAll<HTMLElement>('[data-id]').forEach(c => destroyEditorInCard(c));
            last.remove();
        }
    });
}

function wireGroupBlock(card: HTMLElement, _block: GroupBlock): void {
    const nestedList = card.querySelector<HTMLElement>('[data-nested-list]')!;
    initSortable(nestedList, true);

    card.querySelectorAll<HTMLButtonElement>('[data-add-nested]').forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.dataset.addNested as BlockType;
            if (type === 'columns' || type === 'group' || type === 'notice_list') return;
            const nestedBlock = makeDefaultBlock(type) as NestedBlock;
            nestedList.appendChild(renderBlock(nestedBlock, true));
        });
    });
}

// ─── Serialization ────────────────────────────────────────────────────────────

function serializeBlockList(list: HTMLElement, nested: boolean): Block[] {
    const cards = list.querySelectorAll<HTMLElement>(':scope > .block-card');
    return Array.from(cards).map(card => serializeCard(card, nested));
}

function serializeCard(card: HTMLElement, nested: boolean): Block {
    const id   = card.dataset.id!;
    const type = card.dataset.type as BlockType;
    const cls  = card.querySelector<HTMLInputElement>('.block-field-class')?.value ?? '';
    const hid  = card.querySelector<HTMLInputElement>('.block-field-htmlid')?.value ?? '';

    switch (type) {
        case 'heading': return {
            id, type, class: cls, htmlId: hid,
            level: parseInt(card.querySelector<HTMLSelectElement>('.block-field-level')!.value, 10) as 1|2|3|4|5,
            text:  card.querySelector<HTMLInputElement>('.block-field-text')!.value,
        };

        case 'text': return {
            id, type, class: cls, htmlId: hid,
            content: easymdeInstances.get(id)?.value() ?? null,
        };

        case 'image': return {
            id, type, class: cls, htmlId: hid,
            src:     card.querySelector<HTMLInputElement>('.block-field-src')?.value ?? '',
            alt:     card.querySelector<HTMLInputElement>('.block-field-alt')?.value ?? '',
            caption: card.querySelector<HTMLInputElement>('.block-field-caption')?.value ?? '',
        };

        case 'callout': return {
            id, type, class: cls, htmlId: hid,
            variant: (card.querySelector<HTMLSelectElement>('.block-field-variant')?.value ?? 'info') as CalloutBlock['variant'],
            content: tiptapInstances.get(id)?.getJSON() ?? null,
        };

        case 'button': return {
            id, type, class: cls, htmlId: hid,
            label:   card.querySelector<HTMLInputElement>('.block-field-label')!.value,
            href:    card.querySelector<HTMLInputElement>('.block-field-href')!.value,
            target:  (card.querySelector<HTMLSelectElement>('.block-field-target')?.value ?? '_self') as ButtonBlock['target'],
            variant: (card.querySelector<HTMLSelectElement>('.block-field-variant')?.value ?? 'primary') as ButtonBlock['variant'],
        };

        case 'quote': return {
            id, type, class: cls, htmlId: hid,
            content: card.querySelector<HTMLTextAreaElement>('.block-field-content')?.value ?? '',
            author:  card.querySelector<HTMLInputElement>('.block-field-author')?.value ?? '',
            source:  card.querySelector<HTMLInputElement>('.block-field-source')?.value ?? '',
        };

        case 'calendar_item': return {
            id, type, class: cls, htmlId: hid,
            date: card.querySelector<HTMLInputElement>('.block-field-date')?.value ?? '',
            text: card.querySelector<HTMLTextAreaElement>('.block-field-text')?.value ?? '',
        };

        case 'notice_list': return { id, type, class: cls, htmlId: hid };

        case 'group': {
            const nestedList = card.querySelector<HTMLElement>(':scope > .block-card__body > [data-nested-list]')
                ?? card.querySelector<HTMLElement>('[data-nested-list]')!;
            return {
                id, type, class: cls, htmlId: hid,
                style:  (card.querySelector<HTMLSelectElement>('.block-field-style')?.value ?? 'default') as GroupBlock['style'],
                layout: (card.querySelector<HTMLSelectElement>('.block-field-layout')?.value ?? 'stack') as GroupBlock['layout'],
                blocks: serializeBlockList(nestedList, true) as NestedBlock[],
            };
        }

        case 'columns': {
            const columns: ColumnData[] = [];
            card.querySelectorAll<HTMLElement>('.block-column-editor').forEach(colEl => {
                const nestedList = colEl.querySelector<HTMLElement>('[data-nested-list]')!;
                columns.push({
                    id:     colEl.dataset.colId ?? crypto.randomUUID(),
                    blocks: serializeBlockList(nestedList, true) as NestedBlock[],
                });
            });
            return { id, type, class: cls, htmlId: hid, columns };
        }
    }
}

// ─── Cleanup helpers ──────────────────────────────────────────────────────────

function destroyEditorInCard(card: HTMLElement): void {
    const id = card.dataset.id;
    if (id) {
        if (tiptapInstances.has(id)) {
            tiptapInstances.get(id)!.destroy();
            tiptapInstances.delete(id);
        }
        if (easymdeInstances.has(id)) {
            easymdeInstances.get(id)!.toTextArea();
            easymdeInstances.delete(id);
        }
    }
    card.querySelectorAll<HTMLElement>('.block-card').forEach(nested => {
        const nid = nested.dataset.id;
        if (nid) {
            if (tiptapInstances.has(nid)) {
                tiptapInstances.get(nid)!.destroy();
                tiptapInstances.delete(nid);
            }
            if (easymdeInstances.has(nid)) {
                easymdeInstances.get(nid)!.toTextArea();
                easymdeInstances.delete(nid);
            }
        }
    });
}

// ─── SortableJS ───────────────────────────────────────────────────────────────

function initSortable(list: HTMLElement, nested = false): void {
    Sortable.create(list, {
        animation:       150,
        handle:          '.block-card__drag',
        group:           nested ? 'nested' : 'main',
        ghostClass:      'block-card--ghost',
        dragClass:       'block-card--dragging',
    });
}

// ─── TipTap (callout only) ────────────────────────────────────────────────────

function spawnTipTap(el: HTMLElement, content: object | null): Editor {
    return new Editor({
        element: el,
        extensions: [
            StarterKit,
            Link.configure({ openOnClick: false }),
        ],
        content: content ?? undefined,
    });
}

// ─── EasyMDE (text blocks) ────────────────────────────────────────────────────

function spawnEasyMDE(el: HTMLTextAreaElement, content: string): EasyMDE {
    return new EasyMDE({
        element: el,
        initialValue: content,
        spellChecker: false,
        toolbar: ['bold', 'italic', 'heading', '|', 'unordered-list', 'ordered-list', '|', 'link', '|', 'preview'],
        status: false,
    });
}

// ─── Image upload ─────────────────────────────────────────────────────────────

async function uploadImage(file: File): Promise<string> {
    const formData = new FormData();
    formData.append('file', file);

    const res = await fetch(uploadUrl, {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body:    formData,
    });

    if (!res.ok) throw new Error('Upload failed');
    const json: { url: string } = await res.json();
    return json.url;
}

// ─── Utils ────────────────────────────────────────────────────────────────────

function escHtml(str: string): string {
    return str
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}
