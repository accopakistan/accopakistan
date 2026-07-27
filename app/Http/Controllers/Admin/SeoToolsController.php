<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SeoToolsController extends Controller
{
    public function index(): View
    {
        $sitemapPath = public_path('sitemap.xml');

        return view('admin.seo.index', [
            'sitemapExists' => File::exists($sitemapPath),
            'sitemapGeneratedAt' => File::exists($sitemapPath) ? File::lastModified($sitemapPath) : null,
            'sitemapUrlCount' => File::exists($sitemapPath) ? substr_count(File::get($sitemapPath), '<url>') : 0,
        ]);
    }

    public function regenerateSitemap(): RedirectResponse
    {
        Artisan::call('app:generate-sitemap');

        return back()->with('status', __('Sitemap regenerated successfully.'));
    }
}
