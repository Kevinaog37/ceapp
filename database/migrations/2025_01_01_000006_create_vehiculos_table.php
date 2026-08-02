<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 10)->unique();
            $table->string('tipo', 10); // moto | carro
            $table->string('marca');
            $table->string('modelo');
            $table->unsignedSmallInteger('anio');
            $table->unsignedInteger('km_actual')->default(0);
            $table->string('estado', 20)->default('activo'); // activo | en_mantenimiento | inactivo
            $table->date('fecha_vinculacion');
            $table->timestamps();
            $table->softDeletes();

            $table->index('tipo');
            $table->index('estado');
        });

        DB::statement("ALTER TABLE vehiculos ADD CONSTRAINT vehiculos_tipo_check CHECK (tipo IN ('moto','carro'))");
        DB::statement("ALTER TABLE vehiculos ADD CONSTRAINT vehiculos_estado_check CHECK (estado IN ('activo','en_mantenimiento','inactivo'))");
        DB::statement('ALTER TABLE vehiculos ADD CONSTRAINT vehiculos_km_actual_check CHECK (km_actual >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
