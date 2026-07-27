<x-admin-layout>
    <x-slot name="title">{{ __('Settings') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Settings') }}</h1>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent">
            <ul class="nav nav-tabs card-header-tabs">
                @foreach ($groups as $groupName)
                    <li class="nav-item">
                        <a class="nav-link {{ $group === $groupName ? 'active' : '' }}" href="{{ route('admin.settings.edit', $groupName) }}">
                            {{ ucfirst($groupName) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.settings.update', $group) }}" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="row g-3">
                    @foreach ($fields as $key => $field)
                        <div class="col-md-6">
                            <x-input-label for="{{ $key }}" :value="$field['label']" />

                            @if ($field['type'] === 'text')
                                <textarea id="{{ $key }}" name="{{ $key }}" class="form-control" rows="3">{{ old($key, $values[$key] ?? ($field['default'] ?? '')) }}</textarea>
                            @elseif ($field['type'] === 'image')
                                @if (! empty($values[$key]))
                                    <div class="mb-2">
                                        <img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($values[$key]) }}" alt="{{ $field['label'] }}" style="max-height: 4rem;" class="border rounded p-1 bg-white">
                                        <div class="form-check form-check-inline ms-2">
                                            <input class="form-check-input" type="checkbox" name="{{ $key }}_remove" id="{{ $key }}_remove" value="1">
                                            <label class="form-check-label small" for="{{ $key }}_remove">{{ __('Remove') }}</label>
                                        </div>
                                    </div>
                                @endif
                                <input id="{{ $key }}" name="{{ $key }}" type="file" class="form-control" accept="image/*">
                            @else
                                <x-text-input id="{{ $key }}" name="{{ $key }}" type="text" :value="old($key, $values[$key] ?? ($field['default'] ?? ''))" />
                            @endif

                            <x-input-error :messages="$errors->get($key)" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
