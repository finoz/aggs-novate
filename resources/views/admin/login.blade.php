<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — {{ config('app.name') }}</title>
    @vite(['resources/scss/app.scss', 'resources/ts/app.ts'])
</head>
<body class="admin-body admin-body--login">

<div class="login-card">
    <h1>{{ config('app.name') }}</h1>
    <h2>Area riservata</h2>

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                class="form-control @error('email') is-invalid @enderror"
            >
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="form-control"
            >
        </div>

        <button type="submit" class="btn btn--primary">Accedi</button>
    </form>
</div>

</body>
</html>
