<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\Faq;

class FaqTools
{
    protected static function summarize(Faq $faq): array
    {
        return [
            'id' => $faq->id,
            'question' => $faq->question,
            'answer' => $faq->answer,
            'category' => $faq->category,
            'order' => $faq->order,
            'is_active' => $faq->is_active,
        ];
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_faqs',
                'description' => 'List all site FAQs, optionally filtered by category.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'category' => ['type' => 'string'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $query = Faq::query()->orderBy('order');

                    if (! empty($input['category'])) {
                        $query->where('category', $input['category']);
                    }

                    return $query->get()->map(fn (Faq $f) => static::summarize($f))->all();
                },
            ],
            [
                'name' => 'create_faq',
                'description' => 'Create a new FAQ entry.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'question' => ['type' => 'string'],
                        'answer' => ['type' => 'string'],
                        'category' => ['type' => 'string'],
                        'is_active' => ['type' => 'boolean'],
                    ],
                    'required' => ['question', 'answer'],
                ],
                'handler' => function (array $input): array {
                    $faq = Faq::create([
                        'question' => $input['question'],
                        'answer' => $input['answer'],
                        'category' => $input['category'] ?? null,
                        'is_active' => (bool) ($input['is_active'] ?? true),
                        'order' => (Faq::max('order') ?? 0) + 1,
                    ]);

                    return static::summarize($faq);
                },
            ],
            [
                'name' => 'update_faq',
                'description' => 'Update fields on an existing FAQ by id. Only pass the fields you want to change.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'question' => ['type' => 'string'],
                        'answer' => ['type' => 'string'],
                        'category' => ['type' => 'string'],
                        'is_active' => ['type' => 'boolean'],
                    ],
                    'required' => ['id'],
                ],
                'handler' => function (array $input): array {
                    $faq = Faq::find($input['id'] ?? null);

                    if (! $faq) {
                        return ['error' => 'FAQ not found.'];
                    }

                    $data = array_intersect_key($input, array_flip(['question', 'answer', 'category', 'is_active']));
                    $faq->update($data);

                    return static::summarize($faq->refresh());
                },
            ],
        ];
    }
}
