<x-admin-layout>
    <x-slot name="title">{{ __('Downloads') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Downloads') }}</h1>
    </x-slot>

    @livewire('admin.download-manager')
</x-admin-layout>
