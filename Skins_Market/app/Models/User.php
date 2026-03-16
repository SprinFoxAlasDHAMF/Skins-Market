<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, Notifiable;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',      // antes 'nombre'
        'email',
        'password',  // ahora coincide con la columna
        'role',
        'profile_photo',
        'email_verified_at',
    ];

    // Campos ocultos en arrays/JSON
    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    // Casting de atributos
    protected $casts = [
        'confirmacion_email' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Devuelve la contraseña para Auth
     */
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    /**
     * Verifica si el usuario puede acceder a Filament
     */
    public function canAccessFilament(): bool
    {
        return true;  // Siempre devuelve 'true' para permitir el acceso sin restricciones
    }

    /**
     * Devuelve el nombre que Filament mostrará
     */
    public function getFilamentName(): string
    {
        // ⚡ fallback seguro si nombre es null o vacío
        return $this->nombre ?: 'Administrador';
    }

    /**
     * Opcional: devuelve la foto de perfil para Filament
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->foto_perfil ?: null;
    }
}