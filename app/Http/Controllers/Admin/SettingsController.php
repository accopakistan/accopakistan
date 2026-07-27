<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit(string $group = 'general'): View
    {
        $schema = config('site-settings');

        abort_unless(array_key_exists($group, $schema), 404);

        $fields = $schema[$group];
        $values = Setting::query()
            ->whereIn('key', array_keys($fields))
            ->pluck('value', 'key');

        return view('admin.settings.edit', [
            'group' => $group,
            'groups' => array_keys($schema),
            'fields' => $fields,
            'values' => $values,
        ]);
    }

    public function update(Request $request, string $group): RedirectResponse
    {
        $schema = config('site-settings');

        abort_unless(array_key_exists($group, $schema), 404);

        $fields = $schema[$group];

        foreach ($fields as $key => $field) {
            if ($field['type'] === 'image') {
                if ($request->hasFile($key)) {
                    $request->validate([$key => 'image|max:2048']);
                    $path = $request->file($key)->store('settings', 'public');
                    Setting::set($key, $path, $group, 'image');
                }

                if ($request->boolean("{$key}_remove")) {
                    $existing = Setting::where('key', $key)->first();
                    if ($existing?->value) {
                        Storage::disk('public')->delete($existing->value);
                    }
                    Setting::set($key, null, $group, 'image');
                }

                continue;
            }

            $value = $request->input($key);
            Setting::set($key, $value, $group, $field['type']);
        }

        return redirect()
            ->route('admin.settings.edit', $group)
            ->with('status', __('Settings saved successfully.'));
    }
}
