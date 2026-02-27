@extends('layouts.app')

@section('title', 'Avvisi')

@section('content')
<h1>Avvisi</h1>

@if ($avvisi->isEmpty())
    <p class="empty-state">Nessun avviso pubblicato.</p>
@else
    <ul class="avvisi-list">
        @foreach ($avvisi as $avviso)
        <li class="avviso-card">
            <time datetime="{{ $avviso->data_pubblicazione->toDateString() }}">
                {{ $avviso->data_pubblicazione->isoFormat('D MMMM Y') }}
            </time>
            <h2>
                <a href="{{ route('avvisi.show', $avviso) }}">{{ $avviso->titolo }}</a>
            </h2>
            <p>{{ Str::limit(strip_tags($avviso->contenuto), 200) }}</p>
            <a href="{{ route('avvisi.show', $avviso) }}">Leggi →</a>
        </li>
        @endforeach
    </ul>

    {{ $avvisi->links() }}
@endif
@endsection
