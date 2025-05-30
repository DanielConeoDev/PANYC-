<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPreparacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'preparacion_id',
        'alimento_codigo',
        'cantidad',
        'cantidad_neta',
        'costo',
        'lipidos_porcion',
        'proteina_porcion',

        // Calorías y macronutrientes
        'calorias_porcion_input',
        'proteina_porcion_input',
        'calorias_proteina_porcion_input',
        'lipidos_porcion_input',
        'calorias_lipidos_porcion_input',
        'carbohidratos_porcion_input',
        'calorias_carbohidratos_porcion_input',
        'ceniza_porcion_input',

        // Minerales
        'calcio_porcion_input',
        'hierro_porcion_input',
        'sodio_porcion_input',
        'fosforo_porcion_input',
        'yodo_porcion_input',
        'zinc_porcion_input',
        'magnesio_porcion_input',
        'potasio_porcion_input',

        // Vitaminas
        'tiamina_porcion_input',
        'riboflavina_porcion_input',
        'niacina_porcion_input',
        'folatos_porcion_input',
        'vitaminab12_porcion_input',
        'vitaminac_porcion_input',
        'vitaminaa_porcion_input',

        // Grasas y colesterol
        'saturada_porcion_input',
        'monoinsaturada_porcion_input',
        'poliinsaturada_porcion_input',
        'colesterol_porcion_input'
    ];

    /**
     * Relación con Preparación.
     * Cada ítem pertenece a una preparación.
     */

    public function preparacion()
    {
        return $this->belongsTo(Preparacion::class, 'preparacion_id');
    }




    /*public function preparacion()
    {
        return $this->belongsTo(Preparacion::class, 'preparacion_id', 'id');
    }*/

    /**
     * Relación con Alimento.
     * Cada ítem hace referencia a un alimento.
     */
    public function alimento()
    {
        return $this->belongsTo(Alimento::class, 'alimento_codigo', 'codigo');
    }
}
