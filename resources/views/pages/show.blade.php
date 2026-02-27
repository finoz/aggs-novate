@extends('layouts.app')

@section('title', $page->titolo)

@section('content')
<article class="page-content">
    <div class="page-body">
        @php $blocks = $page->contenuto['blocks'] ?? []; @endphp
        @forelse($blocks as $block)
            @includeIf('components.blocks.' . ($block['type'] ?? ''), ['block' => $block])
        @empty
            <p class="page-empty">Contenuto non ancora disponibile.</p>
        @endforelse
    </div>
</article>
@endsection
