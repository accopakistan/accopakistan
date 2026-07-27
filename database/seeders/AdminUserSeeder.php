<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = env('SUPER_ADMIN_PASSWORD') ?: Str::password(16);

        $user = User::firstOrCreate(
            ['email' => 'accpk2014@gmail.com'],
            [
                'name' => 'ACCO Super Admin',
                'password' => $password,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('Super Admin');

        if (! env('SUPER_ADMIN_PASSWORD')) {
            $this->command?->warn("Super admin created: accpk2014@gmail.com / {$password}");
            $this->command?->warn('Save this password now — it will not be shown again. Change it after first login.');
        }
    }
}
