<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalculadoraPorciones extends Model
{
    use HasFactory;

    protected $table = 'calculadora_porciones';
    

    protected $fillable = [
        'preparacion_id',
        'cantidad_porciones',
        'costo_total_porciones',
    ];


    // Relación con Preparacion (Padre)
    public function preparacion()
    {
        return $this->belongsTo(Preparacion::class);
    }

    // Relación con detalles (hijos)
    public function detalles()
    {
        return $this->hasMany(CalculadoraPorcionDetalle::class, 'calculadora_porcion_id');
    }
}
