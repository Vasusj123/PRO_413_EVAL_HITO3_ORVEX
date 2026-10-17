<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_soporte', function (Blueprint $table) {
            $table->id(); // BIGINT autoincremental
            $table->string('solicitante', 150);
            $table->string('departamento', 150);
            $table->text('descripcion');
            $table->string('prioridad', 10);              // Baja Media Alta
            $table->string('estado', 20)->default('Pendiente'); // Pendiente en proceso resuelta
            $table->timestamps();                         // created_at updated_a
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_soporte');
    }
};
