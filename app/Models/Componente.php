<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Componente extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_vehiculo',
        'nombre',
        'categoria',
        'activo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(MantenimientoDetalle::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDeTipo($query, string $tipoVehiculo)
    {
        return $query->where('tipo_vehiculo', $tipoVehiculo);
    }
}
