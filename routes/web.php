<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'active'])
    ->prefix('admin')
    ->name('admin.')
    ->group(__DIR__.'/admin.php');

Route::get('/', HomeController::class)->name('home');
Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/privacy-policy', [SiteController::class, 'privacy'])->name('privacy');
Route::get('/terms', [SiteController::class, 'terms'])->name('terms');
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{jobPosting}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{jobPosting}/apply', [CareerController::class, 'apply'])->name('careers.apply');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Catch-all for CMS-managed pages — must stay last.
Route::get('/{page:slug}', [PageController::class, 'show'])->name('page.show');
