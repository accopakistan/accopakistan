<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function show(Page $page): View
    {
        abort_unless($page->status === 'published', 404);

        $page->load('seo', 'blocks');

        return view('site.page', compact('page'));
    }
}
