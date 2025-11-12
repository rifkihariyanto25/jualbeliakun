<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Account;
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

        // Seed accounts
        Account::create([
            'username' => 'ProGamer123',
            'rank' => 'Diamond',
            'hero_count' => 50,
            'skin_count' => 20,
            'price' => 500000,
        ]);

        Account::create([
            'username' => 'ElitePlayer',
            'rank' => 'Master',
            'hero_count' => 75,
            'skin_count' => 30,
            'price' => 750000,
        ]);

        Account::create([
            'username' => 'NoobSlayer',
            'rank' => 'Gold',
            'hero_count' => 25,
            'skin_count' => 10,
            'price' => 250000,
        ]);
    }
}
