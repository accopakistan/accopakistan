<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BlogTagController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobPostingController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NotFoundLogController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\SeoToolsController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:dashboard.view')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
});

Route::middleware('permission:users.view')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

Route::middleware('permission:settings.view')->group(function () {
    Route::get('/settings/{group?}', [SettingsController::class, 'edit'])->name('settings.edit');
});

Route::middleware('permission:settings.manage')->group(function () {
    Route::put('/settings/{group}', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware('permission:pages.view')->group(function () {
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
});

Route::middleware('permission:pages.create')->group(function () {
    Route::get('/pages-create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
});

Route::middleware('permission:pages.edit')->group(function () {
    Route::get('/pages/{page:id}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page:id}', [PageController::class, 'update'])->name('pages.update');
});

Route::middleware('permission:pages.delete')->group(function () {
    Route::delete('/pages/{page:id}', [PageController::class, 'destroy'])->name('pages.destroy');
});

Route::middleware('permission:menus.view')->group(function () {
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
});

Route::middleware('permission:menus.manage')->group(function () {
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
});

Route::middleware('permission:media.view')->group(function () {
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
});

// Services
Route::middleware('permission:services.view')->group(function () {
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
});
Route::middleware('permission:services.create')->group(function () {
    Route::get('/services-create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
});
Route::middleware('permission:services.edit')->group(function () {
    Route::get('/services/{service:id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service:id}', [ServiceController::class, 'update'])->name('services.update');
});
Route::middleware('permission:services.delete')->group(function () {
    Route::delete('/services/{service:id}', [ServiceController::class, 'destroy'])->name('services.destroy');
});

// Projects
Route::middleware('permission:projects.view')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
});
Route::middleware('permission:projects.create')->group(function () {
    Route::get('/projects-create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('/project-categories', [ProjectCategoryController::class, 'store'])->name('project-categories.store');
});
Route::middleware('permission:projects.edit')->group(function () {
    Route::get('/projects/{project:id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project:id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project:id}/gallery/{mediaId}', [ProjectController::class, 'destroyGalleryImage'])->name('projects.gallery.destroy');
});
Route::middleware('permission:projects.delete')->group(function () {
    Route::delete('/projects/{project:id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::delete('/project-categories/{projectCategory:id}', [ProjectCategoryController::class, 'destroy'])->name('project-categories.destroy');
});

// Blog
Route::middleware('permission:blog.view')->group(function () {
    Route::get('/blog', [BlogPostController::class, 'index'])->name('blog.index');
});
Route::middleware('permission:blog.create')->group(function () {
    Route::get('/blog-create', [BlogPostController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogPostController::class, 'store'])->name('blog.store');
    Route::post('/blog-categories', [BlogCategoryController::class, 'store'])->name('blog-categories.store');
    Route::post('/blog-tags', [BlogTagController::class, 'store'])->name('blog-tags.store');
});
Route::middleware('permission:blog.edit')->group(function () {
    Route::get('/blog/{post:id}/edit', [BlogPostController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{post:id}', [BlogPostController::class, 'update'])->name('blog.update');
});
Route::middleware('permission:blog.delete')->group(function () {
    Route::delete('/blog/{post:id}', [BlogPostController::class, 'destroy'])->name('blog.destroy');
    Route::delete('/blog-categories/{blogCategory:id}', [BlogCategoryController::class, 'destroy'])->name('blog-categories.destroy');
    Route::delete('/blog-tags/{blogTag:id}', [BlogTagController::class, 'destroy'])->name('blog-tags.destroy');
});

// Team
Route::middleware('permission:team.view')->group(function () {
    Route::get('/team', [TeamMemberController::class, 'index'])->name('team.index');
});

// Testimonials
Route::middleware('permission:testimonials.view')->group(function () {
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
});

// Clients
Route::middleware('permission:clients.view')->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
});

// FAQs
Route::middleware('permission:faqs.view')->group(function () {
    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
});

// Downloads
Route::middleware('permission:downloads.view')->group(function () {
    Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index');
});

// Careers
Route::middleware('permission:careers.view')->group(function () {
    Route::get('/careers', [JobPostingController::class, 'index'])->name('careers.index');
});
Route::middleware('permission:careers.manage')->group(function () {
    Route::get('/careers-create', [JobPostingController::class, 'create'])->name('careers.create');
    Route::post('/careers', [JobPostingController::class, 'store'])->name('careers.store');
    Route::get('/careers/{jobPosting:id}/edit', [JobPostingController::class, 'edit'])->name('careers.edit');
    Route::put('/careers/{jobPosting:id}', [JobPostingController::class, 'update'])->name('careers.update');
    Route::delete('/careers/{jobPosting:id}', [JobPostingController::class, 'destroy'])->name('careers.destroy');
});

// Job Applications
Route::middleware('permission:job_applications.view')->group(function () {
    Route::get('/careers/applications', [JobApplicationController::class, 'index'])->name('careers.applications.index');
});
Route::middleware('permission:job_applications.manage')->group(function () {
    Route::put('/careers/applications/{application}', [JobApplicationController::class, 'updateStatus'])->name('careers.applications.status');
    Route::delete('/careers/applications/{application}', [JobApplicationController::class, 'destroy'])->name('careers.applications.destroy');
});

// Leads
Route::middleware('permission:leads.view')->group(function () {
    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
});
Route::middleware('permission:leads.manage')->group(function () {
    Route::put('/leads/{lead}', [LeadController::class, 'updateStatus'])->name('leads.status');
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
});

// SEO Tools
Route::middleware('permission:seo.view')->group(function () {
    Route::get('/seo', [SeoToolsController::class, 'index'])->name('seo.index');
});
Route::middleware('permission:sitemap.manage')->group(function () {
    Route::post('/seo/sitemap/regenerate', [SeoToolsController::class, 'regenerateSitemap'])->name('seo.sitemap.regenerate');
});

// Redirects
Route::middleware('permission:redirects.view')->group(function () {
    Route::get('/redirects', [RedirectController::class, 'index'])->name('redirects.index');
});

// 404 Monitor
Route::middleware('permission:logs.view')->group(function () {
    Route::get('/not-found-logs', [NotFoundLogController::class, 'index'])->name('not-found-logs.index');
    Route::delete('/not-found-logs/{notFoundLog}', [NotFoundLogController::class, 'destroy'])->name('not-found-logs.destroy');
});

// Activity Log
Route::middleware('permission:logs.view')->group(function () {
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
});

// Backups
Route::middleware('permission:backups.view')->group(function () {
    Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
});
Route::middleware('permission:backups.manage')->group(function () {
    Route::post('/backups/run', [BackupController::class, 'run'])->name('backups.run');
    Route::delete('/backups', [BackupController::class, 'destroy'])->name('backups.destroy');
});
