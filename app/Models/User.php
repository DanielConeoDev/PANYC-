<?php

namespace App\Models;

use App\Models\MensajeUsuario;
use App\Notifications\UsuarioNotificado;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, HasPanelShield;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    // Relación con mensaje personalizado enviado
    public function mensajeEnviado()
    {
        return $this->hasOne(MensajeUsuario::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $rol = $user->getRoleNames()->first() ?? 'Sin rol';

            // Obtiene el último mensaje desde la tabla (si existe)
            $mensajePersonalizado = $user->mensajeEnviado()->latest()->first()?->mensaje;

            $user->notify(new UsuarioNotificado([
                'mensaje' => 'Tu usuario ha sido creado exitosamente.',
                'email' => $user->email,
                'rol' => $rol,
                'url' => url('/admin/login'),
                'mensaje_usuario' => $mensajePersonalizado,
            ]));
        });

        static::updated(function ($user) {
            $rol = $user->getRoleNames()->first() ?? 'Sin rol';

            $mensajePersonalizado = $user->mensajeEnviado()->latest()->first()?->mensaje;

            $user->notify(new UsuarioNotificado([
                'mensaje' => 'Tu usuario ha sido actualizado.',
                'email' => $user->email,
                'rol' => $rol,
                'url' => url('/admin/login'),
                'mensaje_usuario' => $mensajePersonalizado,
            ]));
        });
    }
}
