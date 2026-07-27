<x-admin-layout>
    <x-slot name="title">{{ __('New Page') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('New Page') }}</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.pages._form')
    </form>
</x-admin-layout>
