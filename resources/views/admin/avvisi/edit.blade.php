@extends('layouts.admin')

@section('title', 'Modifica avviso')

@section('content')
<div class="admin-page-header">
    <h1>Modifica avviso</h1>
    <a href="{{ route('admin.avvisi.index') }}" class="btn">← Torna alla lista</a>
</div>

<form method="POST" action="{{ route('admin.avvisi.update', $avviso) }}" class="admin-form">
    @csrf
    @method('PUT')

    @include('admin.avvisi._form')

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">Aggiorna avviso</button>
        <a href="{{ route('admin.avvisi.index') }}">Annulla</a>
    </div>
</form>
@endsection
