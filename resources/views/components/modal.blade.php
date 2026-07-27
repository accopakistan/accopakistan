@props(['name', 'show' => false, 'maxWidth' => '2xl'])

@php
    $maxWidthClass = match ($maxWidth) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        default => '',
    };
@endphp

<div
    class="modal fade @if ($show) show @endif"
    id="{{ $name }}"
    tabindex="-1"
    @if ($show) style="display: block;" @endif
    aria-hidden="{{ $show ? 'false' : 'true' }}"
>
    <div class="modal-dialog modal-dialog-centered {{ $maxWidthClass }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>

@if ($show)
    <div class="modal-backdrop fade show"></div>
@endif
