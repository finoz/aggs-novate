@extends('layouts.admin')

@section('title', 'Nuovo avviso')

@section('content')
<div class="admin-page-header">
    <h1>Nuovo avviso</h1>
    <a href="{{ route('admin.notices.index') }}" class="btn">← Torna alla lista</a>
</div>

<form method="POST" action="{{ route('admin.notices.store') }}" class="admin-form">
    @csrf

    @include('admin.notices._form')

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">Salva avviso</button>
        <a href="{{ route('admin.notices.index') }}">Annulla</a>
    </div>
</form>
@endsection
