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
        Schema::create('preparacions', function (Blueprint $table) {
            $table->id(); // Se crea como unsignedBigInteger automáticamente
            $table->string('codigo_preparacion')->unique();
            $table->string('nombre');
            $table->decimal('total_costo', 8, 2)->nullable();

            // Macronutrientes
            $table->decimal('calorias_total', 8, 2)->nullable();
            $table->decimal('proteina_total', 8, 2)->nullable();
            $table->decimal('lipidos_total', 8, 2)->nullable();
            $table->decimal('carbohidratos_total', 8, 2)->nullable();
            
            // Minerales
            $table->decimal('calcio_total', 8, 2)->nullable();
            $table->decimal('hierro_total', 8, 2)->nullable();
            $table->decimal('sodio_total', 8, 2)->nullable();
            $table->decimal('fosforo_total', 8, 2)->nullable();
            $table->decimal('yodo_total', 8, 2)->nullable();
            $table->decimal('zinc_total', 8, 2)->nullable();
            $table->decimal('magnesio_total', 8, 2)->nullable();
            $table->decimal('potasio_total', 8, 2)->nullable();
            
            // Vitaminas
            $table->decimal('tiamina_total', 8, 2)->nullable();
            $table->decimal('riboflavina_total', 8, 2)->nullable();
            $table->decimal('niacina_total', 8, 2)->nullable();
            $table->decimal('folatos_total', 8, 2)->nullable();
            $table->decimal('vitaminab12_total', 8, 2)->nullable();
            $table->decimal('vitaminac_total', 8, 2)->nullable();
            $table->decimal('vitaminaa_total', 8, 2)->nullable();
            
            // Grasas
            $table->decimal('saturada_total', 8, 2)->nullable();
            $table->decimal('monoinsaturada_total', 8, 2)->nullable();
            $table->decimal('poliinsaturada_total', 8, 2)->nullable();
            $table->decimal('colesterol_total', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preparacions');
    }
};
