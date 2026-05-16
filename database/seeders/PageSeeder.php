<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            'Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us',
            'About Us', 'Careers', 'Privacy Policy', 'Terms & Conditions'
        ];

        foreach ($pages as $title) {
            \App\Models\Page::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($title)],
                [
                    'title' => $title,
                    'content' => '<h1>' . $title . '</h1><p>Content for ' . $title . ' will go here. Please edit it from the admin panel.</p>',
                    'is_fixed' => true
                ]
            );
        }
    }
}
