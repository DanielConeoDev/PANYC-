<?php

namespace App\Filament\Resources\CalculadoraPorcionResource\Pages;

use App\Filament\Resources\CalculadoraPorcionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Pages\Actions\Action;

class ViewCalculadoraPorcion extends ViewRecord
{
    protected static string $resource = CalculadoraPorcionResource::class;

    protected function getActions(): array
{
    return [
        Action::make('volver')
                ->label('Regresar a la lista')
                ->icon('heroicon-o-arrow-left')
                ->color('secondary')
                ->url($this->getResource()::getUrl('index')),

        Action::make('imprimir')
            ->label('Imprimir')
            ->icon('heroicon-o-printer')
            ->button()
            ->extraAttributes(['onclick' => 'window.print()']),
    ];
}


    public function getInfolist(string $name = 'default'): ?Infolist
    {
        $infolist = Infolist::make($this)
            ->record($this->record);

        $detalles = $this->record->detalles ?? [];

        return $infolist->schema([
            Section::make('Datos generales')
                ->icon('heroicon-o-clipboard')
                ->description('Información principal de la preparación y costos')
                ->schema([
                    TextEntry::make('preparacion.nombre')
                        ->label('Preparación')
                        ->icon('heroicon-o-cake'),
                    TextEntry::make('cantidad_porciones')
                        ->label('Cantidad de porciones')
                        ->icon('heroicon-o-users'),
                    TextEntry::make('costo_total_porciones')
                        ->label('Costo total')
                        ->icon('heroicon-o-currency-dollar')
                        ->formatStateUsing(fn($state) => '$ ' . number_format($state, 0, ',', '.') . ' COP'),
                ])->columns(3),

            Section::make('Detalles por alimento')
                ->icon('heroicon-o-archive-box')
                ->description('Detalle por cada alimento que compone la preparación')
                ->schema([
                    RepeatableEntry::make('detalles')
                        ->state($detalles)
                        ->schema([
                            TextEntry::make('alimento_nombre')
                                ->label('Alimento')
                                ->icon('heroicon-o-cake'),
                                TextEntry::make('cantidad_total_gramos')
                                ->label('Cantidad total (g)')
                                ->icon('heroicon-o-scale')
                                ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.') . ' g/ml'),
                            
                            TextEntry::make('subtotal')
                                ->label('Subtotal')
                                ->icon('heroicon-o-currency-dollar')
                                ->formatStateUsing(fn($state) => '$ ' . number_format($state, 0, ',', '.') . ' COP'),
                        ])->columns(3),
                ])->collapsible(),
        ]);
    }

    protected function getScripts(): array
    {
        return [
            <<<JS
        window.addEventListener('imprimir', () => {
            window.print();
        });
        JS,
        ];
    }
}
