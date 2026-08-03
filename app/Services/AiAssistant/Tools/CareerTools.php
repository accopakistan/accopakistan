<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Support\Str;

class CareerTools
{
    protected static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (JobPosting::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    protected static function summarizePosting(JobPosting $posting): array
    {
        return [
            'id' => $posting->id,
            'title' => $posting->title,
            'slug' => $posting->slug,
            'department' => $posting->department,
            'location' => $posting->location,
            'type' => $posting->type,
            'status' => $posting->status,
            'closing_date' => $posting->closing_date?->toDateString(),
        ];
    }

    protected static function summarizeApplication(JobApplication $application): array
    {
        return [
            'id' => $application->id,
            'job_posting_id' => $application->job_posting_id,
            'job_title' => $application->jobPosting?->title,
            'name' => $application->name,
            'email' => $application->email,
            'phone' => $application->phone,
            'cover_letter' => $application->cover_letter,
            'status' => $application->status,
            'resume_url' => $application->resumeUrl(),
            'created_at' => $application->created_at?->toDateTimeString(),
        ];
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_job_postings',
                'description' => 'List all recruitment job postings (id, title, slug, department, location, type, status, closing_date).',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'status' => ['type' => 'string', 'description' => 'Filter by status: "open" or "closed".'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $query = JobPosting::query()->orderByDesc('id');

                    if (! empty($input['status'])) {
                        $query->where('status', $input['status']);
                    }

                    return $query->get()->map(fn (JobPosting $j) => static::summarizePosting($j))->all();
                },
            ],
            [
                'name' => 'get_job_posting',
                'description' => 'Fetch details of a single job posting by id or slug, including description and requirements.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'slug' => ['type' => 'string'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $posting = ! empty($input['id'])
                        ? JobPosting::find($input['id'])
                        : JobPosting::where('slug', $input['slug'] ?? '')->first();

                    if (! $posting) {
                        return ['error' => 'Job posting not found.'];
                    }

                    return array_merge(static::summarizePosting($posting), [
                        'description' => $posting->description,
                        'requirements' => $posting->requirements,
                    ]);
                },
            ],
            [
                'name' => 'create_job_posting',
                'description' => 'Create a new job posting. Slug is auto-generated from title.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string'],
                        'department' => ['type' => 'string', 'description' => 'e.g. "Architecture", "Engineering", "Marketing".'],
                        'location' => ['type' => 'string', 'description' => 'e.g. "Rawalpindi", "Lahore", "Karachi".'],
                        'type' => ['type' => 'string', 'description' => 'e.g. "Full-time", "Contract", "Internship".'],
                        'description' => ['type' => 'string'],
                        'requirements' => ['type' => 'string', 'description' => 'Bullet points or description of job requirements.'],
                        'status' => ['type' => 'string', 'description' => '"open" or "closed". Defaults to "open".'],
                        'closing_date' => ['type' => 'string', 'description' => 'Date format: YYYY-MM-DD.'],
                    ],
                    'required' => ['title', 'description'],
                ],
                'handler' => function (array $input): array {
                    $posting = JobPosting::create([
                        'title' => $input['title'],
                        'slug' => static::uniqueSlug($input['title']),
                        'department' => $input['department'] ?? null,
                        'location' => $input['location'] ?? null,
                        'type' => $input['type'] ?? 'Full-time',
                        'description' => $input['description'],
                        'requirements' => $input['requirements'] ?? null,
                        'status' => $input['status'] ?? 'open',
                        'closing_date' => $input['closing_date'] ?? null,
                    ]);

                    return static::summarizePosting($posting);
                },
            ],
            [
                'name' => 'update_job_posting',
                'description' => 'Update fields on an existing job posting. Only pass the fields you want to change.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'title' => ['type' => 'string'],
                        'department' => ['type' => 'string'],
                        'location' => ['type' => 'string'],
                        'type' => ['type' => 'string'],
                        'description' => ['type' => 'string'],
                        'requirements' => ['type' => 'string'],
                        'status' => ['type' => 'string'],
                        'closing_date' => ['type' => 'string'],
                    ],
                    'required' => ['id'],
                ],
                'handler' => function (array $input): array {
                    $posting = JobPosting::find($input['id'] ?? null);

                    if (! $posting) {
                        return ['error' => 'Job posting not found.'];
                    }

                    $data = array_intersect_key($input, array_flip([
                        'title', 'department', 'location', 'type', 'description', 'requirements', 'status', 'closing_date'
                    ]));
                    $posting->update($data);

                    return static::summarizePosting($posting->refresh());
                },
            ],
            [
                'name' => 'list_job_applications',
                'description' => 'List job applications received for job postings, optionally filtered by job_posting_id or status.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'job_posting_id' => ['type' => 'integer'],
                        'status' => ['type' => 'string', 'description' => 'Filter by status: "new", "reviewed", "shortlisted", "rejected".'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $query = JobApplication::query()->with('jobPosting')->orderByDesc('id');

                    if (! empty($input['job_posting_id'])) {
                        $query->where('job_posting_id', $input['job_posting_id']);
                    }

                    if (! empty($input['status'])) {
                        $query->where('status', $input['status']);
                    }

                    return $query->get()->map(fn (JobApplication $a) => static::summarizeApplication($a))->all();
                },
            ],
            [
                'name' => 'update_job_application_status',
                'description' => 'Update the status of a job application by id.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'status' => ['type' => 'string', 'description' => 'New status: "new", "reviewed", "shortlisted", "rejected".'],
                    ],
                    'required' => ['id', 'status'],
                ],
                'handler' => function (array $input): array {
                    $application = JobApplication::find($input['id'] ?? null);

                    if (! $application) {
                        return ['error' => 'Job application not found.'];
                    }

                    $application->update(['status' => $input['status']]);

                    return static::summarizeApplication($application->refresh());
                },
            ],
        ];
    }
}
