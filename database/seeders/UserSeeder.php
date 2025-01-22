<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(5)->create();
        User::factory()->create([
            'name' => 'Do Duy Anh',
            'email' => 'admin@ghost.com',
            'password' => '123456',
            'role' => 'admin'
        ]);
    }
}
