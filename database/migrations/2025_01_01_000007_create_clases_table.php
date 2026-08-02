<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clases', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->foreignId('conductor_id')->constrained('conductores')->restrictOnDelete();
            $table->foreignId('aprendiz_id')->constrained('aprendices')->restrictOnDelete();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->restrictOnDelete();
            $table->unsignedInteger('km_inicial');
            $table->unsignedInteger('km_final')->nullable();
            $table->decimal('combustible_inicial', 5, 2); // porcentaje o galones, según convención definida por negocio
            $table->decimal('combustible_final', 5, 2)->nullable();
            $table->string('estado', 20)->default('programada'); // programada | en_curso | completada | cancelada
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['fecha', 'conductor_id']);
            $table->index(['fecha', 'aprendiz_id']);
            $table->index(['fecha', 'vehiculo_id']);
            $table->index('estado');
        });

        DB::statement("ALTER TABLE clases ADD CONSTRAINT clases_hora_check CHECK (hora_fin > hora_inicio)");
        DB::statement("ALTER TABLE clases ADD CONSTRAINT clases_estado_check CHECK (estado IN ('programada','en_curso','completada','cancelada'))");

        // Regla de negocio: no se puede marcar como completada sin km final y combustible final.
        // Esta es una salvaguarda a nivel de BD; el rechazo explícito con mensaje claro
        // se maneja en ClaseService::completar() antes de llegar aquí.
        DB::statement("
            ALTER TABLE clases ADD CONSTRAINT clases_cierre_completo_check CHECK (
                estado <> 'completada' OR (km_final IS NOT NULL AND combustible_final IS NOT NULL)
            )
        ");

        DB::statement('ALTER TABLE clases ADD CONSTRAINT clases_km_final_check CHECK (km_final IS NULL OR km_final >= km_inicial)');

        // Constraints de exclusión anti-traslape: mismo conductor / aprendiz / vehículo
        // no pueden tener rangos de horario solapados en la misma fecha.
        // Se excluyen las clases canceladas y las eliminadas (soft delete) de la validación.
        DB::statement("
            ALTER TABLE clases ADD CONSTRAINT clases_no_overlap_conductor
            EXCLUDE USING gist (
                conductor_id WITH =,
                tsrange(fecha + hora_inicio, fecha + hora_fin) WITH &&
            ) WHERE (estado <> 'cancelada' AND deleted_at IS NULL)
        ");

        DB::statement("
            ALTER TABLE clases ADD CONSTRAINT clases_no_overlap_aprendiz
            EXCLUDE USING gist (
                aprendiz_id WITH =,
                tsrange(fecha + hora_inicio, fecha + hora_fin) WITH &&
            ) WHERE (estado <> 'cancelada' AND deleted_at IS NULL)
        ");

        DB::statement("
            ALTER TABLE clases ADD CONSTRAINT clases_no_overlap_vehiculo
            EXCLUDE USING gist (
                vehiculo_id WITH =,
                tsrange(fecha + hora_inicio, fecha + hora_fin) WITH &&
            ) WHERE (estado <> 'cancelada' AND deleted_at IS NULL)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('clases');
    }
};
