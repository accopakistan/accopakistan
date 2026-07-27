<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin*') || $request->is('livewire*')) {
            return $next($request);
        }

        $redirect = Redirect::where('from_path', '/'.ltrim($request->path(), '/'))
            ->where('is_active', true)
            ->first();

        if ($redirect) {
            $redirect->increment('hits');

            return redirect($redirect->to_path, $redirect->type);
        }

        return $next($request);
    }
}
