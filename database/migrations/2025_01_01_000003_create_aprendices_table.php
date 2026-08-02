<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aprendices', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('tipo_documento', 5);
            $table->string('numero_documento', 30);
            $table->string('foto_perfil_path')->nullable();
            $table->date('fecha_nacimiento');
            $table->string('telefono', 20);
            $table->date('fecha_ingreso');
            $table->date('fecha_finalizacion_curso')->nullable();
            $table->string('nivel_categoria', 5); // categoría de curso: A1, A2, B1, B2, B3, C1, C2, C3
            $table->string('estado', 15)->default('activo'); // activo | inactivo | graduado
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tipo_documento', 'numero_documento']);
            $table->index('estado');
        });

        DB::statement("ALTER TABLE aprendices ADD CONSTRAINT aprendices_estado_check CHECK (estado IN ('activo','inactivo','graduado'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('aprendices');
    }
};
