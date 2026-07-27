<x-admin-layout>
    <x-slot name="title">{{ __('Clients') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Clients') }}</h1>
    </x-slot>

    @livewire('admin.client-manager')
</x-admin-layout>
