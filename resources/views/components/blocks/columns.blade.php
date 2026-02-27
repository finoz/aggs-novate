@php $class = trim('block block--columns ' . ($block['class'] ?? '')); @endphp
<div class="{{ $class }}"@if($block['htmlId'] ?? '') id="{{ e($block['htmlId']) }}"@endif>
    @foreach($block['columns'] ?? [] as $col)
        <div class="block-column">
            @foreach($col['blocks'] ?? [] as $sub)
                @includeIf('components.blocks.' . ($sub['type'] ?? ''), ['block' => $sub])
            @endforeach
        </div>
    @endforeach
</div>
