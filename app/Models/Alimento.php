<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alimento extends Model
{
    use HasFactory;

    protected $table = 'alimentos';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function ($alimento) {
            // Solo generar si el código no fue manualmente establecido
            if (empty($alimento->codigo)) {
                $lastCode = DB::table('alimentos')
                    ->where('codigo', 'like', 'TE%')
                    ->orderByDesc('codigo')
                    ->value('codigo');

                $nextNumber = $lastCode ? intval(substr($lastCode, 2)) + 1 : 1;
                $alimento->codigo = 'TE' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $casts = [
        'es_nuevo' => 'boolean',
    ];


    protected $fillable = [
        'codigo',
        'nombre_del_alimento',
        'parte_analizada',

        // Composición nutricional
        'humedad_g',
        'energia_kcal',
        'energia_kj',
        'proteina_g',
        'lipidos_g',
        'carbohidratos_totales_g',
        'carbohidratos_disponibles_g',
        'fibra_dietaria_g',
        'cenizas_g',

        // Minerales
        'calcio_mg',
        'hierro_mg',
        'sodio_mg',
        'fosforo_mg',
        'yodo_mg',
        'zinc_mg',
        'magnesio_mg',
        'potasio_mg',

        // Vitaminas
        'tiamina_mg',
        'riboflavina_mg',
        'niacina_mg',
        'folatos_mcg',
        'vitamina_b12_mcg',
        'vitamina_c_mg',
        'vitamina_a_er',

        // Grasas y colesterol
        'grasa_saturada_g',
        'grasa_monoinsaturada_g',
        'grasa_poliinsaturada_g',
        'colesterol_mg',

        // Otras características
        'parte_comestible_porcentaje',

        // Relaciones
        'grupo_id',
        'fuente_id',
    ];

    // Relaciones
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    // Alimento.php
    public function costoActivo()
    {
        return $this->hasOne(Costo::class, 'alimento_id', 'codigo')->where('estado', 'activo');
    }


    public function fuente()
    {
        return $this->belongsTo(Fuente::class);
    }

    public function costos()
    {
        return $this->hasOne(Costo::class, 'alimento_id', 'codigo');
    }

}
