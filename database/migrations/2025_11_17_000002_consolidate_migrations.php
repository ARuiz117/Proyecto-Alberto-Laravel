<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migración consolida las tablas principales que no fueron creadas correctamente
     * en las migraciones anteriores.
     */
    public function up(): void
    {
        // Crear tabla usuarios si no existe
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 50)->unique();
                $table->string('email', 255)->unique();
                $table->string('clave', 255);
                $table->enum('rol', ['user', 'admin'])->default('user');
                $table->decimal('saldo', 10, 2)->default(100.00);
                $table->timestamps();
            });
        }

        // Crear tabla sessions si no existe
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No eliminar en rollback para evitar pérdida de datos
    }
};
