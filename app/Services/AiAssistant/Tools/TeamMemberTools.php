<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\TeamMember;

class TeamMemberTools
{
    protected static function summarize(TeamMember $member): array
    {
        return [
            'id' => $member->id,
            'name' => $member->name,
            'position' => $member->position,
            'department' => $member->department,
            'bio' => $member->bio,
            'email' => $member->email,
            'phone' => $member->phone,
            'linkedin_url' => $member->linkedin_url,
            'twitter_url' => $member->twitter_url,
            'order' => $member->order,
            'is_active' => $member->is_active,
            'photo_url' => $member->photoUrl(),
        ];
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_team_members',
                'description' => 'List all organization team members showing names, positions, departments, bios, contact details, order, and status.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'department' => ['type' => 'string', 'description' => 'Filter by department, e.g. "Architecture", "Engineering", "Management".'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $query = TeamMember::query()->orderBy('order');

                    if (! empty($input['department'])) {
                        $query->where('department', $input['department']);
                    }

                    return $query->get()->map(fn (TeamMember $t) => static::summarize($t))->all();
                },
            ],
            [
                'name' => 'create_team_member',
                'description' => 'Create a new team member card. Note: the profile picture itself cannot be uploaded via chat and must be uploaded manually in the admin dashboard.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'name' => ['type' => 'string'],
                        'position' => ['type' => 'string'],
                        'department' => ['type' => 'string'],
                        'bio' => ['type' => 'string'],
                        'email' => ['type' => 'string'],
                        'phone' => ['type' => 'string'],
                        'linkedin_url' => ['type' => 'string'],
                        'twitter_url' => ['type' => 'string'],
                        'is_active' => ['type' => 'boolean'],
                    ],
                    'required' => ['name', 'position'],
                ],
                'handler' => function (array $input): array {
                    $member = TeamMember::create([
                        'name' => $input['name'],
                        'position' => $input['position'],
                        'department' => $input['department'] ?? null,
                        'bio' => $input['bio'] ?? null,
                        'email' => $input['email'] ?? null,
                        'phone' => $input['phone'] ?? null,
                        'linkedin_url' => $input['linkedin_url'] ?? null,
                        'twitter_url' => $input['twitter_url'] ?? null,
                        'is_active' => (bool) ($input['is_active'] ?? true),
                        'order' => (TeamMember::max('order') ?? 0) + 1,
                    ]);

                    return static::summarize($member);
                },
            ],
            [
                'name' => 'update_team_member',
                'description' => 'Update fields on an existing team member by id. Only pass the fields you want to change.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'name' => ['type' => 'string'],
                        'position' => ['type' => 'string'],
                        'department' => ['type' => 'string'],
                        'bio' => ['type' => 'string'],
                        'email' => ['type' => 'string'],
                        'phone' => ['type' => 'string'],
                        'linkedin_url' => ['type' => 'string'],
                        'twitter_url' => ['type' => 'string'],
                        'is_active' => ['type' => 'boolean'],
                    ],
                    'required' => ['id'],
                ],
                'handler' => function (array $input): array {
                    $member = TeamMember::find($input['id'] ?? null);

                    if (! $member) {
                        return ['error' => 'Team member not found.'];
                    }

                    $data = array_intersect_key($input, array_flip([
                        'name', 'position', 'department', 'bio', 'email', 'phone', 'linkedin_url', 'twitter_url', 'is_active'
                    ]));
                    $member->update($data);

                    return static::summarize($member->refresh());
                },
            ],
        ];
    }
}
