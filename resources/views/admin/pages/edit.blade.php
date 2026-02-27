@extends('layouts.admin')

@section('title', 'Modifica: ' . $page->titolo)

@section('content')
<div class="admin-page-header">
    <h1>Modifica: {{ $page->titolo }}</h1>
    <div style="display:flex;gap:.75rem;">
        <a href="{{ $page->slug === 'home' ? route('home') : route('page.show', $page->slug) }}"
           target="_blank" class="btn">Visualizza →</a>
        <a href="{{ route('admin.pages.index') }}" class="btn">← Lista</a>
    </div>
</div>

<form method="POST" action="{{ route('admin.pages.update', $page) }}" class="admin-form admin-form--wide">
    @csrf
    @method('PUT')

    @include('admin.pages._form')

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">Aggiorna pagina</button>
        <a href="{{ route('admin.pages.index') }}">Annulla</a>
    </div>
</form>
@endsection
