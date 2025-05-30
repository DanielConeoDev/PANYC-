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
        Schema::create('alimentos', function (Blueprint $table) {
            // Clave primaria
            $table->string('codigo')->primary();

            // Información general del alimento
            $table->string('nombre_del_alimento');
            $table->string('parte_analizada')->nullable();

            // Composición nutricional
            $table->decimal('humedad_g', 8, 2)->nullable();
            $table->decimal('energia_kcal', 8, 2)->nullable();
            $table->decimal('energia_kj', 8, 2)->nullable();
            $table->decimal('proteina_g', 8, 2)->nullable();
            $table->decimal('lipidos_g', 8, 2)->nullable();
            $table->decimal('carbohidratos_totales_g', 8, 2)->nullable();
            $table->decimal('carbohidratos_disponibles_g', 8, 2)->nullable();
            $table->decimal('fibra_dietaria_g', 8, 2)->nullable();
            $table->decimal('cenizas_g', 8, 2)->nullable();

            // Minerales
            $table->decimal('calcio_mg', 8, 2)->nullable();
            $table->decimal('hierro_mg', 8, 2)->nullable();
            $table->decimal('sodio_mg', 8, 2)->nullable();
            $table->decimal('fosforo_mg', 8, 2)->nullable();
            $table->decimal('yodo_mg', 8, 2)->nullable();
            $table->decimal('zinc_mg', 8, 2)->nullable();
            $table->decimal('magnesio_mg', 8, 2)->nullable();
            $table->decimal('potasio_mg', 8, 2)->nullable();

            // Vitaminas
            $table->decimal('tiamina_mg', 8, 2)->nullable();
            $table->decimal('riboflavina_mg', 8, 2)->nullable();
            $table->decimal('niacina_mg', 8, 2)->nullable();
            $table->decimal('folatos_mcg', 8, 2)->nullable();
            $table->decimal('vitamina_b12_mcg', 8, 2)->nullable();
            $table->decimal('vitamina_c_mg', 8, 2)->nullable();
            $table->decimal('vitamina_a_er', 8, 2)->nullable();

            // Grasas y colesterol
            $table->decimal('grasa_saturada_g', 8, 2)->nullable();
            $table->decimal('grasa_monoinsaturada_g', 8, 2)->nullable();
            $table->decimal('grasa_poliinsaturada_g', 8, 2)->nullable();
            $table->decimal('colesterol_mg', 8, 2)->nullable();

            // Otras características
            $table->unsignedInteger('parte_comestible_porcentaje')->nullable();

            // Relaciones
            $table->foreignId('grupo_id')->nullable()->constrained();
            $table->foreignId('fuente_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alimentos');
    }
};
