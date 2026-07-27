<x-admin-layout>
    <x-slot name="title">{{ __('FAQs') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('FAQs') }}</h1>
    </x-slot>

    @livewire('admin.faq-manager')
</x-admin-layout>
