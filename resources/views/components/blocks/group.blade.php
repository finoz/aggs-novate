@php
    $style  = in_array($block['style'] ?? '', ['default', 'card', 'accent', 'dark']) ? $block['style'] : 'default';
    $layout = in_array($block['layout'] ?? '', ['stack', 'grid-2', 'grid-3', 'side']) ? $block['layout'] : 'stack';
    $class  = trim('block block--group block--group-' . $style . ' block--group-' . $layout . ' ' . ($block['class'] ?? ''));
@endphp
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    @foreach($block['blocks'] ?? [] as $sub)
        @includeIf('components.blocks.' . ($sub['type'] ?? ''), ['block' => $sub])
    @endforeach
</div>
