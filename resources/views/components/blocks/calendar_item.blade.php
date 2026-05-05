@php
    $class = trim('block block--calendar-item ' . ($block['class'] ?? ''));
@endphp
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    @if($block['date'] ?? '')
        <div class="calendar-item__date">
            @if($block['link'] ?? '')
                <a href="{{ e($block['link']) }}" target="_blank" rel="noopener">{{ $block['date'] }}</a>
            @else
                {{ $block['date'] }}
            @endif
        </div>
    @endif
    <div class="calendar-item__body">
        @if($block['text'] ?? '')
            <div class="calendar-item__text">{!! nl2br(e($block['text'])) !!}</div>
        @endif
        @if($block['detail'] ?? '')
            <div class="calendar-item__detail">{!! $block['detail'] !!}</div>
        @endif
    </div>
</div>
