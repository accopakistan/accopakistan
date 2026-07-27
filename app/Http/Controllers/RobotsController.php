<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /profile',
            'Disallow: /dashboard',
        ];

        if ($extra = Setting::get('robots_txt_extra')) {
            $lines[] = '';
            $lines[] = trim($extra);
        }

        $lines[] = '';
        $lines[] = 'Sitemap: '.url('/sitemap.xml');

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
    }
}
