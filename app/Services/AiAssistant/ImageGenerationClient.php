<?php

namespace App\Services\AiAssistant;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class ImageGenerationClient
{
    /**
     * Generate an image from a text prompt via Pollinations.ai (free, no API
     * key or billing required). Returns raw binary image bytes and a mime type.
     *
     * @return array{data: string, mime_type: string}
     */
    public function generate(string $prompt): array
    {
        $url = config('ai-assistant.image_api_url').'/'.rawurlencode($prompt);

        $response = Http::timeout(90)->get($url, [
            'width' => config('ai-assistant.image_width'),
            'height' => config('ai-assistant.image_height'),
            'nologo' => 'true',
        ]);

        if ($response->failed()) {
            throw new RuntimeException("Image generation error ({$response->status()}): {$response->body()}");
        }

        $mimeType = $response->header('Content-Type') ?: 'image/jpeg';

        if (! str_starts_with($mimeType, 'image/')) {
            throw new RuntimeException("Image generation did not return an image (got {$mimeType}).");
        }

        return [
            'data' => $response->body(),
            'mime_type' => $mimeType,
        ];
    }
}
