<?php

namespace App\Services\AiAssistant;

use App\Models\AiAssistantMessage;
use App\Models\User;
use RuntimeException;

class ConversationService
{
    public function __construct(
        protected GeminiClient $client,
    ) {
    }

    /**
     * Send a new user message, run the tool-use loop to completion, persist every
     * turn, and return the assistant's final text reply.
     */
    public function reply(User $user, string $userMessage): string
    {
        @set_time_limit(300);

        AiAssistantMessage::create([
            'user_id' => $user->id,
            'role' => 'user',
            'content' => ['content' => $userMessage],
        ]);

        $tools = ToolRegistry::schemas();
        $maxIterations = config('ai-assistant.max_tool_iterations', 8);

        for ($i = 0; $i < $maxIterations; $i++) {
            $response = $this->client->send($this->historyFor($user), $tools, $this->systemPrompt());

            $choice = $response['choices'][0] ?? null;

            if (! $choice) {
                throw new RuntimeException('Unexpected response from the Groq API.');
            }

            $message = $choice['message'];
            $toolCalls = $message['tool_calls'] ?? null;

            AiAssistantMessage::create([
                'user_id' => $user->id,
                'role' => 'assistant',
                'content' => ['content' => $message['content'] ?? null, 'tool_calls' => $toolCalls],
            ]);

            if (empty($toolCalls)) {
                return $message['content'] ?? '(No text response.)';
            }

            foreach ($toolCalls as $call) {
                $name = $call['function']['name'];
                $arguments = json_decode($call['function']['arguments'] ?? '{}', true) ?: [];
                $result = ToolRegistry::execute($name, $arguments);

                AiAssistantMessage::create([
                    'user_id' => $user->id,
                    'role' => 'tool',
                    'content' => [
                        'tool_call_id' => $call['id'],
                        'name' => $name,
                        'content' => json_encode($result),
                    ],
                ]);
            }
        }

        return 'I was unable to finish this request within the allowed number of steps. Please try rephrasing it or breaking it into smaller requests.';
    }

    /**
     * Simplified turns for the chat UI: only user/assistant text, tool activity
     * collapsed into a short note (with an inline image preview for
     * generate_blog_post_image calls) so the transcript stays readable.
     */
    public function displayHistory(User $user): array
    {
        $rows = AiAssistantMessage::where('user_id', $user->id)->orderBy('id')->get();

        // tool_call_id => decoded tool result, so assistant turns can look up
        // what a tool call actually returned (e.g. a generated image URL).
        $toolResults = $rows->where('role', 'tool')->mapWithKeys(function (AiAssistantMessage $m) {
            return [$m->content['tool_call_id'] => json_decode($m->content['content'] ?? '{}', true) ?: []];
        });

        return $rows
            ->filter(fn (AiAssistantMessage $m) => in_array($m->role, ['user', 'assistant'], true))
            ->map(function (AiAssistantMessage $m) use ($toolResults) {
                $text = $m->content['content'] ?? '';
                $toolCalls = $m->content['tool_calls'] ?? null;
                $toolNote = $this->extractToolNote($toolCalls);
                $imageUrl = $this->extractGeneratedImageUrl($toolCalls, $toolResults);

                if ($text === '' && $toolNote === '') {
                    return null;
                }

                return [
                    'role' => $m->role,
                    'text' => $text ?? '',
                    'tool_note' => $toolNote,
                    'image_url' => $imageUrl,
                    'created_at' => $m->created_at,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    protected function systemPrompt(): string
    {
        $now = now()->toDateTimeString();

        return <<<PROMPT
        You are the AI content assistant embedded in the admin panel of the ACCO Pakistan
        website, a Laravel CMS for an architecture, engineering, and construction firm.
        You help the admin manage site content -- settings, blog posts, services, projects,
        FAQs, and testimonials -- using only the tools available to you.

        Today's current date and time is: {$now} (use this to accurately calculate relative dates/times when scheduling posts).

        Rules:
        - Only act through the provided tools. Never invent ids, slugs, or values --
          call a "list_" or "get_" tool first if you are unsure something exists.
        - You have no delete tool and cannot remove content. If asked to delete
          something, explain that must be done manually in the admin panel, and
          offer to unpublish or deactivate it instead if a status field allows it.
        - Proceed directly on clear, specific requests. Ask a clarifying question
          only when the request is genuinely ambiguous (e.g. which of several
          matching records to edit).
        - Keep replies concise and state exactly what you changed, including ids
          or slugs, so the admin can verify it in the admin panel.
        - When a request includes a meta title, meta description, or keywords,
          set them with the dedicated "_seo" tool (e.g. update_blog_post_seo).
          Never write meta title/description/keywords as literal text inside a
          post's content or excerpt field -- that text would appear on the live
          public page, which is wrong.
        - If asked for a specific word count, write content that actually meets
          it before calling the update/create tool. Do not silently write far
          less than requested.
        - Blog post and service "content" fields are rendered as raw HTML on
          the public site, and the page itself already renders the title as
          the <h1>. Structure body content with <h2>/<h3> for sections, plain
          <p> paragraphs, and <table>/<ul>/<ol> where useful -- but never
          include an <h1> in the content field itself.
        - When asked to add or generate an image for a blog post, service, or
          project, use generate_blog_post_image / generate_service_image /
          generate_project_image with a specific visual prompt describing the
          subject, setting, and style -- not the title verbatim. The prompt
          must stay tightly on-topic with the record: name the actual
          building type, location, and architectural details (e.g. "modern
          hospital exterior with glass facade" not just "healthcare"),
          specify "architectural photography" or "professional construction
          photography" style, and never include people/portraits unless the
          record is specifically about people. A generic or vague prompt
          risks generating an unrelated image. These tools do not apply to
          team member photos or client logos -- those represent real people
          and companies and must stay real uploads, never AI-generated.
        PROMPT;
    }

    protected function historyFor(User $user, int $limit = 60): array
    {
        $messages = AiAssistantMessage::where('user_id', $user->id)
            ->orderBy('id')
            ->get();

        if ($messages->count() > $limit) {
            $messages = $messages->slice(-$limit)->values();

            while ($messages->isNotEmpty() && $messages->first()->role !== 'user') {
                $messages = $messages->slice(1)->values();
            }
        }

        return $messages->map(fn (AiAssistantMessage $m) => $this->toApiMessage($m))->all();
    }

    protected function toApiMessage(AiAssistantMessage $m): array
    {
        $content = $m->content;

        if ($m->role === 'tool') {
            return [
                'role' => 'tool',
                'tool_call_id' => $content['tool_call_id'],
                'content' => $content['content'],
            ];
        }

        $message = ['role' => $m->role, 'content' => $content['content'] ?? null];

        if (! empty($content['tool_calls'])) {
            $message['tool_calls'] = $content['tool_calls'];
        }

        return $message;
    }

    protected function extractToolNote(?array $toolCalls): string
    {
        if (empty($toolCalls)) {
            return '';
        }

        $names = array_filter(array_map(fn (array $call) => $call['function']['name'] ?? null, $toolCalls));

        return empty($names) ? '' : 'Used: '.implode(', ', $names);
    }

    protected function extractGeneratedImageUrl(?array $toolCalls, \Illuminate\Support\Collection $toolResults): ?string
    {
        if (empty($toolCalls)) {
            return null;
        }

        foreach ($toolCalls as $call) {
            $name = $call['function']['name'] ?? null;
            if (! in_array($name, ['generate_blog_post_image', 'generate_service_image', 'generate_project_image', 'generate_setting_image'], true)) {
                continue;
            }

            $result = $toolResults->get($call['id']);
            if (empty($result)) {
                continue;
            }

            if (! empty($result['featured_image_url'])) {
                return $result['featured_image_url'];
            }

            if (! empty($result['value'])) {
                return $result['value'];
            }
        }

        return null;
    }
}
