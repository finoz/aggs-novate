@php
    $label   = $block['label'] ?? '';
    $href    = $block['href'] ?? '';
    $target  = in_array($block['target'] ?? '', ['_self', '_blank']) ? $block['target'] : '_self';
    $variant = in_array($block['variant'] ?? '', ['primary', 'secondary', 'outline']) ? $block['variant'] : 'primary';
    $class   = trim('block block--button ' . ($block['class'] ?? ''));
    $rel     = $target === '_blank' ? ' rel="noopener noreferrer"' : '';
@endphp
@if($label && $href)
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    <a href="{{ $href }}" class="btn btn--{{ $variant }}" target="{{ $target }}"{!! $rel !!}>{{ $label }}</a>
</div>
@endif
