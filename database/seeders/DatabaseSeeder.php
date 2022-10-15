<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'super admin',
            'email' => 'admin@gmail.com',
            'password' => '123123'
        ]);
        User::factory()->create([
            'name' => 'editor',
            'email' => 'editor@gmail.com',
            'password' => '123123'
        ]);

        $this->call(RoleSeeder::class);
    }
}
