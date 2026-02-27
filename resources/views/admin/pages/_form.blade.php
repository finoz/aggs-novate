{{-- Partial condiviso da create e edit --}}

<div class="form-group">
    <label for="titolo">Titolo <span class="required">*</span></label>
    <input
        id="titolo"
        type="text"
        name="titolo"
        value="{{ old('titolo', $page->titolo ?? '') }}"
        required
        maxlength="255"
        class="form-control @error('titolo') is-invalid @enderror"
    >
    @error('titolo')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="slug">Slug (URL) <span class="required">*</span></label>
    <div class="slug-preview">
        <span class="slug-preview__base">{{ config('app.url') }}/</span>
        <input
            id="slug"
            type="text"
            name="slug"
            value="{{ old('slug', $page->slug ?? '') }}"
            required
            maxlength="255"
            pattern="[a-z0-9\-]+"
            class="form-control @error('slug') is-invalid @enderror"
        >
    </div>
    @error('slug')
        <span class="form-error">{{ $message }}</span>
    @enderror
    <span class="form-hint">Solo lettere minuscole, numeri e trattini. Es: <code>chi-siamo</code></span>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="ordinamento">Ordine nel menu</label>
        <input
            id="ordinamento"
            type="number"
            name="ordinamento"
            value="{{ old('ordinamento', $page->ordinamento ?? 0) }}"
            min="0"
            class="form-control form-control--narrow @error('ordinamento') is-invalid @enderror"
        >
        @error('ordinamento')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group form-group--checkbox">
        <label>
            <input
                type="checkbox"
                name="pubblicata"
                value="1"
                {{ old('pubblicata', $page->pubblicata ?? true) ? 'checked' : '' }}
            >
            Pubblicata (visibile nel sito e nel menu)
        </label>
    </div>
</div>

<div class="form-group">
    <label>Contenuto</label>
    <div id="block-editor" class="block-editor"
         data-upload-url="{{ route('admin.upload') }}"
         data-csrf="{{ csrf_token() }}">
        <div id="block-list" class="block-list">
            {{-- popolato da JS al caricamento --}}
        </div>
        <div class="block-add-buttons">
            <span class="block-add-buttons__label">Aggiungi blocco:</span>
            <button type="button" class="block-add-btn" data-add-block="heading">&#43; Titolo</button>
            <button type="button" class="block-add-btn" data-add-block="text">&#43; Testo</button>
            <button type="button" class="block-add-btn" data-add-block="image">&#43; Immagine</button>
            <button type="button" class="block-add-btn" data-add-block="callout">&#43; Callout</button>
            <button type="button" class="block-add-btn" data-add-block="columns">&#43; Colonne</button>
        </div>
    </div>
    <input
        type="hidden"
        id="contenuto"
        name="contenuto"
        value="{{ old('contenuto', $page->contenuto ? json_encode($page->contenuto) : '') }}"
    >
    @error('contenuto')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>
