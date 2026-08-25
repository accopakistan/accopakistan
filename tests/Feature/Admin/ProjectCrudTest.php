<?php

use App\Models\Project;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('Super Admin');
});

test('project index page loads for an authorized admin', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.projects.index'))
        ->assertOk();
});

test('project create page loads', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.projects.create'))
        ->assertOk();
});

test('a project can be created', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.projects.store'), [
        'title' => 'Test Tower Complex',
        'slug' => 'test-tower-complex',
        'client' => 'Test Client Pvt Ltd',
        'location' => 'Lahore, Pakistan',
        'status' => 'published',
        'is_featured' => '1',
        'order' => 5,
        'excerpt' => 'A test excerpt.',
        'milestones' => "Groundbreaking | 2024-01-01\nHandover | 2025-01-01",
        'services_involved' => "Architectural Design\nConstruction Management",
        'seo_title' => 'Test Tower SEO Title',
        'seo_description' => 'Test Tower SEO description.',
    ]);

    $project = Project::where('slug', 'test-tower-complex')->first();

    expect($project)->not->toBeNull();
    expect($project->title)->toBe('Test Tower Complex');
    expect($project->client)->toBe('Test Client Pvt Ltd');
    expect($project->status)->toBe('published');
    expect($project->is_featured)->toBeTrue();
    expect($project->milestones)->toBe([
        ['title' => 'Groundbreaking', 'date' => '2024-01-01'],
        ['title' => 'Handover', 'date' => '2025-01-01'],
    ]);
    expect($project->services_involved)->toBe(['Architectural Design', 'Construction Management']);
    expect($project->seo->title)->toBe('Test Tower SEO Title');

    $response->assertRedirect(route('admin.projects.edit', $project));
    $response->assertSessionHas('status');
});

test('project creation fails validation with no title', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.projects.store'), [
        'slug' => 'missing-title',
        'status' => 'draft',
    ]);

    $response->assertSessionHasErrors('title');
    expect(Project::where('slug', 'missing-title')->exists())->toBeFalse();
});

test('project creation fails validation with a duplicate slug', function () {
    Project::create(['title' => 'Existing', 'slug' => 'duplicate-slug', 'status' => 'draft']);

    $response = $this->actingAs($this->admin)->post(route('admin.projects.store'), [
        'title' => 'New One',
        'slug' => 'duplicate-slug',
        'status' => 'draft',
    ]);

    $response->assertSessionHasErrors('slug');
});

test('project edit page loads with existing data', function () {
    $project = Project::create(['title' => 'Edit Me', 'slug' => 'edit-me', 'status' => 'draft']);

    $this->actingAs($this->admin)
        ->get(route('admin.projects.edit', $project))
        ->assertOk()
        ->assertSee('Edit Me');
});

test('a project can be updated', function () {
    $project = Project::create(['title' => 'Old Title', 'slug' => 'old-slug', 'status' => 'draft', 'order' => 1]);

    $response = $this->actingAs($this->admin)->put(route('admin.projects.update', $project), [
        'title' => 'New Title',
        'slug' => 'new-slug',
        'status' => 'published',
        'order' => 2,
    ]);

    $project->refresh();

    expect($project->title)->toBe('New Title');
    expect($project->slug)->toBe('new-slug');
    expect($project->status)->toBe('published');
    expect($project->order)->toBe(2);

    $response->assertRedirect(route('admin.projects.edit', $project));
});

test('a project can have a featured image and gallery images uploaded, and a gallery image removed', function () {
    Storage::fake('public');

    $response = $this->actingAs($this->admin)->post(route('admin.projects.store'), [
        'title' => 'Image Test Project',
        'slug' => 'image-test-project',
        'status' => 'draft',
        'featured_image' => UploadedFile::fake()->image('featured.jpg'),
        'gallery' => [
            UploadedFile::fake()->image('gallery-1.jpg'),
            UploadedFile::fake()->image('gallery-2.jpg'),
        ],
    ]);

    $project = Project::where('slug', 'image-test-project')->firstOrFail();

    expect($project->featuredImageUrl())->not->toBeNull();
    expect($project->getMedia('gallery'))->toHaveCount(2);

    $response->assertRedirect(route('admin.projects.edit', $project));

    $galleryMedia = $project->getMedia('gallery')->first();

    $this->actingAs($this->admin)
        ->delete(route('admin.projects.gallery.destroy', [$project, $galleryMedia->id]))
        ->assertRedirect();

    expect($project->fresh()->getMedia('gallery'))->toHaveCount(1);
});

test('a project can be deleted', function () {
    $project = Project::create(['title' => 'Delete Me', 'slug' => 'delete-me', 'status' => 'draft']);

    $response = $this->actingAs($this->admin)->delete(route('admin.projects.destroy', $project));

    $response->assertRedirect(route('admin.projects.index'));
    expect(Project::find($project->id))->toBeNull();
});

test('a guest cannot access any project admin route', function () {
    $project = Project::create(['title' => 'Protected', 'slug' => 'protected', 'status' => 'draft']);

    $this->get(route('admin.projects.index'))->assertRedirect(route('login'));
    $this->get(route('admin.projects.create'))->assertRedirect(route('login'));
    $this->post(route('admin.projects.store'), [])->assertRedirect(route('login'));
    $this->get(route('admin.projects.edit', $project))->assertRedirect(route('login'));
    $this->put(route('admin.projects.update', $project), [])->assertRedirect(route('login'));
    $this->delete(route('admin.projects.destroy', $project))->assertRedirect(route('login'));
});

test('a user without projects permissions is forbidden', function () {
    $user = User::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->get(route('admin.projects.index'))
        ->assertForbidden();
});
