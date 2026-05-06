{{-- Partial condiviso da create e edit --}}

<div class="form-group">
    <label for="name">Nome <span class="required">*</span></label>
    <input
        id="name"
        type="text"
        name="name"
        value="{{ old('name', $user->name ?? '') }}"
        required
        maxlength="255"
        class="form-control @error('name') is-invalid @enderror"
    >
    @error('name')<span class="form-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="email">Email <span class="required">*</span></label>
    <input
        id="email"
        type="email"
        name="email"
        value="{{ old('email', $user->email ?? '') }}"
        required
        maxlength="255"
        class="form-control @error('email') is-invalid @enderror"
    >
    @error('email')<span class="form-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="password">
        Password <span class="required">*</span>
        @isset($user)
            <small>(lascia vuoto per non cambiarla)</small>
        @endisset
    </label>
    <input
        id="password"
        type="password"
        name="password"
        {{ isset($user) ? '' : 'required' }}
        minlength="8"
        autocomplete="new-password"
        class="form-control @error('password') is-invalid @enderror"
    >
    @error('password')<span class="form-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="password_confirmation">Conferma password <span class="required">*</span></label>
    <input
        id="password_confirmation"
        type="password"
        name="password_confirmation"
        {{ isset($user) ? '' : 'required' }}
        minlength="8"
        autocomplete="new-password"
        class="form-control"
    >
</div>

<div class="form-group">
    <label for="role">Ruolo <span class="required">*</span></label>
    <select
        id="role"
        name="role"
        required
        class="form-control @error('role') is-invalid @enderror"
    >
        <option value="">— Seleziona ruolo —</option>
        @foreach ($roles as $role)
            <option value="{{ $role->name }}"
                {{ old('role', $user->roles->first()?->name ?? '') === $role->name ? 'selected' : '' }}>
                {{ ucfirst($role->name) }}
            </option>
        @endforeach
    </select>
    @error('role')<span class="form-error">{{ $message }}</span>@enderror
</div>
