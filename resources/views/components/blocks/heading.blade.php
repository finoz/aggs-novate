@php
    $level  = min(max((int) ($block['level'] ?? 2), 1), 5);
    $idAttr = ($block['htmlId'] ?? '') ? ' id="' . e($block['htmlId']) . '"' : '';
    $class  = trim('block block--heading ' . ($block['class'] ?? ''));
@endphp
{!! '<h' . $level . $idAttr . ' class="' . e($class) . '">' . e($block['text'] ?? '') . '</h' . $level . '>' !!}
