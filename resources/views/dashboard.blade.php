<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-semibold mb-0 font-heading">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{ __("You're logged in!") }}
        </div>
    </div>
</x-app-layout>
