<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class SiteController extends Controller
{
    public function about(): View
    {
        $story = collect(range(1, 6))->map(fn ($i) => [
            'year' => Setting::get("story{$i}_year"),
            'text' => Setting::get("story{$i}_text"),
        ])->filter(fn ($s) => $s['year']);

        $values = collect(range(1, 4))->map(fn ($i) => [
            'title' => Setting::get("value{$i}_title"),
            'text' => Setting::get("value{$i}_text"),
        ])->filter(fn ($v) => $v['title']);

        $awards = collect(range(1, 6))->map(fn ($i) => [
            'title' => Setting::get("award{$i}_title"),
            'org' => Setting::get("award{$i}_org"),
            'year' => Setting::get("award{$i}_year"),
        ])->filter(fn ($a) => $a['title']);

        return view('site.about', [
            'team' => TeamMember::where('is_active', true)->orderBy('order')->get(),
            'story' => $story,
            'values' => $values,
            'awards' => $awards,
        ]);
    }

    public function privacy(): View
    {
        return view('site.privacy');
    }

    public function terms(): View
    {
        return view('site.terms');
    }
}
