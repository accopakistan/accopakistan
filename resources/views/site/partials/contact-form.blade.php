@if (session('status'))
    <div class="form-note form-note--success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('contact.store') }}">
    @csrf
    <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off">

    <div class="grid">
        <div class="col-6 field">
            <label for="contact_name">{{ __('Name') }}</label>
            <input id="contact_name" type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-6 field">
            <label for="contact_email">{{ __('Email') }}</label>
            <input id="contact_email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-6 field">
            <label for="contact_phone">{{ __('Phone') }}</label>
            <input id="contact_phone" type="text" name="phone" value="{{ old('phone') }}">
            @error('phone') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-6 field">
            <label for="contact_subject">{{ __('Subject') }}</label>
            <input id="contact_subject" type="text" name="subject" value="{{ old('subject') }}">
            @error('subject') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 field">
            <label for="contact_message">{{ __('Message') }}</label>
            <textarea id="contact_message" name="message" rows="4" required>{{ old('message') }}</textarea>
            @error('message') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn--primary">
                {{ __('Send Message') }} <x-icon name="send" />
            </button>
        </div>
    </div>
</form>
