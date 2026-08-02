<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Auditoria extends Model
{
    public const ACCION_CREACION = 'creacion';
    public const ACCION_ACTUALIZACION = 'actualizacion';
    public const ACCION_ELIMINACION = 'eliminacion';

    public $timestamps = false; // solo tiene created_at (ver migración)

    protected $fillable = [
        'auditable_type',
        'auditable_id',
        'user_id',
        'accion',
        'cambios',
    ];

    protected function casts(): array
    {
        return [
            'cambios' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
