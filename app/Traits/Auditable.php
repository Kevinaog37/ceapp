<?php

namespace App\Traits;

use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;

/**
 * Trait para modelos que requieren auditoría (Clase, Mantenimiento, Tanqueo).
 * Registra usuario, fecha y cambios en cada creación/actualización, sin
 * duplicar lógica de observers en cada modelo.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            $model->registrarAuditoria(Auditoria::ACCION_CREACION, $model->getAttributes());
        });

        static::updated(function ($model) {
            if ($model->wasChanged()) {
                $model->registrarAuditoria(Auditoria::ACCION_ACTUALIZACION, $model->getChanges());
            }
        });

        static::deleted(function ($model) {
            $model->registrarAuditoria(Auditoria::ACCION_ELIMINACION, []);
        });
    }

    protected function registrarAuditoria(string $accion, array $cambios): void
    {
        Auditoria::create([
            'auditable_type' => static::class,
            'auditable_id' => $this->getKey(),
            'user_id' => Auth::id(),
            'accion' => $accion,
            'cambios' => $cambios,
        ]);
    }
}
