<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create default settings
        \App\Models\Setting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'eClothing',
                'site_email' => 'info@eclothing.com',
                'site_phone' => '03001234567',
                'site_address' => 'Bahawalpur, Punjab, Pakistan',
            ]
        );
    }
}
