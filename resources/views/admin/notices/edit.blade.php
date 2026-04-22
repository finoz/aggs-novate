@extends('layouts.admin')

@section('title', 'Modifica avviso')

@section('content')
<div class="admin-page-header">
    <h1>Modifica: {{ $notice->heading }}</h1>
    <a href="{{ route('admin.notices.index') }}" class="btn">← Lista</a>
</div>

<form method="POST" action="{{ route('admin.notices.update', $notice) }}" class="admin-form">
    @csrf
    @method('PUT')

    @include('admin.notices._form')

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">Aggiorna avviso</button>
        <a href="{{ route('admin.notices.index') }}">Annulla</a>
    </div>
</form>
@endsection
