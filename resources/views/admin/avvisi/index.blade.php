@extends('layouts.admin')

@section('title', 'Avvisi')

@section('content')
<div class="admin-page-header">
    <h1>Avvisi</h1>
    <a href="{{ route('admin.avvisi.create') }}" class="btn btn--primary">+ Nuovo avviso</a>
</div>

@if ($avvisi->isEmpty())
    <p class="empty-state">Nessun avviso. <a href="{{ route('admin.avvisi.create') }}">Creane uno.</a></p>
@else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Titolo</th>
                <th>Data</th>
                <th>Pubblicato</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($avvisi as $avviso)
            <tr>
                <td>{{ $avviso->titolo }}</td>
                <td>{{ $avviso->data_pubblicazione->isoFormat('D MMM Y') }}</td>
                <td>
                    <span class="badge {{ $avviso->pubblicato ? 'badge--yes' : 'badge--no' }}">
                        {{ $avviso->pubblicato ? 'Sì' : 'No' }}
                    </span>
                </td>
                <td class="table-actions">
                    <a href="{{ route('admin.avvisi.edit', $avviso) }}">Modifica</a>
                    <form method="POST" action="{{ route('admin.avvisi.destroy', $avviso) }}"
                          onsubmit="return confirm('Eliminare questo avviso?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-link btn-link--danger">Elimina</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $avvisi->links() }}
@endif
@endsection
