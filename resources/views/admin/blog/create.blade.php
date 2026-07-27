<x-admin-layout>
    <x-slot name="title">{{ __('New Post') }}</x-slot>
    <x-slot name="header"><h1 class="h4 fw-semibold mb-0 font-heading">{{ __('New Post') }}</h1></x-slot>

    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.blog._form')
    </form>
</x-admin-layout>
