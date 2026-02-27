@php
    $html  = ($block['content'] ?? null)
        ? (new \Tiptap\Editor)->setContent($block['content'])->getHTML()
        : '';
    $class = trim('block block--text ' . ($block['class'] ?? ''));
@endphp
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    {!! $html !!}
</div>
