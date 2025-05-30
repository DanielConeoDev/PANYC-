<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Preparacion;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\PreparacionResource;
use Filament\Widgets\TableWidget as BaseWidget;

class PreparacionesTab extends BaseWidget
{
    protected static ?string $heading = 'Tabla de Preparaciones';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Preparacion::query())
            ->columns([
                TextColumn::make('codigo_preparacion')
                    ->label('Código')
                    ->searchable(), // Habilita búsqueda
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable(), // Habilita búsqueda
                TextColumn::make('total_costo')
                    ->money('COP')
                    ->label('Costo'),
            ])
            ->actions([
                Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Preparacion $record) => PreparacionResource::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(),
            ]);
    }
}
