<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalculadoraPorcionDetalle extends Model
{
    use HasFactory;

    protected $casts = [
        'detalles' => 'array',
    ];
    

    protected $table = 'calculadora_porcion_detalles';

    protected $fillable = [
        'calculadora_porcion_id',
        'alimento_codigo',
        'alimento_nombre',
        'cantidad_total_gramos',
        'costo_unitario',
        'subtotal',
    ];

    // Relación con el modelo padre
    public function calculadoraPorcion()
{
    return $this->belongsTo(CalculadoraPorciones::class, 'calculadora_porcion_id');
}

    // Relación con Alimento para acceder a datos del alimento
    public function alimento()
    {
        return $this->belongsTo(Alimento::class, 'alimento_codigo', 'codigo');
    }
}
