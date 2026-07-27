<x-admin-layout>
    <x-slot name="title">{{ $menu->name }}</x-slot>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Menu') }}: {{ $menu->name }}</h1>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Back to Menus') }}
            </a>
        </div>
    </x-slot>

    @livewire('admin.menu-item-manager', ['menu' => $menu])
</x-admin-layout>
