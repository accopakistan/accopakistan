<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\Client;

class ClientTools
{
    protected static function summarize(Client $client): array
    {
        return [
            'id' => $client->id,
            'name' => $client->name,
            'website_url' => $client->website_url,
            'order' => $client->order,
            'is_active' => $client->is_active,
            'logo_url' => $client->logoUrl(),
        ];
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_clients',
                'description' => 'List all clients/partners showing id, name, website_url, order, and status.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => new \stdClass(),
                ],
                'handler' => function (array $input): array {
                    return Client::query()->orderBy('order')->get()
                        ->map(fn (Client $c) => static::summarize($c))
                        ->all();
                },
            ],
            [
                'name' => 'create_client',
                'description' => 'Create a new client entry. Note: the logo itself cannot be uploaded via chat and must be uploaded manually by the administrator in the Client Manager admin panel page.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'name' => ['type' => 'string'],
                        'website_url' => ['type' => 'string'],
                        'is_active' => ['type' => 'boolean'],
                    ],
                    'required' => ['name'],
                ],
                'handler' => function (array $input): array {
                    $client = Client::create([
                        'name' => $input['name'],
                        'website_url' => $input['website_url'] ?? null,
                        'is_active' => (bool) ($input['is_active'] ?? true),
                        'order' => (Client::max('order') ?? 0) + 1,
                    ]);

                    return static::summarize($client);
                },
            ],
            [
                'name' => 'update_client',
                'description' => 'Update fields on an existing client by id. Only pass the fields you want to change.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'name' => ['type' => 'string'],
                        'website_url' => ['type' => 'string'],
                        'is_active' => ['type' => 'boolean'],
                    ],
                    'required' => ['id'],
                ],
                'handler' => function (array $input): array {
                    $client = Client::find($input['id'] ?? null);

                    if (! $client) {
                        return ['error' => 'Client not found.'];
                    }

                    $data = array_intersect_key($input, array_flip(['name', 'website_url', 'is_active']));
                    $client->update($data);

                    return static::summarize($client->refresh());
                },
            ],
        ];
    }
}
