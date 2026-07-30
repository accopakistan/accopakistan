<?php

namespace App\Services\AiAssistant;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiClient
{
    /**
     * Send a chat completion request to Gemini's OpenAI-compatible endpoint.
     *
     * @param  array<int, array>  $messages
     * @param  array<int, array{type: string, function: array}>  $tools
     */
    public function send(array $messages, array $tools = [], ?string $system = null): array
    {
        $apiKey = config('ai-assistant.api_key');

        if (! $apiKey) {
            throw new RuntimeException('GEMINI_API_KEY is not configured. Add it to your .env file.');
        }

        if ($system) {
            array_unshift($messages, ['role' => 'system', 'content' => $system]);
        }

        $payload = [
            'model' => config('ai-assistant.model'),
            'messages' => $messages,
            'max_tokens' => config('ai-assistant.max_tokens'),
        ];

        if (! empty($tools)) {
            $payload['tools'] = $tools;
            $payload['tool_choice'] = 'auto';
        }

        $maxRetries = 2;

        for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
            $response = Http::withToken($apiKey)
                ->timeout(120)
                ->post(config('ai-assistant.api_url'), $payload);

            if ($response->successful()) {
                return $response->json();
            }

            $message = $response->json('error.message') ?? $response->body();

            // If the rate limit is hit and the server tells us a short wait,
            // wait it out and retry silently instead of surfacing a transient
            // limit as a hard failure.
            if ($response->status() === 429 && $attempt < $maxRetries) {
                $wait = $this->parseRetryDelay($message);

                if ($wait !== null && $wait <= 15) {
                    sleep((int) ceil($wait) + 1);

                    continue;
                }
            }

            throw new RuntimeException("Gemini API error ({$response->status()}): {$message}");
        }

        throw new RuntimeException('Gemini API error: exhausted retries.');
    }

    protected function parseRetryDelay(string $message): ?float
    {
        if (preg_match('/try again in ([\d.]+)s/i', $message, $matches)) {
            return (float) $matches[1];
        }

        if (preg_match('/"retryDelay"\s*:\s*"([\d.]+)s"/i', $message, $matches)) {
            return (float) $matches[1];
        }

        return null;
    }
}
