<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->restrictOnDelete();
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin')->nullable();
            // El responsable suele ser un conductor de la academia; si es un técnico externo
            // no registrado como conductor, se usa el campo de texto libre.
            $table->foreignId('responsable_conductor_id')->nullable()->constrained('conductores')->nullOnDelete();
            $table->string('responsable_externo')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('estado', 15)->default('pendiente'); // pendiente | en_proceso | finalizado
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vehiculo_id', 'estado']);
            $table->index('fecha_hora_inicio');
        });

        DB::statement("ALTER TABLE mantenimientos ADD CONSTRAINT mantenimientos_estado_check CHECK (estado IN ('pendiente','en_proceso','finalizado'))");
        DB::statement('ALTER TABLE mantenimientos ADD CONSTRAINT mantenimientos_fechas_check CHECK (fecha_hora_fin IS NULL OR fecha_hora_fin >= fecha_hora_inicio)');
        DB::statement('ALTER TABLE mantenimientos ADD CONSTRAINT mantenimientos_responsable_check CHECK (responsable_conductor_id IS NOT NULL OR responsable_externo IS NOT NULL)');
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};
