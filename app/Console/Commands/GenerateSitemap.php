<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the public XML sitemap from static routes and CMS content';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sitemap = Sitemap::create();

        foreach ([
            ['route' => 'home', 'priority' => 1.0],
            ['route' => 'about', 'priority' => 0.8],
            ['route' => 'services.index', 'priority' => 0.9],
            ['route' => 'projects.index', 'priority' => 0.9],
            ['route' => 'blog.index', 'priority' => 0.7],
            ['route' => 'careers.index', 'priority' => 0.6],
            ['route' => 'faqs.index', 'priority' => 0.5],
            ['route' => 'contact', 'priority' => 0.7],
        ] as $entry) {
            $sitemap->add(
                Url::create(route($entry['route']))
                    ->setPriority($entry['priority'])
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        }

        Page::published()->get()->each(function (Page $page) use ($sitemap) {
            $sitemap->add(
                Url::create(route('page.show', $page))
                    ->setLastModificationDate($page->updated_at)
                    ->setPriority(0.6)
            );
        });

        Service::published()->get()->each(function (Service $service) use ($sitemap) {
            $sitemap->add(
                Url::create(route('services.show', $service))
                    ->setLastModificationDate($service->updated_at)
                    ->setPriority(0.7)
            );
        });

        Project::published()->get()->each(function (Project $project) use ($sitemap) {
            $sitemap->add(
                Url::create(route('projects.show', $project))
                    ->setLastModificationDate($project->updated_at)
                    ->setPriority(0.7)
            );
        });

        BlogPost::published()->get()->each(function (BlogPost $post) use ($sitemap) {
            $sitemap->add(
                Url::create(route('blog.show', $post))
                    ->setLastModificationDate($post->updated_at)
                    ->setPriority(0.6)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated at public/sitemap.xml with '.count($sitemap->getTags()).' URLs.');

        return self::SUCCESS;
    }
}
