@php
    $notices = \App\Models\Notice::orderBy('ordinamento')->orderBy('id', 'desc')->get();
    $class   = trim('block block--notice-list ' . ($block['class'] ?? ''));
@endphp
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    @forelse($notices as $notice)
        <div class="notice-item">
            <div class="notice-item__meta">
                @if($notice->date)
                    <span class="notice-item__date">{{ $notice->date }}</span>
                @endif
                @if($notice->tag)
                    <span class="notice-item__tag">{{ $notice->tag }}</span>
                @endif
            </div>
            <h3 class="notice-item__heading">{{ $notice->heading }}</h3>
            @if($notice->copy)
                <div class="notice-item__copy">{!! nl2br(e($notice->copy)) !!}</div>
            @endif
        </div>
    @empty
        <p class="notice-item--empty">Nessun avviso al momento.</p>
    @endforelse
</div>
