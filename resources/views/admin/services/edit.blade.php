<x-admin-layout>
    <x-slot name="title">{{ __('Edit Service') }}</x-slot>
    <x-slot name="header"><h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Edit Service') }}: {{ $service->title }}</h1></x-slot>

    <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">
        @csrf
        @method('put')
        @include('admin.services._form')
    </form>
</x-admin-layout>
