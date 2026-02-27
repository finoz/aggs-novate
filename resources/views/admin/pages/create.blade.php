@extends('layouts.admin')

@section('title', 'Nuova pagina')

@section('content')
<div class="admin-page-header">
    <h1>Nuova pagina</h1>
    <a href="{{ route('admin.pages.index') }}" class="btn">← Torna alla lista</a>
</div>

<form method="POST" action="{{ route('admin.pages.store') }}" class="admin-form admin-form--wide">
    @csrf

    @include('admin.pages._form')

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">Salva pagina</button>
        <a href="{{ route('admin.pages.index') }}">Annulla</a>
    </div>
</form>
@endsection
