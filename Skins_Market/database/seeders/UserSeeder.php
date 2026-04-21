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
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'confirmacion_email' => true,
            ]);
            User::create([
                'nombre' => 'ivan',          // Antes era 'name'
                'email' => 'ivan@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'confirmacion_email' => true,
            ]);
            $this->command->info("Usuario administrador creado: {$adminEmail} / admin123");
        } else {
            $this->command->info("El administrador ya existe.");
        }
    }
}
