<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Backup\BackupDestination\BackupDestinationFactory;
use Spatie\Backup\Config\Config;

class BackupController extends Controller
{
    public function index(): View
    {
        $destinations = BackupDestinationFactory::createFromArray(Config::fromArray(config('backup')));

        $backups = $destinations
            ->flatMap(fn ($destination) => collect($destination->backups())->map(fn ($backup) => [
                'disk' => $destination->diskName(),
                'path' => $backup->path(),
                'date' => $backup->date(),
                'size' => $backup->sizeInBytes(),
            ]))
            ->sortByDesc('date')
            ->values();

        return view('admin.backups.index', compact('backups'));
    }

    public function run(): RedirectResponse
    {
        Artisan::call('backup:run');

        return back()->with('status', __('Backup started successfully. Refresh in a moment to see it listed.'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'disk' => ['required', 'string'],
            'path' => ['required', 'string'],
        ]);

        $destinations = BackupDestinationFactory::createFromArray(Config::fromArray(config('backup')));

        $destination = $destinations->firstWhere(fn ($d) => $d->diskName() === $data['disk']);

        $backup = collect($destination?->backups() ?? [])->first(fn ($b) => $b->path() === $data['path']);

        $backup?->delete();

        return back()->with('status', __('Backup deleted.'));
    }
}
