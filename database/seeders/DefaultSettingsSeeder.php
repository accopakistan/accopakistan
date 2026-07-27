<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DefaultSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('site-settings') as $group => $fields) {
            foreach ($fields as $key => $field) {
                if (! array_key_exists('default', $field)) {
                    continue;
                }

                if (Setting::where('key', $key)->exists()) {
                    continue;
                }

                Setting::set($key, $field['default'], $group, $field['type']);
            }
        }
    }
}
