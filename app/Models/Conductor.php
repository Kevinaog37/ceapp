<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conductor extends Model
{
    use HasFactory, SoftDeletes;

    public const ESTADO_ACTIVO = 'activo';
    public const ESTADO_INACTIVO = 'inactivo';

    protected $fillable = [
        'nombres',
        'apellidos',
        'tipo_documento',
        'numero_documento',
        'foto_perfil_path',
        'fecha_nacimiento',
        'telefono',
        'licencia_categoria',
        'licencia_fecha_vencimiento',
        'fecha_ingreso',
        'fecha_salida',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'licencia_fecha_vencimiento' => 'date',
            'fecha_ingreso' => 'date',
            'fecha_salida' => 'date',
        ];
    }

    public function usuario(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function clases(): HasMany
    {
        return $this->hasMany(Clase::class);
    }

    public function mantenimientosResponsable(): HasMany
    {
        return $this->hasMany(Mantenimiento::class, 'responsable_conductor_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }
}
