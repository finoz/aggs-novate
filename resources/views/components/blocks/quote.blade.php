@php
    $class = trim('block block--quote ' . ($block['class'] ?? ''));
@endphp
@if($block['content'] ?? '')
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    <blockquote class="quote__body">{{ $block['content'] }}</blockquote>
    @if(($block['author'] ?? '') || ($block['source'] ?? ''))
        <footer class="quote__footer">
            @if($block['author'] ?? '')
                <cite class="quote__author">{{ $block['author'] }}</cite>
            @endif
            @if($block['source'] ?? '')
                <span class="quote__source">{{ $block['source'] }}</span>
            @endif
        </footer>
    @endif
</div>
@endif
