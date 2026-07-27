<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredServices = Service::published()->where('is_featured', true)->orderBy('order')->get();
        $otherServices = Service::published()->where('is_featured', false)->orderBy('order')->get();

        $featuredProjects = Project::published()->where('is_featured', true)->with('category')->orderBy('order')->limit(6)->get();
        $flagshipProject = $featuredProjects->first();

        $industries = collect(range(1, 6))->map(fn ($i) => [
            'title' => Setting::get("industry{$i}_title"),
            'text' => Setting::get("industry{$i}_text"),
        ])->filter(fn ($i) => $i['title']);

        $values = collect(range(1, 4))->map(fn ($i) => [
            'title' => Setting::get("value{$i}_title"),
            'text' => Setting::get("value{$i}_text"),
        ])->filter(fn ($v) => $v['title']);

        $processSteps = collect(range(1, 4))->map(fn ($i) => [
            'title' => Setting::get("step{$i}_title"),
            'text' => Setting::get("step{$i}_text"),
        ])->filter(fn ($s) => $s['title']);

        $awards = collect(range(1, 6))->map(fn ($i) => [
            'title' => Setting::get("award{$i}_title"),
            'org' => Setting::get("award{$i}_org"),
            'year' => Setting::get("award{$i}_year"),
        ])->filter(fn ($a) => $a['title'])->take(4);

        $offices = collect(range(1, 4))->map(fn ($i) => [
            'city' => Setting::get("office{$i}_city"),
            'text' => Setting::get("office{$i}_text"),
        ])->filter(fn ($o) => $o['city']);

        return view('site.home', [
            'featuredServices' => $featuredServices,
            'otherServices' => $otherServices,
            'featuredProjects' => $featuredProjects,
            'flagshipProject' => $flagshipProject,
            'industries' => $industries,
            'values' => $values,
            'processSteps' => $processSteps,
            'awards' => $awards,
            'offices' => $offices,
            'testimonials' => Testimonial::where('is_active', true)->with('project')->orderBy('order')->limit(6)->get(),
            'clients' => Client::where('is_active', true)->orderBy('order')->get(),
            'latestPosts' => BlogPost::published()->with('category')->latest('published_at')->limit(3)->get(),
            'stats' => [
                'years_experience' => Setting::get('years_experience', 15),
                'projects_completed' => Setting::get('projects_completed', 120),
                'happy_clients' => Setting::get('happy_clients', 80),
                'awards_won' => Setting::get('awards_won', 12),
            ],
        ]);
    }
}
