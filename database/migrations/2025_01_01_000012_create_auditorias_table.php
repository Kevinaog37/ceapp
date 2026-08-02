<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->morphs('auditable'); // auditable_type, auditable_id (Clase, Mantenimiento, Tanqueo, ...)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('accion', 20); // creacion | actualizacion | eliminacion
            $table->jsonb('cambios')->nullable(); // diff antes/después
            $table->timestamp('created_at')->useCurrent();

            $table->index(['auditable_type', 'auditable_id']);
        });

        DB::statement("ALTER TABLE auditorias ADD CONSTRAINT auditorias_accion_check CHECK (accion IN ('creacion','actualizacion','eliminacion'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
