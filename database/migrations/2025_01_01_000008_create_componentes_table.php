<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('componentes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_vehiculo', 10); // moto | carro
            $table->string('nombre'); // ej. "Frenos delanteros", "Cadena", "Aceite de motor"
            $table->string('categoria')->nullable(); // agrupación visual opcional, ej. "Motor", "Frenos"
            $table->boolean('activo')->default(true);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();

            $table->index(['tipo_vehiculo', 'activo']);
        });

        DB::statement("ALTER TABLE componentes ADD CONSTRAINT componentes_tipo_vehiculo_check CHECK (tipo_vehiculo IN ('moto','carro'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('componentes');
    }
};
