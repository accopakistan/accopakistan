<x-admin-layout>
    <x-slot name="title">{{ __('Edit Post') }}</x-slot>
    <x-slot name="header"><h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Edit Post') }}: {{ $post->title }}</h1></x-slot>

    <form method="POST" action="{{ route('admin.blog.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('put')
        @include('admin.blog._form')
    </form>
</x-admin-layout>
