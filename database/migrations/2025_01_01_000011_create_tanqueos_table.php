<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanqueos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->restrictOnDelete();
            $table->dateTime('fecha_hora');
            $table->decimal('valor_pagado', 10, 2);
            $table->decimal('galones', 6, 2);
            $table->string('tipo_combustible', 20); // gasolina | diesel | gas | electrico, etc. (parametrizable a futuro)
            $table->foreignId('clase_id')->nullable()->constrained('clases')->nullOnDelete();
            $table->unsignedInteger('km_al_tanqueo');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vehiculo_id', 'fecha_hora']);
        });

        DB::statement('ALTER TABLE tanqueos ADD CONSTRAINT tanqueos_valor_check CHECK (valor_pagado >= 0)');
        DB::statement('ALTER TABLE tanqueos ADD CONSTRAINT tanqueos_galones_check CHECK (galones > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('tanqueos');
    }
};
