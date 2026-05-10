<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder que crea el usuario administrador de la plataforma.
 * Las credenciales se cargan desde config/app.php que a su vez
 * las lee del .env, evitando hardcodear datos sensibles en el código.
 */
class AdminSeeder extends Seeder
{
    /**
     * Crea el usuario administrador con rol admin.
     * Se ejecuta desde DatabaseSeeder al lanzar php artisan db:seed.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Miguel Angel',
            'email'    => config('app.admin_email'),    // Leído de ADMIN_EMAIL en .env
            'password' => bcrypt(config('app.admin_password')), // Leído de ADMIN_PASSWORD en .env y hasheado
            'role'     => 'admin',
        ]);
    }
}