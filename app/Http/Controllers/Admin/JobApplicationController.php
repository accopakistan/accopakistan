<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $applications = JobApplication::with('jobPosting')
            ->when($request->filled('job_posting_id'), fn ($q) => $q->where('job_posting_id', $request->integer('job_posting_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $jobPostings = JobPosting::orderBy('title')->get(['id', 'title']);

        return view('admin.careers.applications.index', compact('applications', 'jobPostings'));
    }

    public function updateStatus(Request $request, JobApplication $application): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['new', 'reviewed', 'shortlisted', 'rejected', 'hired'])],
        ]);

        $application->update($data);

        return back()->with('status', __('Application status updated.'));
    }

    public function destroy(JobApplication $application): RedirectResponse
    {
        $application->delete();

        return back()->with('status', __('Application removed.'));
    }
}
