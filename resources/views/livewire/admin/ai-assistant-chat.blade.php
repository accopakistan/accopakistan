<div class="ai-chat-wrapper">
    <div class="ai-chat-intro mb-3">
        <div class="ai-chat-intro-icon"><i class="bi bi-stars"></i></div>
        <p class="text-muted small mb-0">
            {{ __('Ask the assistant to update site content — settings, blog posts, services, projects, FAQs, and testimonials. It cannot delete anything; deletions must be done manually in the admin panel.') }}
        </p>
    </div>

    @if ($error)
        <div class="alert alert-danger py-2 small d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <div>{{ $error }}</div>
        </div>
    @endif

    <div class="ai-chat-card card border-0 shadow-sm">
        <div class="ai-chat-card-header">
            <span class="d-flex align-items-center gap-2 fw-semibold small">
                <i class="bi bi-stars text-warning"></i> {{ __('ACCO AI') }}
                <span class="text-muted fw-normal">&middot; {{ __('Content Assistant') }}</span>
            </span>
            <span class="badge text-bg-light border small fw-normal">{{ __('Beta') }}</span>
        </div>

        <div id="ai-chat-scroll" class="ai-chat-messages">
            @forelse ($history as $turn)
                <div class="ai-msg-row {{ $turn['role'] === 'user' ? 'ai-msg-row--user' : '' }}">
                    <div class="ai-avatar {{ $turn['role'] === 'user' ? 'ai-avatar--user' : 'ai-avatar--assistant' }}">
                        <i class="bi {{ $turn['role'] === 'user' ? 'bi-person-fill' : 'bi-stars' }}"></i>
                    </div>
                    <div class="ai-bubble-col">
                        <div class="ai-bubble {{ $turn['role'] === 'user' ? 'ai-bubble--user' : 'ai-bubble--assistant' }}">
                            @if ($turn['text'] !== '')
                                {{ $turn['text'] }}
                            @endif
                            @if ($turn['tool_note'] !== '')
                                <div class="ai-bubble-tool-note">
                                    <i class="bi bi-gear-fill"></i> {{ $turn['tool_note'] }}
                                </div>
                            @endif
                        </div>
                        @if ($turn['created_at'])
                            <div class="ai-bubble-time">{{ $turn['created_at']->format('g:i A') }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="ai-empty-state">
                    <div class="ai-empty-icon"><i class="bi bi-stars"></i></div>
                    <h2 class="h6 fw-semibold mb-1">{{ __('How can I help with your content today?') }}</h2>
                    <p class="text-muted small mb-3">{{ __('Try one of these, or type your own request below.') }}</p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary ai-suggestion-chip" wire:click="useSuggestion('What services do we currently offer?')">
                            {{ __('What services do we offer?') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary ai-suggestion-chip" wire:click="useSuggestion('Add a new FAQ about project warranties.')">
                            {{ __('Add an FAQ about warranties') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary ai-suggestion-chip" wire:click="useSuggestion('Update the homepage hero heading to something more compelling.')">
                            {{ __('Update the homepage hero heading') }}
                        </button>
                    </div>
                </div>
            @endforelse

            <div wire:loading wire:target="send" class="ai-msg-row">
                <div class="ai-avatar ai-avatar--assistant"><i class="bi bi-stars"></i></div>
                <div class="ai-bubble-col">
                    <div class="ai-bubble ai-bubble--assistant">
                        <span class="ai-typing-dots text-muted"><span></span><span></span><span></span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="ai-chat-input-bar card-footer">
            <form wire:submit="send" class="d-flex gap-2 align-items-start">
                <textarea
                    wire:model="message"
                    rows="2"
                    class="form-control @error('message') is-invalid @enderror"
                    placeholder="{{ __('Type a request…') }}"
                    wire:loading.attr="disabled"
                    wire:target="send"
                    x-data
                    @keydown.enter.prevent="if (!$event.shiftKey) { $wire.send(); }"
                ></textarea>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="send">
                    <i class="bi bi-send"></i> {{ __('Send') }}
                </button>
            </form>
            @error('message') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

@script
<script>
    const scrollToBottom = () => {
        let el = document.getElementById('ai-chat-scroll');
        if (el) {
            el.scrollTop = el.scrollHeight;
        }
    };

    scrollToBottom();
    $wire.on('ai-assistant-message-sent', () => setTimeout(scrollToBottom, 50));
</script>
@endscript
