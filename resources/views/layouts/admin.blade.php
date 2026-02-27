<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    @vite(['resources/scss/app.scss', 'resources/ts/app.ts'])
</head>
<body class="admin-body">

<header class="admin-header">
    <div class="container">
        <a href="{{ route('admin.avvisi.index') }}" class="admin-logo">
            {{ config('app.name') }} — Admin
        </a>
        <nav class="admin-nav">
            <a href="{{ route('admin.avvisi.index') }}" @class(['active' => request()->routeIs('admin.avvisi.*')])>
                Avvisi
            </a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}" class="admin-logout">
            @csrf
            <button type="submit">Esci</button>
        </form>
    </div>
</header>

<main class="admin-main">
    <div class="container">

        @if (session('success'))
            <div class="alert alert--success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert--error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </div>
</main>

</body>
</html>
