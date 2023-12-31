<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        User::factory()->admin()->create([
            'email' => 'admin@gmail.com',
            //password is password by default
        ]);
        $this->call(AdminUserSeeder::class);
        $this->call(PublisherSeeder::class);
    }
}
