{{-- Partial condiviso da create e edit --}}

<div class="form-group">
    <label for="titolo">Titolo <span class="required">*</span></label>
    <input
        id="titolo"
        type="text"
        name="titolo"
        value="{{ old('titolo', $avviso->titolo ?? '') }}"
        required
        maxlength="255"
        class="form-control @error('titolo') is-invalid @enderror"
    >
    @error('titolo')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="data_pubblicazione">Data pubblicazione <span class="required">*</span></label>
    <input
        id="data_pubblicazione"
        type="date"
        name="data_pubblicazione"
        value="{{ old('data_pubblicazione', isset($avviso) ? $avviso->data_pubblicazione->toDateString() : now()->toDateString()) }}"
        required
        class="form-control @error('data_pubblicazione') is-invalid @enderror"
    >
    @error('data_pubblicazione')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="contenuto">Contenuto <span class="required">*</span></label>
    <textarea
        id="contenuto"
        name="contenuto"
        rows="10"
        required
        class="form-control @error('contenuto') is-invalid @enderror"
    >{{ old('contenuto', $avviso->contenuto ?? '') }}</textarea>
    @error('contenuto')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group form-group--checkbox">
    <label>
        <input
            type="checkbox"
            name="pubblicato"
            value="1"
            {{ old('pubblicato', $avviso->pubblicato ?? false) ? 'checked' : '' }}
        >
        Pubblicato (visibile sul sito)
    </label>
</div>
