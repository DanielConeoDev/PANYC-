<?php

namespace App\Filament\Widgets;

use App\Models\Costo;
use Filament\Widgets\ChartWidget;

class CostosChart extends ChartWidget
{
    protected static ?string $heading = 'Costos del alimento más reciente';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Obtiene el último costo registrado para saber cuál alimento mostrar
        $ultimoCosto = Costo::latest('created_at')->first();

        // Si no hay datos, retornar gráfico vacío
        if (!$ultimoCosto) {
            return [
                'datasets' => [
                    [
                        'label' => 'Sin datos',
                        'data' => [],
                    ],
                ],
                'labels' => [],
            ];
        }

        // Obtener TODOS los costos (sin filtrar por estado) para ese alimento_id, ordenados por fecha
        $costos = Costo::where('alimento_id', $ultimoCosto->alimento_id)
            ->orderBy('created_at')
            ->get();

        // Armar el dataset para la gráfica
        return [
            'datasets' => [
                [
                    'label' => "Precios del alimento ID: {$ultimoCosto->alimento_id}",
                    'data' => $costos->pluck('precio')->toArray(),
                ],
            ],
            // Usamos las fechas como etiquetas formateadas
            'labels' => $costos->pluck('created_at')->map(fn($fecha) => $fecha->format('d/m/Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Tipo de gráfico (líneas)
    }
}
