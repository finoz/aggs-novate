@extends('layouts.admin')

@section('title', 'Nuovo avviso')

@section('content')
<div class="admin-page-header">
    <h1>Nuovo avviso</h1>
    <a href="{{ route('admin.avvisi.index') }}" class="btn">← Torna alla lista</a>
</div>

<form method="POST" action="{{ route('admin.avvisi.store') }}" class="admin-form">
    @csrf

    @include('admin.avvisi._form')

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">Salva avviso</button>
        <a href="{{ route('admin.avvisi.index') }}">Annulla</a>
    </div>
</form>
@endsection
