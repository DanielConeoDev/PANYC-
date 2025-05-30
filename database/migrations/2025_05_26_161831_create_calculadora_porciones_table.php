<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calculadora_porciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('preparacion_id');
            $table->integer('cantidad_porciones');

            $table->decimal('costo_total_porciones', 10, 2)->nullable(); // Calculado

            $table->timestamps();

            // Clave foránea a preparaciones
            $table->foreign('preparacion_id')
                ->references('id')
                ->on('preparacions')
                ->onDelete('cascade');
        });

        Schema::create('calculadora_porcion_detalles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('calculadora_porcion_id');
            $table->string('alimento_codigo');
            $table->string('alimento_nombre'); // <-- Asegúrate que esta columna exista
            $table->decimal('cantidad_total_gramos', 10, 2)->nullable(); // gramos para las porciones
            $table->decimal('costo_unitario', 10, 2)->nullable(); // desde la tabla `costos`
            $table->decimal('subtotal', 10, 2)->nullable(); // cantidad_total_gramos * costo_unitario

            $table->timestamps();

            // Claves foráneas
            $table->foreign('calculadora_porcion_id')
                ->references('id')
                ->on('calculadora_porciones')
                ->onDelete('cascade');

            $table->foreign('alimento_codigo')
                ->references('codigo')
                ->on('alimentos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculadora_porcion_detalles');
        Schema::dropIfExists('calculadora_porciones');
    }
};
