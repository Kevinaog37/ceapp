<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conductores', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('tipo_documento', 5); // CC, CE, PA, etc.
            $table->string('numero_documento', 30);
            $table->string('foto_perfil_path')->nullable();
            $table->date('fecha_nacimiento');
            $table->string('telefono', 20);
            $table->string('licencia_categoria', 5); // A1, A2, B1, B2, B3, C1, C2, C3
            $table->date('licencia_fecha_vencimiento');
            $table->date('fecha_ingreso');
            $table->date('fecha_salida')->nullable();
            $table->string('estado', 15)->default('activo'); // activo | inactivo
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tipo_documento', 'numero_documento']);
            $table->index('estado');
            $table->index('licencia_fecha_vencimiento');
        });

        DB::statement("ALTER TABLE conductores ADD CONSTRAINT conductores_estado_check CHECK (estado IN ('activo','inactivo'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('conductores');
    }
};
