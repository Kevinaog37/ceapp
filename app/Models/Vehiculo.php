<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    use HasFactory, SoftDeletes;

    public const TIPO_MOTO = 'moto';
    public const TIPO_CARRO = 'carro';

    public const ESTADO_ACTIVO = 'activo';
    public const ESTADO_EN_MANTENIMIENTO = 'en_mantenimiento';
    public const ESTADO_INACTIVO = 'inactivo';

    protected $fillable = [
        'placa',
        'tipo',
        'marca',
        'modelo',
        'anio',
        'km_actual',
        'estado',
        'fecha_vinculacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_vinculacion' => 'date',
            'anio' => 'integer',
            'km_actual' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        // La placa se normaliza siempre en mayúsculas para evitar duplicados
        // lógicos ("abc123" vs "ABC123") pese al índice unique en BD.
        static::saving(function (Vehiculo $vehiculo) {
            $vehiculo->placa = strtoupper($vehiculo->placa);
        });
    }

    public function clases(): HasMany
    {
        return $this->hasMany(Clase::class);
    }

    public function mantenimientos(): HasMany
    {
        return $this->hasMany(Mantenimiento::class);
    }

    public function tanqueos(): HasMany
    {
        return $this->hasMany(Tanqueo::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }

    public function scopeDeTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
