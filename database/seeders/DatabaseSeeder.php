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
            'email' => 'superadmin@example.com',
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

        DB::table('inventories')->insert([
            'code' => 'INV-001',
            'name' => 'Buku Tulis',
            'price' => 5000,
            'stock' => 100,
        ]);

        DB::table('inventories')->insert([
            'code' => 'INV-002',
            'name' => 'Pensil',
            'price' => 2000,
            'stock' => 100,
        ]);

        DB::table('inventories')->insert([
            'code' => 'INV-003',
            'name' => 'Penghapus',
            'price' => 1000,
            'stock' => 100,
        ]);

        DB::table('inventories')->insert([
            'code' => 'INV-004',
            'name' => 'Penggaris',
            'price' => 3000,
            'stock' => 100,
        ]);

        DB::table('inventories')->insert([
            'code' => 'INV-005',
            'name' => 'Buku Gambar',
            'price' => 10000,
            'stock' => 100,
        ]);

        DB::table('sales')->insert([
            'user_id' => 2,
            'number' => 'SLS-001',
            'date' => '2021-01-01',
        ]);

        DB::table('sales_details')->insert([
            'sale_id' => 1,
            'inventory_id' => 1,
            'qty' => 2,
            'price' => 10000,
        ]);

        DB::table('sales_details')->insert([
            'sale_id' => 1,
            'inventory_id' => 2,
            'qty' => 2,
            'price' => 4000,
        ]);

        DB::table('sales_details')->insert([
            'sale_id' => 1,
            'inventory_id' => 3,
            'qty' => 2,
            'price' => 2000,
        ]);

        DB::table('sales')->insert([
            'user_id' => 2,
            'number' => 'SLS-002',
            'date' => '2021-01-02',
        ]);

        DB::table('sales_details')->insert([
            'sale_id' => 2,
            'inventory_id' => 1,
            'qty' => 2,
            'price' => 10000,
        ]);

        DB::table('sales_details')->insert([
            'sale_id' => 2,
            'inventory_id' => 2,
            'qty' => 3,
            'price' => 6000,
        ]);

        DB::table('sales_details')->insert([
            'sale_id' => 2,
            'inventory_id' => 3,
            'qty' => 1,
            'price' => 1000,
        ]);

        DB::table('sales')->insert([
            'user_id' => 2,
            'number' => 'SLS-003',
            'date' => '2021-01-03',
        ]);

        DB::table('sales_details')->insert([
            'sale_id' => 3,
            'inventory_id' => 4,
            'qty' => 3,
            'price' => 9000,
        ]);

        DB::table('sales_details')->insert([
            'sale_id' => 3,
            'inventory_id' => 5,
            'qty' => 6,
            'price' => 60000,
        ]);

        DB::table('purchases')->insert([
            'user_id' => 3,
            'number' => 'PRC-001',
            'date' => '2021-01-01',
        ]);

        DB::table('purchases_details')->insert([
            'purchase_id' => 1,
            'inventory_id' => 1,
            'qty' => 150,
            'price' => 750000,
        ]);

        DB::table('purchases_details')->insert([
            'purchase_id' => 1,
            'inventory_id' => 2,
            'qty' => 300,
            'price' => 600000,
        ]);

        DB::table('purchases_details')->insert([
            'purchase_id' => 1,
            'inventory_id' => 3,
            'qty' => 200,
            'price' => 200000,
        ]);

        DB::table('purchases')->insert([
            'user_id' => 3,
            'number' => 'PRC-002',
            'date' => '2021-01-02',
        ]);

        DB::table('purchases_details')->insert([
            'purchase_id' => 2,
            'inventory_id' => 5,
            'qty' => 1000,
            'price' => 10000000,
        ]);

        DB::table('purchases_details')->insert([
            'purchase_id' => 2,
            'inventory_id' => 4,
            'qty' => 300,
            'price' => 900000,
        ]);
    }
}
