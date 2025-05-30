<?php

namespace App\Filament\Resources\PreparacionResource\Pages;

use App\Filament\Resources\PreparacionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Pages\Actions\Action;

class ViewPreparacion extends ViewRecord
{
    protected static string $resource = PreparacionResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('volver')
                ->label('Regresar')
                ->icon('heroicon-o-arrow-left')
                ->color('primary')
                ->button()
                ->url($this->getResource()::getUrl('index')),

            Action::make('imprimir')
                ->label('Imprimir esta vista')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->button()
                ->extraAttributes(['onclick' => 'window.print()']),
        ];
    }

    public function getInfolist(string $name = 'default'): ?Infolist
    {
        $preparacion = $this->record->load('itemPreparacions.alimento');

        return Infolist::make($this)
            ->record($preparacion)
            ->schema([
                // Datos generales de la preparación
                Section::make('Información general de la preparación')
                    ->icon('heroicon-o-information-circle')
                    ->description('Resumen básico de la preparación, incluyendo código, nombre y costo total.')
                    ->schema([
                        TextEntry::make('codigo_preparacion')
                            ->label('Código de la preparación')
                            ->icon('heroicon-o-tag')
                            ->placeholder('N/D'),

                        TextEntry::make('nombre')
                            ->label('Nombre')
                            ->icon('heroicon-o-hashtag')
                            ->placeholder('N/D'),

                        TextEntry::make('total_costo')
                            ->label('Costo total estimado')
                            ->icon('heroicon-o-currency-dollar')
                            ->formatStateUsing(fn($state) => '$ ' . number_format($state, 0, ',', '.') . ' COP'),

                    ])
                    ->columns(3),

                // Información nutricional total agrupada en pestañas
                Tabs::make('Información Nutricional Total')
                    ->tabs([
                        Tab::make('Macronutrientes')
                            ->icon('heroicon-o-puzzle-piece')
                            ->schema([
                                TextEntry::make('calorias_total')->label('Calorías'),
                                TextEntry::make('proteina_total')->label('Proteína'),
                                TextEntry::make('lipidos_total')->label('Lípidos'),
                                TextEntry::make('carbohidratos_total')->label('Carbohidratos'),
                            ])
                            ->columns(4),

                        Tab::make('Minerales')
                            ->icon('heroicon-o-cube-transparent')
                            ->schema([
                                TextEntry::make('calcio_total')->label('Calcio'),
                                TextEntry::make('hierro_total')->label('Hierro'),
                                TextEntry::make('sodio_total')->label('Sodio'),
                                TextEntry::make('fosforo_total')->label('Fósforo'),
                                TextEntry::make('yodo_total')->label('Yodo'),
                                TextEntry::make('zinc_total')->label('Zinc'),
                                TextEntry::make('magnesio_total')->label('Magnesio'),
                                TextEntry::make('potasio_total')->label('Potasio'),
                            ])
                            ->columns(4),

                        Tab::make('Vitaminas')
                            ->icon('heroicon-o-sun')
                            ->schema([
                                TextEntry::make('tiamina_total')->label('Tiamina (B1)'),
                                TextEntry::make('riboflavina_total')->label('Riboflavina (B2)'),
                                TextEntry::make('niacina_total')->label('Niacina (B3)'),
                                TextEntry::make('folatos_total')->label('Folatos (B9)'),
                                TextEntry::make('vitaminab12_total')->label('Vitamina B12'),
                                TextEntry::make('vitaminac_total')->label('Vitamina C'),
                                TextEntry::make('vitaminaa_total')->label('Vitamina A'),
                            ])
                            ->columns(4),

                        Tab::make('Grasas')
                            ->icon('heroicon-o-fire')
                            ->schema([
                                TextEntry::make('saturada_total')->label('Grasa saturada'),
                                TextEntry::make('monoinsaturada_total')->label('Grasa monoinsaturada'),
                                TextEntry::make('poliinsaturada_total')->label('Grasa poliinsaturada'),
                                TextEntry::make('colesterol_total')->label('Colesterol'),
                            ])
                            ->columns(4),
                    ]),

                Section::make('Detalle por alimentos (ítems)')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->description('Valores nutricionales por cada alimento en la preparación')
                    ->schema([
                        RepeatableEntry::make('itemPreparacions')
                            ->label('Lista de alimentos')
                            ->schema([
                                Grid::make(3) // 3 columnas para datos básicos
                                    ->schema([
                                        TextEntry::make('alimento.codigo')
                                            ->icon('heroicon-o-tag')
                                            ->label('Código alimento'),

                                        TextEntry::make('alimento.nombre_del_alimento')
                                            ->icon('heroicon-o-hashtag')
                                            ->label('Nombre alimento'),

                                            TextEntry::make('cantidad')
                                            ->icon('heroicon-o-scale')
                                            ->label('Cantidad bruta')
                                            ->formatStateUsing(fn($state) => $state !== null ? $state . ' g/ml' : ''),
                                        
                                        TextEntry::make('cantidad_neta')
                                            ->icon('heroicon-o-scale')
                                            ->label('Cantidad neta')
                                            ->formatStateUsing(fn($state) => $state !== null ? $state . ' g/ml' : ''),                                          

                                        TextEntry::make('costo')
                                            ->icon('heroicon-o-currency-dollar')
                                            ->label('Costo')
                                            ->formatStateUsing(fn($state) => '$ ' . number_format($state, 0, ',', '.') . ' COP'),
                                    ]),

                                Tabs::make('Nutrientes por alimento')->tabs([
                                    Tab::make('Macronutrientes')
                                        ->icon('heroicon-o-puzzle-piece')
                                        ->schema([
                                            Grid::make(4)->schema([
                                                TextEntry::make('calorias_porcion_input')->label('Calorías'),
                                                TextEntry::make('proteina_porcion_input')->label('Proteína'),
                                                TextEntry::make('lipidos_porcion_input')->label('Lípidos'),
                                                TextEntry::make('carbohidratos_porcion_input')->label('Carbohidratos'),
                                            ]),
                                        ]),

                                    Tab::make('Minerales')
                                        ->icon('heroicon-o-cube-transparent')
                                        ->schema([
                                            Grid::make(4)->schema([
                                                TextEntry::make('calcio_porcion_input')->label('Calcio'),
                                                TextEntry::make('hierro_porcion_input')->label('Hierro'),
                                                TextEntry::make('sodio_porcion_input')->label('Sodio'),
                                                TextEntry::make('fosforo_porcion_input')->label('Fósforo'),
                                                TextEntry::make('yodo_porcion_input')->label('Yodo'),
                                                TextEntry::make('zinc_porcion_input')->label('Zinc'),
                                                TextEntry::make('magnesio_porcion_input')->label('Magnesio'),
                                                TextEntry::make('potasio_porcion_input')->label('Potasio'),
                                            ]),
                                        ]),

                                    Tab::make('Vitaminas')
                                        ->icon('heroicon-o-sun')
                                        ->schema([
                                            Grid::make(4)->schema([
                                                TextEntry::make('tiamina_porcion_input')->label('Tiamina'),
                                                TextEntry::make('riboflavina_porcion_input')->label('Riboflavina'),
                                                TextEntry::make('niacina_porcion_input')->label('Niacina'),
                                                TextEntry::make('folatos_porcion_input')->label('Folatos'),
                                                TextEntry::make('vitaminab12_porcion_input')->label('Vitamina B12'),
                                                TextEntry::make('vitaminac_porcion_input')->label('Vitamina C'),
                                                TextEntry::make('vitaminaa_porcion_input')->label('Vitamina A'),
                                            ]),
                                        ]),

                                    Tab::make('Grasas')
                                        ->icon('heroicon-o-fire')
                                        ->schema([
                                            Grid::make(4)->schema([
                                                TextEntry::make('saturada_porcion_input')->label('Grasa saturada'),
                                                TextEntry::make('monoinsaturada_porcion_input')->label('Grasa monoinsaturada'),
                                                TextEntry::make('poliinsaturada_porcion_input')->label('Grasa poliinsaturada'),
                                                TextEntry::make('colesterol_porcion_input')->label('Colesterol'),
                                            ]),
                                        ]),
                                ]),
                            ])
                            ->columns(1), // Aquí 1 para que el grid se adapte mejor y no quede muy comprimido
                    ])
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }
}
