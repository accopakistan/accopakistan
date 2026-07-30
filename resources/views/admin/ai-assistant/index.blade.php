<x-admin-layout>
    <x-slot name="title">{{ __('AI Assistant') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('AI Assistant') }}</h1>
    </x-slot>

    @livewire('admin.ai-assistant-chat')
</x-admin-layout>
