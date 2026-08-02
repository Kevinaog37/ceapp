<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROL_ADMINISTRADOR = 'administrador';
    public const ROL_CONDUCTOR = 'conductor';
    public const ROL_APRENDIZ = 'aprendiz';

    public const ROLES = [
        self::ROL_ADMINISTRADOR,
        self::ROL_CONDUCTOR,
        self::ROL_APRENDIZ,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'conductor_id',
        'aprendiz_id',
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

    /**
     * Si el usuario tiene rol "conductor", referencia a su registro de dominio Conductor.
     */
    public function conductor()
    {
        return $this->belongsTo(Conductor::class);
    }

    /**
     * Si el usuario tiene rol "aprendiz", referencia a su registro de dominio Aprendiz.
     */
    public function aprendiz()
    {
        return $this->belongsTo(Aprendiz::class);
    }

    public function esAdministrador(): bool
    {
        return $this->rol === self::ROL_ADMINISTRADOR;
    }

    public function esConductor(): bool
    {
        return $this->rol === self::ROL_CONDUCTOR;
    }

    public function esAprendiz(): bool
    {
        return $this->rol === self::ROL_APRENDIZ;
    }
}
