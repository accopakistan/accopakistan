<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Modules and the CRUD-style abilities each one exposes.
     *
     * @var array<string, list<string>>
     */
    protected array $modules = [
        'dashboard' => ['view'],
        'users' => ['view', 'create', 'edit', 'delete'],
        'roles' => ['view', 'manage'],
        'pages' => ['view', 'create', 'edit', 'delete', 'publish'],
        'menus' => ['view', 'manage'],
        'media' => ['view', 'manage'],
        'blog' => ['view', 'create', 'edit', 'delete', 'publish'],
        'projects' => ['view', 'create', 'edit', 'delete'],
        'services' => ['view', 'create', 'edit', 'delete'],
        'team' => ['view', 'manage'],
        'careers' => ['view', 'manage'],
        'job_applications' => ['view', 'manage'],
        'testimonials' => ['view', 'manage'],
        'clients' => ['view', 'manage'],
        'awards' => ['view', 'manage'],
        'certificates' => ['view', 'manage'],
        'downloads' => ['view', 'manage'],
        'events' => ['view', 'manage'],
        'faqs' => ['view', 'manage'],
        'gallery' => ['view', 'manage'],
        'forms' => ['view', 'manage'],
        'leads' => ['view', 'manage'],
        'newsletter' => ['view', 'manage'],
        'seo' => ['view', 'manage'],
        'redirects' => ['view', 'manage'],
        'sitemap' => ['view', 'manage'],
        'settings' => ['view', 'manage'],
        'integrations' => ['view', 'manage'],
        'backups' => ['view', 'manage'],
        'logs' => ['view'],
        'ai_assistant' => ['view', 'use'],
    ];

    /**
     * Permission slugs granted to each non-admin role.
     *
     * @var array<string, list<string>>
     */
    protected array $rolePermissions = [
        'Marketing Manager' => [
            'dashboard.view', 'pages.view', 'pages.create', 'pages.edit', 'pages.publish',
            'blog.view', 'blog.create', 'blog.edit', 'blog.publish',
            'testimonials.view', 'testimonials.manage', 'clients.view', 'clients.manage',
            'awards.view', 'awards.manage', 'certificates.view', 'certificates.manage',
            'gallery.view', 'gallery.manage', 'events.view', 'events.manage',
            'newsletter.view', 'newsletter.manage', 'leads.view', 'media.view', 'media.manage',
        ],
        'SEO Manager' => [
            'dashboard.view', 'seo.view', 'seo.manage', 'redirects.view', 'redirects.manage',
            'sitemap.view', 'sitemap.manage', 'pages.view', 'pages.edit', 'blog.view', 'blog.edit',
        ],
        'Content Editor' => [
            'dashboard.view', 'pages.view', 'pages.create', 'pages.edit',
            'blog.view', 'blog.create', 'blog.edit',
            'media.view', 'media.manage', 'faqs.view', 'faqs.manage',
            'downloads.view', 'downloads.manage', 'team.view', 'team.manage',
        ],
        'HR Manager' => [
            'dashboard.view', 'careers.view', 'careers.manage',
            'job_applications.view', 'job_applications.manage', 'team.view', 'team.manage',
        ],
        'Project Manager' => [
            'dashboard.view', 'projects.view', 'projects.create', 'projects.edit', 'projects.delete',
            'services.view', 'services.create', 'services.edit', 'services.delete',
            'clients.view', 'clients.manage', 'media.view', 'media.manage',
        ],
        'Sales Manager' => [
            'dashboard.view', 'leads.view', 'leads.manage', 'forms.view',
            'newsletter.view', 'newsletter.manage', 'clients.view', 'clients.manage',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $allPermissions = [];

        foreach ($this->modules as $module => $abilities) {
            foreach ($abilities as $ability) {
                $allPermissions[] = "{$module}.{$ability}";
            }
        }

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($allPermissions);

        $administrator = Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'web']);
        $administrator->syncPermissions(array_values(array_filter(
            $allPermissions,
            fn (string $permission) => ! str_starts_with($permission, 'roles.')
        )));

        foreach ($this->rolePermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }

        $viewer = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions(array_values(array_filter(
            $allPermissions,
            fn (string $permission) => str_ends_with($permission, '.view')
        )));

        Cache::forget('spatie.permission.cache');
    }
}
