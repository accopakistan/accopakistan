<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\Testimonial;

class TestimonialTools
{
    protected static function summarize(Testimonial $testimonial): array
    {
        return [
            'id' => $testimonial->id,
            'client_name' => $testimonial->client_name,
            'client_position' => $testimonial->client_position,
            'company' => $testimonial->company,
            'quote' => $testimonial->quote,
            'rating' => $testimonial->rating,
            'project_id' => $testimonial->project_id,
            'is_active' => $testimonial->is_active,
        ];
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_testimonials',
                'description' => 'List all client testimonials.',
                'input_schema' => ['type' => 'object', 'properties' => new \stdClass()],
                'handler' => function (array $input): array {
                    return Testimonial::query()->orderBy('order')->get()
                        ->map(fn (Testimonial $t) => static::summarize($t))->all();
                },
            ],
            [
                'name' => 'create_testimonial',
                'description' => 'Create a new client testimonial.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'client_name' => ['type' => 'string'],
                        'client_position' => ['type' => 'string'],
                        'company' => ['type' => 'string'],
                        'quote' => ['type' => 'string'],
                        'rating' => ['type' => 'integer', 'description' => '1 to 5.'],
                        'project_id' => ['type' => 'integer', 'description' => 'Optional related project id.'],
                        'is_active' => ['type' => 'boolean'],
                    ],
                    'required' => ['client_name', 'quote'],
                ],
                'handler' => function (array $input): array {
                    $testimonial = Testimonial::create([
                        'client_name' => $input['client_name'],
                        'client_position' => $input['client_position'] ?? null,
                        'company' => $input['company'] ?? null,
                        'quote' => $input['quote'],
                        'rating' => $input['rating'] ?? null,
                        'project_id' => $input['project_id'] ?? null,
                        'is_active' => (bool) ($input['is_active'] ?? true),
                        'order' => (Testimonial::max('order') ?? 0) + 1,
                    ]);

                    return static::summarize($testimonial);
                },
            ],
            [
                'name' => 'update_testimonial',
                'description' => 'Update fields on an existing testimonial by id. Only pass the fields you want to change.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'client_name' => ['type' => 'string'],
                        'client_position' => ['type' => 'string'],
                        'company' => ['type' => 'string'],
                        'quote' => ['type' => 'string'],
                        'rating' => ['type' => 'integer'],
                        'is_active' => ['type' => 'boolean'],
                    ],
                    'required' => ['id'],
                ],
                'handler' => function (array $input): array {
                    $testimonial = Testimonial::find($input['id'] ?? null);

                    if (! $testimonial) {
                        return ['error' => 'Testimonial not found.'];
                    }

                    $data = array_intersect_key($input, array_flip([
                        'client_name', 'client_position', 'company', 'quote', 'rating', 'is_active',
                    ]));

                    $testimonial->update($data);

                    return static::summarize($testimonial->refresh());
                },
            ],
        ];
    }
}
