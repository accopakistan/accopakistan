<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Services\AiAssistant\ImageGenerationClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class RegenerateStockImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:regenerate-images
        {--only=* : Limit to one or more of: blog, services, projects, settings, headers}
        {--dry-run : Print what would be generated without calling the API or writing anything}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate blog/service/project featured images and site header images with fresh, on-topic AI images (via Pollinations.ai, free, no API key needed)';

    public function handle(ImageGenerationClient $client): int
    {
        $only = $this->option('only');
        $groups = empty($only) ? ['blog', 'services', 'projects', 'settings', 'headers'] : $only;
        $dryRun = (bool) $this->option('dry-run');

        if (in_array('blog', $groups, true)) {
            $this->regenerateBlogPosts($client, $dryRun);
        }

        if (in_array('services', $groups, true)) {
            $this->regenerateServices($client, $dryRun);
        }

        if (in_array('projects', $groups, true)) {
            $this->regenerateProjects($client, $dryRun);
        }

        if (in_array('settings', $groups, true)) {
            $this->regenerateSetting($client, $dryRun, 'hero_image', 'homepage', 'Cinematic wide-angle photo of a modern glass and steel commercial building under a golden sunset sky, dramatic architectural photography, Pakistan skyline, no people.');
            $this->regenerateSetting($client, $dryRun, 'about_image', 'about', 'Professional architects and engineers reviewing building blueprints and a 3D model in a modern design studio, professional photography, no visible faces.');
        }

        if (in_array('headers', $groups, true)) {
            $this->regenerateSetting($client, $dryRun, 'blog_header_image', 'page_headers', 'Wide cinematic photo of an architect\'s desk with drawings, a laptop, and a coffee cup, warm editorial lighting, professional photography, no visible faces, wide banner composition.');
            $this->regenerateSetting($client, $dryRun, 'services_header_image', 'page_headers', 'Wide cinematic photo of a modern commercial building under construction with cranes against a clear sky in Pakistan, professional architectural photography, wide banner composition.');
            $this->regenerateSetting($client, $dryRun, 'projects_header_image', 'page_headers', 'Wide cinematic photo of a row of modern glass office towers and residential buildings skyline in a Pakistani city at golden hour, professional architectural photography, wide banner composition.');
            $this->regenerateSetting($client, $dryRun, 'about_header_image', 'page_headers', 'Wide cinematic photo of a professional architecture and engineering team collaborating around a table with blueprints in a modern office, professional photography, no visible faces, wide banner composition.');
            $this->regenerateSetting($client, $dryRun, 'contact_header_image', 'page_headers', 'Wide cinematic photo of a modern professional office reception or meeting room with large windows and natural light, professional photography, no people, wide banner composition.');
            $this->regenerateSetting($client, $dryRun, 'careers_header_image', 'page_headers', 'Wide cinematic photo of young professional architects and engineers collaborating in a bright modern studio office, professional photography, no visible faces, wide banner composition.');
            $this->regenerateSetting($client, $dryRun, 'faqs_header_image', 'page_headers', 'Wide cinematic photo of an organized modern architecture office bookshelf with blueprints and design reference materials, professional photography, wide banner composition.');
        }

        $this->newLine();
        $this->info($dryRun ? 'Dry run complete — nothing was changed.' : 'Done.');

        return self::SUCCESS;
    }

    protected function regenerateBlogPosts(ImageGenerationClient $client, bool $dryRun): void
    {
        $this->info('=== Blog Posts ===');

        foreach (BlogPost::all() as $post) {
            $prompt = 'Photorealistic architectural or construction-related photograph relevant to a blog post titled "'.$post->title.'". '
                .($post->excerpt ? 'Context: '.$post->excerpt.' ' : '')
                .'Professional architectural photography, Pakistan, no visible faces unless people are essential to the topic.';

            $this->generateAndAttach($client, $dryRun, $post, 'blog', $prompt);
        }
    }

    protected function regenerateServices(ImageGenerationClient $client, bool $dryRun): void
    {
        $this->info('=== Services ===');

        foreach (Service::all() as $service) {
            $prompt = 'Photorealistic professional photograph representing the architecture/construction service "'.$service->title.'". '
                .($service->excerpt ? 'Context: '.$service->excerpt.' ' : '')
                .'Professional architectural or construction photography, no visible faces unless essential.';

            $this->generateAndAttach($client, $dryRun, $service, 'service', $prompt);
        }
    }

    protected function regenerateProjects(ImageGenerationClient $client, bool $dryRun): void
    {
        $this->info('=== Projects ===');

        foreach (Project::all() as $project) {
            $prompt = 'Photorealistic exterior architectural photograph of a completed building: "'.$project->title.'"'
                .($project->location ? ' in '.$project->location : '').'. '
                .($project->excerpt ? 'Context: '.$project->excerpt.' ' : '')
                .'Daytime, professional architectural photography.';

            $this->generateAndAttach($client, $dryRun, $project, 'project', $prompt);
        }
    }

    protected function generateAndAttach(ImageGenerationClient $client, bool $dryRun, $model, string $prefix, string $prompt): void
    {
        $label = $model->title ?? "#{$model->id}";
        $this->line("#{$model->id} {$label}");
        $this->line('  prompt: '.$prompt);

        if ($dryRun) {
            return;
        }

        try {
            $image = $this->generateWithRetry($client, $prompt);
        } catch (Throwable $e) {
            $this->error('  failed: '.$e->getMessage());

            return;
        }

        $extension = match ($image['mime_type']) {
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            default => 'png',
        };

        $model->addMediaFromString($image['data'])
            ->usingFileName("{$prefix}-{$model->id}-featured.{$extension}")
            ->toMediaCollection('featured_image');

        $this->info('  -> saved ('.$model->fresh()->featuredImageUrl().')');
    }

    protected function regenerateSetting(ImageGenerationClient $client, bool $dryRun, string $key, string $group, string $prompt): void
    {
        $this->line($key);
        $this->line('  prompt: '.$prompt);

        if ($dryRun) {
            return;
        }

        try {
            $image = $this->generateWithRetry($client, $prompt);
        } catch (Throwable $e) {
            $this->error('  failed: '.$e->getMessage());

            return;
        }

        $extension = match ($image['mime_type']) {
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            default => 'png',
        };

        $existing = Setting::where('key', $key)->first();
        if ($existing && $existing->value) {
            Storage::disk('public')->delete($existing->value);
        }

        $filename = 'settings/'.$key.'-'.uniqid().'.'.$extension;
        Storage::disk('public')->put($filename, $image['data']);

        Setting::set($key, $filename, $group, 'image');

        $this->info('  -> saved ('.$filename.')');
    }

    protected function generateWithRetry(ImageGenerationClient $client, string $prompt, int $maxRetries = 3): array
    {
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                return $client->generate($prompt);
            } catch (Throwable $e) {
                if ($attempt === $maxRetries) {
                    throw $e;
                }

                $this->line("    retry ({$attempt}): {$e->getMessage()}");
                sleep(3);
            }
        }

        throw new \RuntimeException('Unreachable.');
    }
}
