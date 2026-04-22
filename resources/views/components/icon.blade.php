@props(['name', 'class' => ''])

<svg class="icon icon--{{ $name }}{{ $class ? ' ' . $class : '' }}" aria-hidden="true" focusable="false">
    <use href="#icon-{{ $name }}"></use>
</svg>
