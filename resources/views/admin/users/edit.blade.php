@extends('layouts.admin')

@section('title', 'Modifica: ' . $user->name)

@section('content')
<div class="admin-page-header">
    <h1>Modifica: {{ $user->name }}</h1>
    <a href="{{ route('admin.users.index') }}" class="btn">← Lista</a>
</div>

<form method="POST" action="{{ route('admin.users.update', $user) }}" class="admin-form">
    @csrf
    @method('PUT')

    @include('admin.users._form')

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">Aggiorna utente</button>
        <a href="{{ route('admin.users.index') }}">Annulla</a>
    </div>
</form>
@endsection
