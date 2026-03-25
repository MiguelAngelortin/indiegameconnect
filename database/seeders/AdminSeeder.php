<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
    'name' => 'Admin Miguel Angel',
    'email' => env('ADMIN_EMAIL'),
    'password' => bcrypt(env('ADMIN_PASSWORD')),
    'role' => 'admin',
]);
    }
}
