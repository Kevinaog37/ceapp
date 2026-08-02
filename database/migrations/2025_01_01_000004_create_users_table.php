<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('rol', 20); // administrador | conductor | aprendiz
            // Vínculo opcional al registro de dominio correspondiente, según el rol.
            $table->foreignId('conductor_id')->nullable()->constrained('conductores')->nullOnDelete();
            $table->foreignId('aprendiz_id')->nullable()->constrained('aprendices')->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();

            $table->index('rol');
        });

        DB::statement("ALTER TABLE users ADD CONSTRAINT users_rol_check CHECK (rol IN ('administrador','conductor','aprendiz'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
