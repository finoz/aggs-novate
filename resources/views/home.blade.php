@extends('layouts.app')

@section('title', 'Home')

@section('content')
<section class="hero">
    <h1>Benvenuto in {{ config('app.name') }}</h1>
    <p class="hero__lead">Sito istituzionale dell'Associazione.</p>
</section>

@if ($avvisi->isNotEmpty())
<section class="home-avvisi">
    <h2>Ultimi avvisi</h2>
    <ul class="avvisi-list">
        @foreach ($avvisi as $avviso)
        <li class="avviso-card">
            <time datetime="{{ $avviso->data_pubblicazione->toDateString() }}">
                {{ $avviso->data_pubblicazione->isoFormat('D MMMM Y') }}
            </time>
            <h3>
                <a href="{{ route('avvisi.show', $avviso) }}">{{ $avviso->titolo }}</a>
            </h3>
            <p>{{ Str::limit(strip_tags($avviso->contenuto), 150) }}</p>
        </li>
        @endforeach
    </ul>
    <a href="{{ route('avvisi.index') }}" class="btn">Tutti gli avvisi</a>
</section>
@endif
@endsection
