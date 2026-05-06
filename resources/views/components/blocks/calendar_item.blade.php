@php
    $class = trim('block block--calendar-item ' . ($block['class'] ?? ''));
@endphp
<article class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    @if($block['date'] ?? '')
        <time class="calendar-item__date">
            {{ $block['date'] }}
        </time>
    @endif
    <div class="calendar-item__body">
        @if($block['text'] ?? '')
            @if($block['link'] ?? '')
                <a class="calendar-item__text" href="{{ e($block['link']) }}" target="_blank" rel="noopener">{!! nl2br(e($block['text'])) !!}</a>
            @else
                <p class="calendar-item__text">{!! nl2br(e($block['text'])) !!}</p>
            @endif
        @endif
        @if($block['detail'] ?? '')
            <div class="calendar-item__detail">{!! $block['detail'] !!}</div>
        @endif
    </div>
</article>
