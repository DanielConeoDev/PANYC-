<?php

namespace App\Filament\Resources\AlimentoResource\Pages;

use App\Models\Grupo;
use App\Models\Fuente;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use App\Exports\AlimentoSampleExport;
use App\Filament\Resources\AlimentoResource;
use App\Filament\Widgets\ResumenAlimentos;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Pages\ListRecords\Tab;


class ListAlimentos extends ListRecords
{
    protected static string $resource = AlimentoResource::class;


    // Filtros por pestañas
    public  function getTabs(): array
    {
        return [
            'Todos' => Tab::make()
                ->label('Todos')
                ->icon('heroicon-m-list-bullet')
                ->query(fn($query) => $query),

            'TE' => Tab::make()
                ->label('Equivalentes')
                ->icon('heroicon-m-beaker')
                ->query(fn($query) => $query->where('codigo', 'like', 'TE%')),

            'Ascendente' => Tab::make()
                ->label('Ascendente (A-Z)')
                ->icon('heroicon-m-arrow-up')
                ->query(fn($query) => $query->orderBy('nombre_del_alimento', 'asc')),

            'Descendente' => Tab::make()
                ->label('Descendente (Z-A)')
                ->icon('heroicon-m-arrow-down')
                ->query(fn($query) => $query->orderBy('nombre_del_alimento', 'desc')),
        ];
    }

    // Acciones del encabezado, incluyendo importación Excel
    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->slideOver()
                ->color('info')
                ->icon('heroicon-m-arrow-up-tray')
                ->sampleExcel(
                    sampleData: [],
                    fileName: 'alimento_ejemplo.xlsx',
                    exportClass: AlimentoSampleExport::class,
                    sampleButtonLabel: 'Descargar Ejemplo',
                    customiseActionUsing: fn(Action $action) => $action
                        ->color('secondary')
                        ->icon('heroicon-m-arrow-up-tray')
                        ->requiresConfirmation()
                )
                ->beforeUploadField([
                    Select::make('fuente_id')
                        ->label('Fuente')
                        ->options(Fuente::all()->pluck('fuente', 'id'))
                        ->required()
                        ->searchable()
                        ->preload(),
        
                    Select::make('grupo_id')
                        ->label('Grupo de Alimentos')
                        ->options(Grupo::all()->pluck('grupo', 'id'))
                        ->searchable()
                        ->preload(),
                ])
                ->beforeImport(function (array $data, $livewire, $excelImportAction) {
                    $excelImportAction->additionalData([
                        'grupo_id' => $data['grupo_id'],
                        'fuente_id' => $data['fuente_id'],
                    ]);
                })
                ->validateUsing([
                    'codigo' => [
                        'required',
                        'string',
                        function ($attribute, $value, $fail) {
                            if (\App\Models\Alimento::where('codigo', $value)->exists()) {
                                $fail("El código '{$value}' ya existe en la base de datos.");
                            }
                        }
                    ],
                    // Puedes agregar más validaciones aquí si lo necesitas
                ]),
            
            Actions\CreateAction::make(),
        ];
               
        
    }
}
