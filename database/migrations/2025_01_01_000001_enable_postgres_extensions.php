<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Necesaria para el EXCLUDE USING gist en la tabla `clases` (anti-traslape).
        DB::statement('CREATE EXTENSION IF NOT EXISTS btree_gist');
    }

    public function down(): void
    {
        // No se elimina la extensión en el rollback para no afectar otras tablas/BD que puedan usarla.
    }
};
