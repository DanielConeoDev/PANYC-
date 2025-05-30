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
        Schema::create('costos', function (Blueprint $table) {
            $table->id();
            $table->string('alimento_id');
            $table->decimal('precio', 10, 2);
            $table->enum('unidad_medida', ['kg', 'g', 'l', 'ml', 'unidad']); // Usado con Select
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        
            $table->foreign('alimento_id')
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
        Schema::dropIfExists('costos');
    }
};
