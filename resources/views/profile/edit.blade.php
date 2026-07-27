<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-semibold mb-0 font-heading">{{ __('Profile') }}</h2>
    </x-slot>

    <div class="d-flex flex-column gap-4" style="max-width: 640px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
