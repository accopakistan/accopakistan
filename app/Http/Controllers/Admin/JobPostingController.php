<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobPostingController extends Controller
{
    public function index(): View
    {
        $jobPostings = JobPosting::withCount('applications')->latest()->paginate(15);

        return view('admin.careers.index', compact('jobPostings'));
    }

    public function create(): View
    {
        return view('admin.careers.create', ['jobPosting' => new JobPosting]);
    }

    protected function rules(?JobPosting $jobPosting = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('job_postings', 'slug')->ignore($jobPosting)],
            'department' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(['full-time', 'part-time', 'contract', 'internship'])],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['open', 'closed'])],
            'closing_date' => ['nullable', 'date'],
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        $jobPosting = JobPosting::create($data);

        return redirect()->route('admin.careers.edit', $jobPosting)->with('status', __('Job posting created successfully.'));
    }

    public function edit(JobPosting $jobPosting): View
    {
        return view('admin.careers.edit', compact('jobPosting'));
    }

    public function update(Request $request, JobPosting $jobPosting): RedirectResponse
    {
        $data = $request->validate($this->rules($jobPosting));

        $jobPosting->update($data);

        return redirect()->route('admin.careers.edit', $jobPosting)->with('status', __('Job posting updated successfully.'));
    }

    public function destroy(JobPosting $jobPosting): RedirectResponse
    {
        $jobPosting->delete();

        return redirect()->route('admin.careers.index')->with('status', __('Job posting deleted successfully.'));
    }
}
