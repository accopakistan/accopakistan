<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $activities = Activity::with('causer')->latest()->paginate(30);

        return view('admin.activity-log.index', compact('activities'));
    }
}
