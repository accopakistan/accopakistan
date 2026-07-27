<x-admin-layout>
    <x-slot name="title">{{ __('Testimonials') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Testimonials') }}</h1>
    </x-slot>

    @livewire('admin.testimonial-manager')
</x-admin-layout>
