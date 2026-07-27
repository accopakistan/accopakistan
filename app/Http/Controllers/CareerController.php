<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(): View
    {
        return view('site.careers.index', [
            'jobPostings' => JobPosting::open()->latest()->paginate(9),
        ]);
    }

    public function show(JobPosting $jobPosting): View
    {
        return view('site.careers.show', compact('jobPosting'));
    }

    public function apply(Request $request, JobPosting $jobPosting): RedirectResponse
    {
        abort_unless($jobPosting->status === 'open', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'cover_letter' => ['nullable', 'string', 'max:3000'],
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'website' => ['prohibited'],
        ]);

        $application = JobApplication::create([
            'job_posting_id' => $jobPosting->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'cover_letter' => $data['cover_letter'] ?? null,
            'status' => 'new',
        ]);

        $application->addMediaFromRequest('resume')->toMediaCollection('resume');

        return back()->with('status', __('Your application has been submitted successfully. We will be in touch soon.'));
    }
}
