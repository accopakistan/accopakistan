<?php

namespace App\Http\Controllers;

use App\Mail\NewLeadNotification;
use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('site.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
            'website' => ['prohibited'],
        ]);

        $lead = Lead::create([
            ...collect($data)->except('website')->all(),
            'type' => 'contact',
        ]);

        $notifyEmail = Setting::get('email');

        if ($notifyEmail) {
            Mail::to($notifyEmail)->send(new NewLeadNotification($lead));
        }

        return back()->with('status', __('Thank you for reaching out. We will get back to you shortly.'));
    }
}
