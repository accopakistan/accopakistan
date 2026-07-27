<x-admin-layout>
    <x-slot name="title">{{ __('Team') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Team') }}</h1>
    </x-slot>

    @livewire('admin.team-member-manager')
</x-admin-layout>
