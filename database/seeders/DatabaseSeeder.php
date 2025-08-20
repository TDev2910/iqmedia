<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([PostSeeder::class,  CategorySeeder::class, ProductSeeder::class]);

        User::insert(
            [
                [
                    'first_name' => 'Bổn',
                    'last_name'  => 'Lào',
                    'email'      => 'admin@example.com',
                    'password'   => bcrypt('Admin123@'),
                    'role'       => 'admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'first_name' => 'Thiên',
                    'last_name'  => 'Đạt',
                    'email'      => 'hctd123@gmail.com',
                    'password'   => bcrypt('Dat23498@'),
                    'role'       => 'user',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]
        );
    }
}
