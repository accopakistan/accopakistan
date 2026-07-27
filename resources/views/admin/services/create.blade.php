<x-admin-layout>
    <x-slot name="title">{{ __('New Service') }}</x-slot>
    <x-slot name="header"><h1 class="h4 fw-semibold mb-0 font-heading">{{ __('New Service') }}</h1></x-slot>

    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.services._form')
    </form>
</x-admin-layout>
