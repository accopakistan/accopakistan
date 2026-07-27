<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('site.services.index', [
            'services' => Service::published()->orderBy('order')->paginate(9),
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->status === 'published', 404);

        $service->load('seo');

        $relatedProjects = Project::published()
            ->get()
            ->filter(fn ($project) => in_array($service->title, $project->services_involved ?? []))
            ->take(3);

        if ($relatedProjects->isEmpty()) {
            $relatedProjects = Project::published()->where('is_featured', true)->inRandomOrder()->limit(3)->get();
        }

        $faqs = ! empty($service->faqs)
            ? $service->faqs
            : Faq::where('is_active', true)->whereIn('category', ['Services', 'Process'])->orderBy('order')->limit(6)->get()
                ->map(fn ($faq) => ['question' => $faq->question, 'answer' => $faq->answer])->all();

        return view('site.services.show', [
            'service' => $service,
            'relatedServices' => Service::published()->where('id', '!=', $service->id)->inRandomOrder()->limit(3)->get(),
            'relatedProjects' => $relatedProjects,
            'faqs' => $faqs,
        ]);
    }
}
