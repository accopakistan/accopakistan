<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::orderBy('order')->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create', ['service' => new Service]);
    }

    protected function rules(?Service $service = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('services', 'slug')->ignore($service)],
            'icon' => ['nullable', 'string', 'max:100'],
            'hero_tagline' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'process_steps' => ['nullable', 'string'],
            'comparison_table' => ['nullable', 'json'],
            'faqs' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'is_featured' => ['boolean'],
            'order' => ['nullable', 'integer'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function parseLines(?string $raw): array
    {
        return collect(explode("\n", $raw ?? ''))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function ($line) {
                [$title, $description] = array_pad(explode('|', $line, 2), 2, '');

                return ['title' => trim($title), 'description' => trim($description)];
            })
            ->values()
            ->all();
    }

    protected function parseFaqs(?string $raw): array
    {
        return collect(explode("\n", $raw ?? ''))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function ($line) {
                [$question, $answer] = array_pad(explode('|', $line, 2), 2, '');

                return ['question' => trim($question), 'answer' => trim($answer)];
            })
            ->values()
            ->all();
    }

    protected function preparePayload(array $data): array
    {
        $data['benefits'] = $this->parseLines($data['benefits'] ?? null);
        $data['process_steps'] = $this->parseLines($data['process_steps'] ?? null);
        $data['faqs'] = $this->parseFaqs($data['faqs'] ?? null);
        $data['comparison_table'] = ! empty($data['comparison_table']) ? json_decode($data['comparison_table'], true) : null;

        return $data;
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->preparePayload($request->validate($this->rules()));

        $service = Service::create(collect($data)->except(['featured_image', 'seo_title', 'seo_description', 'seo_keywords'])->all());

        if ($request->hasFile('featured_image')) {
            $service->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        $service->saveSeo([
            'title' => $data['seo_title'] ?? null,
            'description' => $data['seo_description'] ?? null,
            'keywords' => $data['seo_keywords'] ?? null,
        ]);

        return redirect()->route('admin.services.edit', $service)->with('status', __('Service created successfully.'));
    }

    public function edit(Service $service): View
    {
        $service->load('seo');

        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $this->preparePayload($request->validate($this->rules($service)));

        $service->update(collect($data)->except(['featured_image', 'seo_title', 'seo_description', 'seo_keywords'])->all());

        if ($request->hasFile('featured_image')) {
            $service->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        $service->saveSeo([
            'title' => $data['seo_title'] ?? null,
            'description' => $data['seo_description'] ?? null,
            'keywords' => $data['seo_keywords'] ?? null,
        ]);

        return redirect()->route('admin.services.edit', $service)->with('status', __('Service updated successfully.'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', __('Service deleted successfully.'));
    }
}
