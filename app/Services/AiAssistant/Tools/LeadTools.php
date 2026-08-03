<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\Lead;

class LeadTools
{
    protected static function summarize(Lead $lead): array
    {
        return [
            'id' => $lead->id,
            'type' => $lead->type,
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'subject' => $lead->subject,
            'message' => $lead->message,
            'status' => $lead->status,
            'created_at' => $lead->created_at?->toDateTimeString(),
        ];
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_leads',
                'description' => 'List all contact inquiries, quotation requests, and client leads.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'type' => ['type' => 'string', 'description' => 'Filter by type, e.g. "contact", "quote".'],
                        'status' => ['type' => 'string', 'description' => 'Filter by status: "new", "read", "in_progress", "archived".'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $query = Lead::query()->orderByDesc('id');

                    if (! empty($input['type'])) {
                        $query->where('type', $input['type']);
                    }

                    if (! empty($input['status'])) {
                        $query->where('status', $input['status']);
                    }

                    return $query->get()->map(fn (Lead $l) => static::summarize($l))->all();
                },
            ],
            [
                'name' => 'update_lead_status',
                'description' => 'Update the operational processing status of a lead by id.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'status' => ['type' => 'string', 'description' => 'New status: "new", "read", "in_progress", "archived".'],
                    ],
                    'required' => ['id', 'status'],
                ],
                'handler' => function (array $input): array {
                    $lead = Lead::find($input['id'] ?? null);

                    if (! $lead) {
                        return ['error' => 'Lead not found.'];
                    }

                    $lead->update(['status' => $input['status']]);

                    return static::summarize($lead->refresh());
                },
            ],
        ];
    }
}
