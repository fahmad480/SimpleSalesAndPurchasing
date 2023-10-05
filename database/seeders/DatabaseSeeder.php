<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Superadmin User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Sales User',
            'email' => 'sales@example.com',
            'password' => bcrypt('password'),
            'role' => 'sales',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Purcheses User',
            'email' => 'purchases@example.com',
            'password' => bcrypt('password'),
            'role' => 'purcheses',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);
    }
}
