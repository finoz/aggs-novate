@php
    $class = trim('block block--calendar-item ' . ($block['class'] ?? ''));
@endphp
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    @if($block['date'] ?? '')
        <div class="calendar-item__date">{{ $block['date'] }}</div>
    @endif
    @if($block['text'] ?? '')
        <div class="calendar-item__text">{!! nl2br(e($block['text'])) !!}</div>
    @endif
</div>
