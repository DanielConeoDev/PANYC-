<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Alimento;
use Illuminate\Support\Carbon;

class AlimentosChart extends ChartWidget
{
    protected static ?string $heading = 'Alimentos Registrados Esta Semana';
    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $alimentosPorDia = Alimento::query()
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        $labels = [];
        $data = [];

        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $date->locale('es')->isoFormat('ddd');
            $data[] = $alimentosPorDia[$formattedDate]->total ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Alimentos Registrados',
                    'data' => $data,
                    'backgroundColor' => '#3b82f6',
                ],
            ],
        ];
    }
}
