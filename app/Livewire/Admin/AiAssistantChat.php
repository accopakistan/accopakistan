<?php

namespace App\Livewire\Admin;

use App\Services\AiAssistant\ConversationService;
use Livewire\Component;
use Throwable;

class AiAssistantChat extends Component
{
    public string $message = '';

    public array $history = [];

    public ?string $error = null;

    public function mount(): void
    {
        abort_unless(auth()->user()->can('ai_assistant.use'), 403);

        $this->loadHistory();
    }

    protected function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:4000'],
        ];
    }

    public function useSuggestion(string $text): void
    {
        $this->message = $text;
    }

    public function send(): void
    {
        abort_unless(auth()->user()->can('ai_assistant.use'), 403);

        $this->validate();

        $this->error = null;
        $userMessage = $this->message;
        $this->message = '';

        try {
            app(ConversationService::class)->reply(auth()->user(), $userMessage);
        } catch (Throwable $e) {
            $this->message = $userMessage;
            $this->error = $e->getMessage();
        }

        $this->loadHistory();
        $this->dispatch('ai-assistant-message-sent');
    }

    protected function loadHistory(): void
    {
        $this->history = app(ConversationService::class)->displayHistory(auth()->user());
    }

    public function render()
    {
        return view('livewire.admin.ai-assistant-chat');
    }
}
