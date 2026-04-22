{{-- Partial condiviso da create e edit --}}

<div class="form-group">
    <label for="heading">Titolo <span class="required">*</span></label>
    <input
        id="heading"
        type="text"
        name="heading"
        value="{{ old('heading', $notice->heading ?? '') }}"
        required
        maxlength="255"
        class="form-control @error('heading') is-invalid @enderror"
    >
    @error('heading')<span class="form-error">{{ $message }}</span>@enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label for="date">Data <span class="required">*</span></label>
        <input
            id="date"
            type="text"
            name="date"
            value="{{ old('date', $notice->date ?? '') }}"
            required
            maxlength="255"
            placeholder="es. 22 aprile 2026"
            class="form-control @error('date') is-invalid @enderror"
        >
        @error('date')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label for="tag">Tag</label>
        <input
            id="tag"
            type="text"
            name="tag"
            value="{{ old('tag', $notice->tag ?? '') }}"
            maxlength="100"
            placeholder="es. Comunicazione"
            class="form-control @error('tag') is-invalid @enderror"
        >
        @error('tag')<span class="form-error">{{ $message }}</span>@enderror
    </div>
</div>

<div class="form-group">
    <label for="copy">Testo <span class="required">*</span></label>
    <textarea
        id="copy"
        name="copy"
        rows="6"
        required
        class="form-control @error('copy') is-invalid @enderror"
    >{{ old('copy', $notice->copy ?? '') }}</textarea>
    @error('copy')<span class="form-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="ordinamento">Ordine</label>
    <input
        id="ordinamento"
        type="number"
        name="ordinamento"
        value="{{ old('ordinamento', $notice->ordinamento ?? 0) }}"
        min="0"
        class="form-control form-control--narrow @error('ordinamento') is-invalid @enderror"
    >
    @error('ordinamento')<span class="form-error">{{ $message }}</span>@enderror
</div>
