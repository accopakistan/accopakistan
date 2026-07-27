<x-admin-layout>
    <x-slot name="title">{{ __('New Project') }}</x-slot>
    <x-slot name="header"><h1 class="h4 fw-semibold mb-0 font-heading">{{ __('New Project') }}</h1></x-slot>

    <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.projects._form')
    </form>
</x-admin-layout>
