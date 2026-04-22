@php
    $raw   = $block['content'] ?? null;
    $html  = '';
    if (is_string($raw) && $raw !== '') {
        $html = \Illuminate\Support\Str::markdown($raw);
    } elseif ($raw) {
        // Legacy TipTap JSON — fallback
        $html = (new \Tiptap\Editor)->setContent($raw)->getHTML();
        $html = preg_replace('~<li><p>(.*?)</p></li>~s', '<li>$1</li>', $html);
    }
    $class = trim('block block--text ' . ($block['class'] ?? ''));
@endphp
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    {!! $html !!}
</div>
