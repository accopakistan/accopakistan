<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotFoundLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class NotFoundLogController extends Controller
{
    public function index(): View
    {
        $logs = NotFoundLog::orderByDesc('hits')->paginate(20);

        return view('admin.not-found-logs.index', compact('logs'));
    }

    public function destroy(NotFoundLog $notFoundLog): RedirectResponse
    {
        $notFoundLog->delete();

        return back()->with('status', __('Entry removed.'));
    }
}
