@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1'])

@php
    $alignClass = $align === 'left' ? 'dropdown-menu-start' : 'dropdown-menu-end';
@endphp

<div class="dropdown">
    {{ $trigger }}

    <div class="dropdown-menu {{ $alignClass }} {{ $contentClasses }}">
        {{ $content }}
    </div>
</div>
