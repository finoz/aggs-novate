@if($block['src'] ?? '')
@php $class = trim('block block--image ' . ($block['class'] ?? '')); @endphp
<figure class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    <img src="{{ $block['src'] }}" alt="{{ $block['alt'] ?? '' }}">
    @if($block['caption'] ?? '')
        <figcaption>{{ $block['caption'] }}</figcaption>
    @endif
</figure>
@endif
