@extends('layouts.admin')

@section('title', 'Utenti')

@section('content')
<div class="admin-page-header">
    <h1>Utenti</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn--primary">+ Nuovo utente</a>
</div>

@if ($users->isEmpty())
    <p class="empty-state">Nessun utente.</p>
@else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ruolo</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge badge--yes">{{ $user->roles->first()?->name ?? '—' }}</span>
                </td>
                <td class="table-actions">
                    <a href="{{ route('admin.users.edit', $user) }}">Modifica</a>
                    @if ($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          data-confirm="Eliminare l'utente &quot;{{ $user->name }}&quot;?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-link btn-link--danger">Elimina</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
