@extends('layouts.admin')

@section('title', 'Nuovo utente')

@section('content')
<div class="admin-page-header">
    <h1>Nuovo utente</h1>
    <a href="{{ route('admin.users.index') }}" class="btn">← Torna alla lista</a>
</div>

<form method="POST" action="{{ route('admin.users.store') }}" class="admin-form">
    @csrf

    @include('admin.users._form')

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">Crea utente</button>
        <a href="{{ route('admin.users.index') }}">Annulla</a>
    </div>
</form>
@endsection
