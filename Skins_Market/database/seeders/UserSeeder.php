<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $adminEmail = 'admin@gmail.com';

        $existingAdmin = User::where('email', $adminEmail)->first();

        if (!$existingAdmin) {
            User::create([
                'nombre' => 'admin',          // Antes era 'name'
                'email' => $adminEmail,
                'contraseña' => Hash::make('12345678'), // Antes era 'password'
                'role' => 'admin',
                'confirmacion_email' => true,
            ]);

            $this->command->info("Usuario administrador creado: {$adminEmail} / admin123");
        } else {
            $this->command->info("El administrador ya existe.");
        }
    }
}
