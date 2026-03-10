<header class="siteheader">
    <div class="siteheader-inner">
        <button class="siteheader-toggle" aria-controls="siteheader-nav" aria-label="Toggle main navigation">
            <span class="siteheader-toggle-inner"></span>
        </button>
        <h1 class="siteheader-logo" aria-label="{{ config('app.name') }}">
            @if(request()->routeIs('home'))
                <img src="{{ asset('images/main-logo.svg') }}" alt="">
                <span>{{ config('app.name') }}</span>
            @else    
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/main-logo.svg') }}" alt="">
                    <span>{{ config('app.name') }}</span>
                </a>
            @endif
        </h1>
        <nav id="siteheader-nav" class="siteheader-nav" role="navigation" aria-label="Main navigation">
            <ul class="menu">
                @foreach($navPages as $navPage)
                    <li @class(['menu-item', 'active' => $navPage->slug === 'home' ? request()->routeIs('home') : request()->is($navPage->slug)])>
                        @if($navPage->slug === 'home')
                            <a href="{{ route('home') }}">{{ $navPage->titolo }}</a>
                        @else
                            <a href="{{ route('page.show', $navPage->slug) }}">{{ $navPage->titolo }}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</header>