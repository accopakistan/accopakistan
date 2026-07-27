<x-admin-layout>
    <x-slot name="title">{{ __('Redirects') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Redirect Manager') }}</h1>
    </x-slot>

    @livewire('admin.redirect-manager')
</x-admin-layout>
