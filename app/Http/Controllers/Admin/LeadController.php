<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $leads = Lead::when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.leads.index', compact('leads'));
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,contacted,closed'],
        ]);

        $lead->update($data);

        return back()->with('status', __('Lead status updated.'));
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return back()->with('status', __('Lead deleted.'));
    }
}
