<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MantenimientoDetalle extends Model
{
    use HasFactory;

    public const ESTADO_BUENO = 'bueno';
    public const ESTADO_REGULAR = 'regular';
    public const ESTADO_MALO = 'malo';

    protected $fillable = [
        'mantenimiento_id',
        'componente_id',
        'estado_componente',
        'requirio_mantenimiento',
        'observacion',
    ];

    protected function casts(): array
    {
        return [
            'requirio_mantenimiento' => 'boolean',
        ];
    }

    public function mantenimiento(): BelongsTo
    {
        return $this->belongsTo(Mantenimiento::class);
    }

    public function componente(): BelongsTo
    {
        return $this->belongsTo(Componente::class);
    }
}
