<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', config('app.name'))">
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    @vite(['resources/scss/app.scss', 'resources/ts/app.ts'])
</head>
<body>

<header class="site-header">
    <div class="container">
        <a href="{{ route('home') }}" class="site-logo">
            {{ config('app.name') }}
        </a>
        <nav class="site-nav">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a>
            <a href="{{ route('chi-siamo') }}" @class(['active' => request()->routeIs('chi-siamo')])>Chi siamo</a>
            <a href="{{ route('avvisi.index') }}" @class(['active' => request()->routeIs('avvisi.*')])>Avvisi</a>
            <a href="{{ route('contatti') }}" @class(['active' => request()->routeIs('contatti')])>Contatti</a>
        </nav>
    </div>
</header>

<main class="site-main">
    <div class="container">
        @yield('content')
    </div>
</main>

<footer class="site-footer">
    <div class="container">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</footer>

</body>
</html>
