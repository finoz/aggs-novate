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

@include('partials.icons-sprite')

@include('partials.header')

<main class="site-main">
    <div class="container">
        @yield('content')
    </div>
</main>

@include('partials.footer')

</body>
</html>
