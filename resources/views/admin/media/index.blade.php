<x-admin-layout>
    <x-slot name="title">{{ __('Media Library') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Media Library') }}</h1>
    </x-slot>

    @livewire('admin.media-library')
</x-admin-layout>
