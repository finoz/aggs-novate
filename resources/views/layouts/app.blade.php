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
            @foreach($navPages as $navPage)
                @if($navPage->slug === 'home')
                    <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>
                        {{ $navPage->titolo }}
                    </a>
                @else
                    <a href="{{ route('page.show', $navPage->slug) }}"
                       @class(['active' => request()->is($navPage->slug)])>
                        {{ $navPage->titolo }}
                    </a>
                @endif
            @endforeach
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
