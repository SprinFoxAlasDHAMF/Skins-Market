<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, Notifiable;
    // Define la tabla explícitamente si es necesario
    protected $table = 'users';

    protected $fillable = [
        'nombre',
        'email',
        'password',  // Asegúrate de usar 'password' (no 'contraseña')
        'role',
        'amount',
        'stripe_account_id',
        'foto_perfil',
        'confirmacion_email',
    ];

    protected $hidden = [
        'password',  // Asegúrate de ocultar la contraseña al serializar el modelo
        'remember_token',
    ];

    protected $casts = [
        'confirmacion_email' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->password;  // Retorna la columna 'password'
    }

    /**
     * Verifica si el usuario puede acceder a Filament
     */
    public function canAccessFilament(): bool
    {
        return $this->rol == 'admin';  // Asegúrate de que el rol sea 'admin'
    }

    /**
     * Devuelve el nombre que Filament mostrará
     */
    public function getFilamentName(): string
    {
        return $this->nombre ?: 'Administrador';  // El nombre del usuario
    }

    /**
     * Opcional: devuelve la foto de perfil para Filament
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->foto_perfil ?: null;  // Foto de perfil si está disponible
    }

    public function favoritos()
    {
        return $this->belongsToMany(Item::class, 'favoritos', 'usuario_id', 'item_id')->withTimestamps();
    }
}
