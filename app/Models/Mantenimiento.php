<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Mantenimiento extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_EN_PROCESO = 'en_proceso';
    public const ESTADO_FINALIZADO = 'finalizado';

    protected $fillable = [
        'vehiculo_id',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'responsable_conductor_id',
        'responsable_externo',
        'observaciones',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_hora_inicio' => 'datetime',
            'fecha_hora_fin' => 'datetime',
        ];
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function responsableConductor(): BelongsTo
    {
        return $this->belongsTo(Conductor::class, 'responsable_conductor_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(MantenimientoDetalle::class);
    }
}
