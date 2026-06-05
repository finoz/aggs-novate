@php
    $style = in_array($block['style'] ?? '', ['default', 'accent', 'dark']) ? $block['style'] : 'default';
    $layout = in_array($block['layout'] ?? '', ['default', 'wide']) ? $block['layout'] : 'default';
    $gap = in_array($block['gap'] ?? '', ['default', 'small', 'large', 'none']) ? $block['gap'] : 'default';
    $class = trim('block block--columns block--columns-' . $style . ' ' . ($layout === 'wide' ? 'block--wide' : '') . '  block--columns-gap-' . $gap . ' ' . ($block['class'] ?? ''));
@endphp
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    @foreach($block['columns'] ?? [] as $col)
        <div class="block-column">
            @foreach($col['blocks'] ?? [] as $sub)
                @includeIf('components.blocks.' . ($sub['type'] ?? ''), ['block' => $sub])
            @endforeach
        </div>
    @endforeach
</div>
