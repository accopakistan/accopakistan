<x-admin-layout>
    <x-slot name="title">{{ __('Edit Project') }}</x-slot>
    <x-slot name="header"><h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Edit Project') }}: {{ $project->title }}</h1></x-slot>

    <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
        @csrf
        @method('put')
        @include('admin.projects._form')
    </form>
</x-admin-layout>
