<x-site-layout :seoable="$jobPosting" :title="$jobPosting->title" :description="\Illuminate\Support\Str::limit(strip_tags($jobPosting->description), 160)">
    <x-page-header
        :title="$jobPosting->title"
        :image="\App\Models\Setting::imageUrl('careers_header_image', 'https://picsum.photos/seed/acco-careers-header/1920/900')"
        :breadcrumbs="[__('Careers') => route('careers.index'), $jobPosting->title => null]"
    >
        <x-slot:subtitle>
            @if ($jobPosting->department) {{ $jobPosting->department }} &middot; @endif
            @if ($jobPosting->location) {{ $jobPosting->location }} &middot; @endif
            {{ ucfirst(str_replace('-', ' ', $jobPosting->type)) }}
        </x-slot:subtitle>
    </x-page-header>

    <section class="section">
        <div class="container project-layout">
            <div class="reveal-up">
                @if ($jobPosting->status !== 'open')
                    <div class="form-note form-note--error">{{ __('This position is no longer accepting applications.') }}</div>
                @endif

                @if ($jobPosting->description)
                    <h3 class="heading-sm">{{ __('Job Description') }}</h3>
                    <div class="prose" style="margin-top:1rem;margin-bottom:2rem;">
                        @foreach (explode("\n", $jobPosting->description) as $paragraph)
                            @if (trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif
                        @endforeach
                    </div>
                @endif

                @if ($jobPosting->requirements)
                    <h3 class="heading-sm">{{ __('Requirements') }}</h3>
                    <div class="prose" style="margin-top:1rem;">
                        @foreach (explode("\n", $jobPosting->requirements) as $paragraph)
                            @if (trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="reveal-up">
                @if ($jobPosting->status === 'open')
                    <h3 class="heading-sm" style="margin-bottom:1.5rem;">{{ __('Apply for this Position') }}</h3>

                    @if (session('status'))
                        <div class="form-note form-note--success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('careers.apply', $jobPosting) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off">

                        <div class="field">
                            <label for="apply_name">{{ __('Full Name') }}</label>
                            <input id="apply_name" type="text" name="name" value="{{ old('name') }}" required>
                            @error('name') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="field">
                            <label for="apply_email">{{ __('Email') }}</label>
                            <input id="apply_email" type="email" name="email" value="{{ old('email') }}" required>
                            @error('email') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="field">
                            <label for="apply_phone">{{ __('Phone') }}</label>
                            <input id="apply_phone" type="text" name="phone" value="{{ old('phone') }}">
                            @error('phone') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="field">
                            <label for="apply_cover">{{ __('Cover Letter') }}</label>
                            <textarea id="apply_cover" name="cover_letter" rows="4">{{ old('cover_letter') }}</textarea>
                            @error('cover_letter') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="field">
                            <label for="apply_resume">{{ __('Resume (PDF or Word)') }}</label>
                            <input id="apply_resume" type="file" name="resume" accept=".pdf,.doc,.docx" required style="border:1px dashed var(--border);padding:0.75rem;">
                            @error('resume') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;">{{ __('Submit Application') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</x-site-layout>
