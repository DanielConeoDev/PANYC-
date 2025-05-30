<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preparacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_preparacion',
        'nombre',
        'total_costo',
        'calorias_total',
        'proteina_total',
        'lipidos_total',
        'carbohidratos_total',
        'calcio_total',
        'hierro_total',
        'sodio_total',
        'fosforo_total',
        'yodo_total',
        'zinc_total',
        'magnesio_total',
        'potasio_total',
        'tiamina_total',
        'riboflavina_total',
        'niacina_total',
        'folatos_total',
        'vitaminab12_total',
        'vitaminac_total',
        'vitaminaa_total',
        'saturada_total',
        'monoinsaturada_total',
        'poliinsaturada_total',
        'colesterol_total'
    ];

    /**
     * Relación con los items de la preparación.
     * Una preparación puede tener muchos alimentos (ítems).
     */
    /*public function items()
    {
        return $this->hasMany(ItemPreparacion::class, 'preparacion_id', 'id');
    }*/

    public function items()
    {
        return $this->hasMany(ItemPreparacion::class, 'preparacion_id');
    }

    public function itemPreparacions()
    {
        return $this->hasMany(ItemPreparacion::class);
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($preparacion) {
            $preparacion->codigo_preparacion = self::generarCodigo();
        });
    }

    public static function generarCodigo()
    {
        // Obtiene el último código generado
        $ultimo = self::orderBy('id', 'desc')->value('codigo_preparacion');

        if (!$ultimo) {
            return 'PA000'; // Si no hay registros, inicia con PA000
        }

        // Divide en letras y números
        preg_match('/([A-Z]+)(\d+)/', $ultimo, $partes);
        $letras = $partes[1]; // PA, PB, etc.
        $numero = (int)$partes[2]; // 000, 001, etc.

        // Incrementa el número
        $numero++;

        // Si el número supera 999, se resetea a 000 y aumenta las letras
        if ($numero > 999) {
            $numero = 0;
            $letras = self::incrementarLetras($letras);
        }

        // Retorna el nuevo código con ceros a la izquierda
        return $letras . str_pad($numero, 3, '0', STR_PAD_LEFT);
    }

    public static function incrementarLetras($letras)
    {
        $longitud = strlen($letras);
        $arr = str_split($letras);

        // Recorremos de derecha a izquierda
        for ($i = $longitud - 1; $i >= 0; $i--) {
            if ($arr[$i] === 'Z') {
                $arr[$i] = 'A';
            } else {
                $arr[$i] = chr(ord($arr[$i]) + 1); // Aumenta la letra
                return implode('', $arr);
            }
        }

        // Si todas eran 'Z', se agrega una nueva 'A' al inicio
        return 'A' . implode('', $arr);
    }
}
