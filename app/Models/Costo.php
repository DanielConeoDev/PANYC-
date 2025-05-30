<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Costo extends Model
{
    use HasFactory;

    protected $table = 'costos';

    protected $fillable = [
        'alimento_id',
        'precio',
        'unidad_medida',
        'estado',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function alimento()
    {
        return $this->belongsTo(Alimento::class, 'alimento_id', 'codigo');
    }

    protected static function booted()
    {
        static::creating(function ($costo) {
            // Cambiar a inactivo todos los costos anteriores del mismo alimento
            Costo::where('alimento_id', $costo->alimento_id)
                ->where('estado', 'activo')
                ->update(['estado' => 'inactivo']);

            // Marcar el nuevo como activo
            $costo->estado = 'activo';
        });
    }
}
