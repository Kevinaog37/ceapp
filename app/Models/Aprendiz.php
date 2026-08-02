<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Aprendiz extends Model
{
    use HasFactory, SoftDeletes;

    public const ESTADO_ACTIVO = 'activo';
    public const ESTADO_INACTIVO = 'inactivo';
    public const ESTADO_GRADUADO = 'graduado';

    protected $fillable = [
        'nombres',
        'apellidos',
        'tipo_documento',
        'numero_documento',
        'foto_perfil_path',
        'fecha_nacimiento',
        'telefono',
        'fecha_ingreso',
        'fecha_finalizacion_curso',
        'nivel_categoria',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'fecha_ingreso' => 'date',
            'fecha_finalizacion_curso' => 'date',
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

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    /**
     * Conductores con los que ha tenido clase (para la vista de solo-lectura "mis conductores").
     */
    public function conductores()
    {
        return Conductor::whereIn('id', $this->clases()->select('conductor_id')->distinct())->get();
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }
}
