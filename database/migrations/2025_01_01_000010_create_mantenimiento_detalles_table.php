<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantenimiento_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mantenimiento_id')->constrained('mantenimientos')->cascadeOnDelete();
            $table->foreignId('componente_id')->constrained('componentes')->restrictOnDelete();
            $table->string('estado_componente', 10); // bueno | regular | malo
            $table->boolean('requirio_mantenimiento')->default(false);
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->unique(['mantenimiento_id', 'componente_id']);
        });

        DB::statement("ALTER TABLE mantenimiento_detalles ADD CONSTRAINT mant_detalle_estado_check CHECK (estado_componente IN ('bueno','regular','malo'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimiento_detalles');
    }
};
