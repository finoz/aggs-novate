@extends('layouts.admin')

@section('title', 'Avvisi')

@section('content')
<div class="admin-page-header">
    <h1>Avvisi</h1>
    <a href="{{ route('admin.notices.create') }}" class="btn btn--primary">+ Nuovo avviso</a>
</div>

@if ($notices->isEmpty())
    <p class="empty-state">Nessun avviso. <a href="{{ route('admin.notices.create') }}">Creane uno.</a></p>
@else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Titolo</th>
                <th>Tag</th>
                <th>Ordine</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notices as $notice)
            <tr>
                <td>{{ $notice->date }}</td>
                <td>{{ $notice->heading }}</td>
                <td>{{ $notice->tag ?? '—' }}</td>
                <td>{{ $notice->ordinamento }}</td>
                <td class="table-actions">
                    <a href="{{ route('admin.notices.edit', $notice) }}">Modifica</a>
                    <form method="POST" action="{{ route('admin.notices.destroy', $notice) }}"
                          data-confirm="Eliminare l'avviso &quot;{{ $notice->heading }}&quot;?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-link btn-link--danger">Elimina</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
