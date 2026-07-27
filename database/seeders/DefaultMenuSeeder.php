<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class DefaultMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $header = Menu::firstOrCreate(
            ['slug' => 'header-navigation'],
            ['name' => 'Header Navigation', 'location' => 'header', 'is_active' => true]
        );

        if ($header->items()->count() === 0) {
            foreach ([
                ['title' => 'Home', 'route_name' => 'home', 'order' => 0],
                ['title' => 'About', 'route_name' => 'about', 'order' => 1],
                ['title' => 'Services', 'route_name' => 'services.index', 'order' => 2],
                ['title' => 'Projects', 'route_name' => 'projects.index', 'order' => 3],
                ['title' => 'Blog', 'route_name' => 'blog.index', 'order' => 4],
                ['title' => 'Careers', 'route_name' => 'careers.index', 'order' => 5],
                ['title' => 'Contact', 'route_name' => 'contact', 'order' => 6],
            ] as $item) {
                $header->items()->create([...$item, 'is_active' => true]);
            }
        }

        $footer = Menu::firstOrCreate(
            ['slug' => 'footer-navigation'],
            ['name' => 'Footer Navigation', 'location' => 'footer', 'is_active' => true]
        );

        if ($footer->items()->count() === 0) {
            foreach ([
                ['title' => 'About Us', 'route_name' => 'about', 'order' => 0],
                ['title' => 'Services', 'route_name' => 'services.index', 'order' => 1],
                ['title' => 'Projects', 'route_name' => 'projects.index', 'order' => 2],
                ['title' => 'Careers', 'route_name' => 'careers.index', 'order' => 3],
                ['title' => 'FAQs', 'route_name' => 'faqs.index', 'order' => 4],
                ['title' => 'Contact', 'route_name' => 'contact', 'order' => 5],
            ] as $item) {
                $footer->items()->create([...$item, 'is_active' => true]);
            }
        }
    }
}
