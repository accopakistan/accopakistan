<x-admin-layout>
    <x-slot name="title">{{ __('New Job Posting') }}</x-slot>
    <x-slot name="header"><h1 class="h4 fw-semibold mb-0 font-heading">{{ __('New Job Posting') }}</h1></x-slot>

    <form method="POST" action="{{ route('admin.careers.store') }}">
        @csrf
        @include('admin.careers._form')
    </form>
</x-admin-layout>
