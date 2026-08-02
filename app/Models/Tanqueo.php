<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class Tanqueo extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'vehiculo_id',
        'fecha_hora',
        'valor_pagado',
        'galones',
        'tipo_combustible',
        'clase_id',
        'km_al_tanqueo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_hora' => 'datetime',
            'valor_pagado' => 'decimal:2',
            'galones' => 'decimal:2',
            'km_al_tanqueo' => 'integer',
        ];
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function clase(): BelongsTo
    {
        return $this->belongsTo(Clase::class);
    }

    /**
     * Costo por galón, útil para reportes de consumo.
     */
    public function getCostoPorGalonAttribute(): float
    {
        return $this->galones > 0 ? round((float) $this->valor_pagado / (float) $this->galones, 2) : 0.0;
    }
}
