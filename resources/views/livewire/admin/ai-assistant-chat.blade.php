<div class="ai-chat-wrapper">
    <div class="ai-chat-card-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="ai-chat-header-icon"><i class="bi bi-stars"></i></div>
            <div>
                <div class="fw-bold fs-6 mb-0">{{ __('ACCO AI') }}</div>
                <div class="text-success d-flex align-items-center gap-1" style="font-size: 0.75rem; font-weight: 500;">
                    <span class="d-inline-block rounded-circle bg-success" style="width: 6px; height: 6px;"></span>
                    {{ __('Online & ready') }}
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light-subtle text-dark-emphasis border px-2.5 py-1 small rounded-pill fw-medium" style="font-size: 0.72rem;">{{ __('Gemini 3.5') }}</span>
        </div>
    </div>

    @if ($error)
        <div class="alert alert-danger py-2.5 px-3 mb-0 rounded-0 small d-flex align-items-start gap-2 flex-shrink-0">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <div>{{ $error }}</div>
        </div>
    @endif

    <div id="ai-chat-scroll" class="ai-chat-scroll">
        <div class="ai-chat-messages">
            @forelse ($history as $turn)
                <div class="ai-msg-row {{ $turn['role'] === 'user' ? 'ai-msg-row--user' : '' }}">
                    <div class="ai-avatar {{ $turn['role'] === 'user' ? 'ai-avatar--user' : 'ai-avatar--assistant' }}">
                        <i class="bi {{ $turn['role'] === 'user' ? 'bi-person-fill' : 'bi-stars' }}"></i>
                    </div>
                    <div class="ai-bubble-col">
                        <div class="ai-bubble {{ $turn['role'] === 'user' ? 'ai-bubble--user' : 'ai-bubble--assistant' }}">
                            @if ($turn['text'] !== '')
                                @if ($turn['role'] === 'assistant')
                                    <div class="ai-bubble-markdown">{!! \Illuminate\Support\Str::markdown($turn['text'], ['html_input' => 'strip']) !!}</div>
                                @else
                                    <div class="ai-bubble-plain">{{ $turn['text'] }}</div>
                                @endif
                            @endif
                            @if (! empty($turn['image_url']))
                                <div class="ai-image-preview-card mt-3">
                                    <a href="{{ $turn['image_url'] }}" target="_blank" rel="noopener" class="d-block position-relative overflow-hidden rounded-3 shadow-sm border border-light-subtle">
                                        <img src="{{ $turn['image_url'] }}" alt="{{ __('Generated image') }}" class="ai-bubble-image w-100 img-fluid">
                                        <div class="ai-image-hover-overlay">
                                            <i class="bi bi-zoom-in"></i> {{ __('View Full HD Image') }}
                                        </div>
                                    </a>
                                </div>
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

            <div wire:loading.flex wire:target="send" class="ai-msg-row" style="display: none;">
                <div class="ai-avatar ai-avatar--assistant ai-avatar--pulse"><i class="bi bi-stars"></i></div>
                <div class="ai-bubble-col">
                    <div class="ai-bubble ai-bubble--assistant ai-bubble--thinking">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="spinner-border spinner-border-sm text-warning" role="status" style="width: 0.85rem; height: 0.85rem; border-width: 1.5px;"></span>
                            <span class="ai-thinking-status fw-semibold text-warning" style="font-size: 0.8rem; letter-spacing: 0.01em;"></span>
                        </div>
                        <div class="ai-skeleton-loader">
                            <div class="ai-skeleton-line"></div>
                            <div class="ai-skeleton-line" style="width: 85%;"></div>
                            <div class="ai-skeleton-line" style="width: 60%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ai-chat-input-bar">
        <div class="ai-chat-input-inner">
            <form wire:submit="send" class="ai-composer" x-data @submit="scrollChatToBottom()">
                <textarea
                    wire:model="message"
                    rows="1"
                    class="ai-composer-input"
                    placeholder="{{ __('Message ACCO AI…') }}"
                    wire:loading.attr="disabled"
                    wire:target="send"
                    x-init="$el.style.height = 'auto'"
                    @input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 192) + 'px'"
                    @keydown.enter.prevent="if (!$event.shiftKey) { $el.closest('form').requestSubmit(); }"
                ></textarea>
                <button type="submit" class="ai-composer-send" wire:loading.attr="disabled" wire:target="send" aria-label="{{ __('Send') }}">
                    <i class="bi bi-arrow-up"></i>
                </button>
            </form>
            <div class="ai-composer-hint">{{ __('AI-generated content can be wrong — review before publishing. It cannot delete anything.') }}</div>
            @error('message') <div class="text-danger small mt-1 text-center">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

@script
<script>
    // Instant, direct scrollTop assignment — deliberately not scrollTo({behavior:
    // 'smooth'}) or scrollIntoView(), which can silently no-op if a scroll is
    // already in flight or the element hasn't painted yet. Runs across a couple
    // of animation frames so it still lands correctly after Livewire morphs the
    // message list in (new, taller content) a moment after the DOM patch.
    window.scrollChatToBottom = () => {
        const scroll = () => {
            let el = document.getElementById('ai-chat-scroll');
            if (el) el.scrollTop = el.scrollHeight;
        };

        scroll();
        requestAnimationFrame(() => requestAnimationFrame(scroll));
    };

    // Initial load: jump straight to the bottom of the existing conversation.
    scrollChatToBottom();

    // After a reply lands (success or error), scroll again and reset the
    // composer's auto-grown height back down.
    $wire.on('ai-assistant-message-sent', () => {
        let input = document.querySelector('.ai-composer-input');
        if (input) input.style.height = 'auto';

        scrollChatToBottom();
        setTimeout(scrollChatToBottom, 150);
    });
</script>
@endscript
