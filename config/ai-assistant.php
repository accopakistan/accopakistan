<?php

return [
    // Get a free key at https://aistudio.google.com/apikey — no billing required.
    'api_key' => env('GEMINI_API_KEY'),

    'model' => env('GEMINI_MODEL', 'gemini-3.5-flash-lite'),

    // Gemini's free tier has much more headroom than Groq's did, so this can
    // comfortably support long-form content requests (e.g. a full blog post).
    'max_tokens' => 8192,

    // Safety cap on how many tool-use round trips a single request can take
    // before the assistant is forced to give a final answer.
    'max_tool_iterations' => 8,

    'api_url' => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',

    // Used by the generate_blog_post_image tool. Gemini's own image model
    // (gemini-3.1-flash-image) has a hard free-tier limit of zero requests —
    // confirmed via a live 429 response — so this uses Pollinations.ai
    // instead: genuinely free, no API key or billing required.
    'image_api_url' => 'https://image.pollinations.ai/prompt',
    'image_width' => 1200,
    'image_height' => 800,
];
