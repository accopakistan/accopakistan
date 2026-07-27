<x-admin-layout>
    <x-slot name="title">{{ __('Edit Page') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Edit Page') }}: {{ $page->title }}</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data" class="mb-4">
        @csrf
        @method('put')
        @include('admin.pages._form')
    </form>

    @livewire('admin.page-block-editor', ['page' => $page])
</x-admin-layout>
