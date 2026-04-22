@php
    $variant = in_array($block['variant'] ?? '', ['info', 'warning', 'success', 'danger'])
        ? $block['variant']
        : 'info';
    $html  = ($block['content'] ?? null)
        ? (new \Tiptap\Editor)->setContent($block['content'])->getHTML()
        : '';
    $html = preg_replace('~<li><p>(.*?)</p></li>~s', '<li>$1</li>', $html);
    $class = trim('block block--callout block--callout-' . $variant . ' ' . ($block['class'] ?? ''));
@endphp
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    {!! $html !!}
</div>
