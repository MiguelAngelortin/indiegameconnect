<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Miguel Angel',
            'email' => config('app.admin_email'),
            'password' => bcrypt(config('app.admin_password')),
            'role' => 'admin',
        ]);
    }
}