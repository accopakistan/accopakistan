<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <x-input-label for="title" :value="__('Job Title')" />
                    <x-text-input id="title" name="title" type="text" :value="old('title', $jobPosting->title ?? '')" required autofocus />
                    <x-input-error :messages="$errors->get('title')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="slug" :value="__('Slug')" />
                    <x-text-input id="slug" name="slug" type="text" :value="old('slug', $jobPosting->slug ?? '')" required />
                    <x-input-error :messages="$errors->get('slug')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" class="form-control" rows="6">{{ old('description', $jobPosting->description ?? '') }}</textarea>
                </div>
                <div class="mb-0">
                    <x-input-label for="requirements" :value="__('Requirements')" />
                    <textarea id="requirements" name="requirements" class="form-control" rows="6">{{ old('requirements', $jobPosting->requirements ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <x-input-label for="department" :value="__('Department')" />
                    <x-text-input id="department" name="department" type="text" :value="old('department', $jobPosting->department ?? '')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="location" :value="__('Location')" />
                    <x-text-input id="location" name="location" type="text" :value="old('location', $jobPosting->location ?? '')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="type" :value="__('Employment Type')" />
                    <select id="type" name="type" class="form-select">
                        @foreach (['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Contract', 'internship' => 'Internship'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $jobPosting->type ?? 'full-time') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" class="form-select">
                        <option value="open" @selected(old('status', $jobPosting->status ?? 'open') === 'open')>{{ __('Open') }}</option>
                        <option value="closed" @selected(old('status', $jobPosting->status ?? 'open') === 'closed')>{{ __('Closed') }}</option>
                    </select>
                </div>
                <div class="mb-0">
                    <x-input-label for="closing_date" :value="__('Closing Date')" />
                    <x-text-input id="closing_date" name="closing_date" type="date"
                        :value="old('closing_date', isset($jobPosting->closing_date) ? $jobPosting->closing_date?->format('Y-m-d') : '')" />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <x-primary-button>{{ __('Save Job Posting') }}</x-primary-button>
    <a href="{{ route('admin.careers.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
</div>
