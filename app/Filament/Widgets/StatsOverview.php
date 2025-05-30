<?php

namespace App\Filament\Widgets;

use App\Models\Alimento;
use App\Models\Preparacion;
use App\Models\CalculadoraPorciones;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total de Alimentos', Alimento::count())
                ->description('Registros en la base de datos')
                ->descriptionIcon('heroicon-o-beaker', IconPosition::Before)
                ->color('success'),

            Stat::make('Total de Preparaciones', Preparacion::count())
                ->description('Preparaciones creadas')
                ->descriptionIcon('heroicon-o-clipboard-document-list', IconPosition::Before)
                ->color('primary'),

            Stat::make('Total de Cálculos de Porciones', CalculadoraPorciones::count())
                ->description('Cálculos realizados')
                ->descriptionIcon('heroicon-o-calculator', IconPosition::Before)
                ->color('warning'),
        ];
    }
}
