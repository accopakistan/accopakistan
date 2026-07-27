<?php

namespace App\Support;

use App\Models\NotFoundLog;
use Illuminate\Http\Request;

class NotFoundLogger
{
    /**
     * Record a 404 hit for the given request, ignoring admin, asset, and API-ish paths.
     */
    public static function log(Request $request): void
    {
        $path = '/'.ltrim($request->path(), '/');

        if ($request->is('admin*') || $request->is('livewire*') || $request->is('build/*') || $request->is('storage/*')) {
            return;
        }

        $log = NotFoundLog::firstOrNew(['path' => $path]);
        $log->referrer = $request->headers->get('referer');
        $log->hits = ($log->hits ?? 0) + 1;
        $log->last_seen_at = now();
        $log->save();
    }
}
