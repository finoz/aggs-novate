@extends('layouts.admin')

@section('title', 'Pagine')

@section('content')
<div class="admin-page-header">
    <h1>Pagine</h1>
    <a href="{{ route('admin.pages.create') }}" class="btn btn--primary">+ Nuova pagina</a>
</div>

@if (session('success'))
    <p class="alert alert--success">{{ session('success') }}</p>
@endif

@if ($pages->isEmpty())
    <p class="empty-state">Nessuna pagina. <a href="{{ route('admin.pages.create') }}">Creane una.</a></p>
@else
    <form method="POST" action="{{ route('admin.pages.reorder') }}">
        @csrf
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Titolo</th>
                    <th>Slug</th>
                    <th>Ordine</th>
                    <th>Pubblicata</th>
                    <th>In menu</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                <tr>
                    <td>{{ $page->titolo }}</td>
                    <td><code>/{{ $page->slug === 'home' ? '' : $page->slug }}</code></td>
                    <td>
                        <input
                            type="number"
                            name="order[{{ $page->id }}]"
                            value="{{ $page->ordinamento }}"
                            min="0"
                            class="form-control form-control--narrow"
                            aria-label="Ordine per {{ $page->titolo }}"
                        >
                    </td>
                    <td>
                        <span class="badge {{ $page->pubblicata ? 'badge--yes' : 'badge--no' }}">
                            {{ $page->pubblicata ? 'Sì' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $page->in_menu ? 'badge--yes' : 'badge--no' }}">
                            {{ $page->in_menu ? 'Sì' : 'No' }}
                        </span>
                    </td>
                    <td class="table-actions">
                        <a href="{{ $page->slug === 'home' ? route('home') : route('page.show', $page->slug) }}" target="_blank">Visualizza</a>
                        <a href="{{ route('admin.pages.edit', $page) }}">Modifica</a>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}"
                              data-confirm="Eliminare la pagina &quot;{{ $page->titolo }}&quot;?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-link btn-link--danger">Elimina</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="form-actions">
            <button type="submit" class="btn">Salva ordine</button>
        </div>
    </form>
@endif
@endsection
