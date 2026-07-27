<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Contracts\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::where('is_active', true)->orderBy('order')->get()->groupBy(fn ($faq) => $faq->category ?: 'General');

        return view('site.faqs', compact('faqs'));
    }
}
