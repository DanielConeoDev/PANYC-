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
        Schema::create('item_preparacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('preparacion_id');
            $table->string('alimento_codigo'); // Mantener como string porque 'codigo' en 'alimentos' es string
            $table->decimal('cantidad', 8, 2)->nullable();
            $table->decimal('cantidad_neta', 8, 2)->nullable();
            $table->decimal('costo', 8, 2)->nullable();

            // Calorías y macronutrientes
            $table->decimal('calorias_porcion_input', 8, 2)->nullable();
            $table->decimal('proteina_porcion_input', 8, 2)->nullable();
            $table->decimal('calorias_proteina_porcion_input', 8, 2)->nullable();
            $table->decimal('lipidos_porcion_input', 8, 2)->nullable();
            $table->decimal('calorias_lipidos_porcion_input', 8, 2)->nullable();
            $table->decimal('carbohidratos_porcion_input', 8, 2)->nullable();
            $table->decimal('calorias_carbohidratos_porcion_input', 8, 2)->nullable();
            $table->decimal('ceniza_porcion_input', 8, 2)->nullable();

            // Minerales
            $table->decimal('calcio_porcion_input', 8, 2)->nullable();
            $table->decimal('hierro_porcion_input', 8, 2)->nullable();
            $table->decimal('sodio_porcion_input', 8, 2)->nullable();
            $table->decimal('fosforo_porcion_input', 8, 2)->nullable();
            $table->decimal('yodo_porcion_input', 8, 2)->nullable();
            $table->decimal('zinc_porcion_input', 8, 2)->nullable();
            $table->decimal('magnesio_porcion_input', 8, 2)->nullable();
            $table->decimal('potasio_porcion_input', 8, 2)->nullable();

            // Vitaminas
            $table->decimal('tiamina_porcion_input', 8, 2)->nullable();
            $table->decimal('riboflavina_porcion_input', 8, 2)->nullable();
            $table->decimal('niacina_porcion_input', 8, 2)->nullable();
            $table->decimal('folatos_porcion_input', 8, 2)->nullable();
            $table->decimal('vitaminab12_porcion_input', 8, 2)->nullable();
            $table->decimal('vitaminac_porcion_input', 8, 2)->nullable();
            $table->decimal('vitaminaa_porcion_input', 8, 2)->nullable();

            // Grasas y colesterol
            $table->decimal('saturada_porcion_input', 8, 2)->nullable();
            $table->decimal('monoinsaturada_porcion_input', 8, 2)->nullable();
            $table->decimal('poliinsaturada_porcion_input', 8, 2)->nullable();
            $table->decimal('colesterol_porcion_input', 8, 2)->nullable();

            $table->timestamps();

            // Definir claves foráneas
            $table->foreign('preparacion_id')->references('id')->on('preparacions')->onDelete('cascade');
            $table->foreign('alimento_codigo')->references('codigo')->on('alimentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_preparacions');
    }
};
