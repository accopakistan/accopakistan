<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\JobApplication;
use App\Models\Lead;
use App\Models\NotFoundLog;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'pages' => Page::count(),
            'published_pages' => Page::published()->count(),
            'services' => Service::published()->count(),
            'projects' => Project::published()->count(),
            'blog_posts' => BlogPost::published()->count(),
            'leads_new' => Lead::where('status', 'new')->count(),
            'applications_new' => JobApplication::where('status', 'new')->count(),
            'not_found_count' => NotFoundLog::count(),
        ];

        $recentActivity = class_exists(Activity::class)
            ? Activity::with('causer')->latest()->limit(8)->get()
            : collect();

        $recentLeads = Lead::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentActivity', 'recentLeads'));
    }
}
