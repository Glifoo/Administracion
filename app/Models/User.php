<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;


use Filament\Panel;
use Swindon\FilamentHashids\Traits\HasHashid;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasHashid;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone',
        'logo',
        'password',
        'rol_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * realciones
     */
    public function suscripcion()
    {
        return $this->hasOne(Suscripcion::class);
    }

    public function clientes()
    {
        return $this->hasMany(Client::class, 'usuario_id');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
    public function cuentasAhorro()
    {
       return $this->hasMany(Cuentahorro::class, 'user_id');
    }
    /**
     * Funciones
     */

    public function tieneSuscripcionActiva(): bool
    {
        return $this->suscripcion()->where('estado', true)->exists();
    }

    public function hasRole(string $role): bool
    {
        return $this->rol->nombre === $role;
    }

    public function nombreRol(): string
    {
        return $this->rol?->nombre ?? 'Sin rol';
    }

    public function admin(User $user): bool
    {

        if ($user->rol_id == 1) {
            return true;
        } else {
            return false;
        }
    }
    public function verirol(User $user)
    {
        return $user->rol->nombre;
    }

    //accesos de panel
    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->hasRole('Administrador General')) {
            return true;
        }

        if ($panel->getId() === 'home' && $this->hasRole('Usuario')) {
            return true;
        }
        return false;
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->rol_id = '2';
        });
    }
}
