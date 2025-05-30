<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class AlimentoSampleExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return new Collection([
            [
                'codigo' => 'A001',
                'nombre_del_alimento' => 'Arroz blanco',
                'parte_analizada' => 'Grano entero',
                'humedad_g' => 12.30,
                'energia_kcal' => 360.00,
                'energia_kj' => 1500.00,
                'proteina_g' => 6.50,
                'lipidos_g' => 1.20,
                'carbohidratos_totales_g' => 78.00,
                'carbohidratos_disponibles_g' => 75.00,
                'fibra_dietaria_g' => 2.50,
                'cenizas_g' => 0.50,
                // Minerales
                'calcio_mg' => 10.00,
                'hierro_mg' => 1.50,
                'sodio_mg' => 5.00,
                'fosforo_mg' => 120.00,
                'yodo_mg' => 0.03,
                'zinc_mg' => 1.10,
                'magnesio_mg' => 25.00,
                'potasio_mg' => 100.00,
                // Vitaminas
                'tiamina_mg' => 0.25,
                'riboflavina_mg' => 0.02,
                'niacina_mg' => 2.00,
                'folatos_mcg' => 15.00,
                'vitamina_b12_mcg' => 0.00,
                'vitamina_c_mg' => 0.00,
                'vitamina_a_er' => 0.00,
                // Grasas y colesterol
                'grasa_saturada_g' => 0.20,
                'grasa_monoinsaturada_g' => 0.30,
                'grasa_poliinsaturada_g' => 0.40,
                'colesterol_mg' => 0.00,

                // Otras características al final
                'parte_comestible_porcentaje' => 100,
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'codigo',
            'nombre_del_alimento',
            'parte_analizada',
            'humedad_g',
            'energia_kcal',
            'energia_kj',
            'proteina_g',
            'lipidos_g',
            'carbohidratos_totales_g',
            'carbohidratos_disponibles_g',
            'fibra_dietaria_g',
            'cenizas_g',
            'calcio_mg',
            'hierro_mg',
            'sodio_mg',
            'fosforo_mg',
            'yodo_mg',
            'zinc_mg',
            'magnesio_mg',
            'potasio_mg',
            'tiamina_mg',
            'riboflavina_mg',
            'niacina_mg',
            'folatos_mcg',
            'vitamina_b12_mcg',
            'vitamina_c_mg',
            'vitamina_a_er',
            'grasa_saturada_g',
            'grasa_monoinsaturada_g',
            'grasa_poliinsaturada_g',
            'colesterol_mg',

            'parte_comestible_porcentaje', // Último encabezado
        ];
    }
}
