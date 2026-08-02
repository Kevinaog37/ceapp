<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Clase extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public const ESTADO_PROGRAMADA = 'programada';
    public const ESTADO_EN_CURSO = 'en_curso';
    public const ESTADO_COMPLETADA = 'completada';
    public const ESTADO_CANCELADA = 'cancelada';

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'conductor_id',
        'aprendiz_id',
        'vehiculo_id',
        'km_inicial',
        'km_final',
        'combustible_inicial',
        'combustible_final',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'km_inicial' => 'integer',
            'km_final' => 'integer',
            'combustible_inicial' => 'decimal:2',
            'combustible_final' => 'decimal:2',
        ];
    }

    public function conductor(): BelongsTo
    {
        return $this->belongsTo(Conductor::class);
    }

    public function aprendiz(): BelongsTo
    {
        return $this->belongsTo(Aprendiz::class);
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function tanqueos(): HasMany
    {
        return $this->hasMany(Tanqueo::class);
    }

    public function estaCompletada(): bool
    {
        return $this->estado === self::ESTADO_COMPLETADA;
    }

    /**
     * Kilómetros recorridos en la clase (null si aún no tiene km_final).
     */
    public function getKmRecorridosAttribute(): ?int
    {
        return $this->km_final !== null ? $this->km_final - $this->km_inicial : null;
    }

    /**
     * Combustible consumido en la clase (null si aún no tiene combustible_final).
     */
    public function getCombustibleConsumidoAttribute(): ?float
    {
        return $this->combustible_final !== null
            ? (float) $this->combustible_inicial - (float) $this->combustible_final
            : null;
    }
}
