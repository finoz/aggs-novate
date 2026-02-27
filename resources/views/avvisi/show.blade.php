@extends('layouts.app')

@section('title', $avviso->titolo)

@section('content')
<article class="avviso-detail">
    <header class="avviso-detail__header">
        <a href="{{ route('avvisi.index') }}" class="back-link">← Tutti gli avvisi</a>
        <h1>{{ $avviso->titolo }}</h1>
        <time datetime="{{ $avviso->data_pubblicazione->toDateString() }}">
            {{ $avviso->data_pubblicazione->isoFormat('D MMMM Y') }}
        </time>
    </header>

    <div class="avviso-detail__body">
        {!! nl2br(e($avviso->contenuto)) !!}
    </div>
</article>
@endsection
