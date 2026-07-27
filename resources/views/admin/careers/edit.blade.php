<x-admin-layout>
    <x-slot name="title">{{ __('Edit Job Posting') }}</x-slot>
    <x-slot name="header"><h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Edit Job Posting') }}: {{ $jobPosting->title }}</h1></x-slot>

    <form method="POST" action="{{ route('admin.careers.update', $jobPosting) }}">
        @csrf
        @method('put')
        @include('admin.careers._form')
    </form>
</x-admin-layout>
