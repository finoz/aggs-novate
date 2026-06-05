@php
    $notices = \App\Models\Notice::orderBy('ordinamento')->orderBy('id', 'desc')->get();
    $class   = trim('block block--notice-list ' . ($block['class'] ?? ''));
@endphp
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    @if(!empty($block['title']))
        <h2 class="block--notice-list__title">{!! $block['title'] !!}</h2>
    @endif
    @forelse($notices as $notice)
        <div class="notice-item">
            <h3 class="notice-item__heading">{{ $notice->heading }}</h3>
            @if($notice->subheading)
                <p class="notice-item__subheading">{{ $notice->subheading }}</p>
            @endif
            @if($notice->date)
            <time class="notice-item__date">{{ $notice->date }}</time>
            @endif
            @if($notice->copy)
                <div class="notice-item__copy">{!! \Illuminate\Support\Str::markdownLinks($notice->copy) !!}</div>
            @endif
        </div>
    @empty
        <p class="notice-item--empty">Nessun avviso al momento.</p>
    @endforelse
</div>
