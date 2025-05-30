<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Costo;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Livewire\Livewire;
use Filament\Tables\Actions\Action;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\CostoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CostoResource\RelationManagers;

class CostoResource extends Resource
{
    protected static ?string $model = Costo::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $label = 'Costos';
    protected static ?string $pluralLabel = 'Costos';

    protected static ?string $navigationGroup = 'Gestión de alimentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Alimento')
                    ->description('Seleccione el alimento para ver el precio activo actual y su fecha.')
                    ->schema([
                        Select::make('alimento_id')
                            ->label('Alimento')
                            ->options(function () {
                                return \App\Models\Alimento::all()->mapWithKeys(function ($alimento) {
                                    $tieneActivo = $alimento->costos()->where('estado', 'activo')->exists();

                                    $icono = $tieneActivo ? '✅' : '❌';

                                    return [
                                        $alimento->codigo => "{$icono} {$alimento->nombre_del_alimento}",
                                    ];
                                })->toArray();
                            })
                            ->searchable()
                            ->required()
                            ->reactive(),
                        Placeholder::make(' ')
                            ->content('
                    ✅ : El alimento tiene al menos un precio activo.
                    ❌ : El alimento no tiene precio activo actualmente.')
                            ->columnSpan('full'),
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('info_precio')
                                    ->label('Precio activo actual')
                                    ->content(function ($get) {
                                        $alimentoId = $get('alimento_id');
                                        if (!$alimentoId) {
                                            return 'Seleccione un alimento';
                                        }
                                        $costoActivo = Costo::where('alimento_id', $alimentoId)
                                            ->where('estado', 'activo')
                                            ->latest('created_at')
                                            ->first();

                                        return $costoActivo
                                            ? '$' . number_format($costoActivo->precio, 2)
                                            : 'No hay precio activo';
                                    }),

                                Placeholder::make('info_fecha')
                                    ->label('Fecha del precio activo')
                                    ->content(function ($get) {
                                        $alimentoId = $get('alimento_id');
                                        if (!$alimentoId) {
                                            return '—';
                                        }
                                        $costoActivo = Costo::where('alimento_id', $alimentoId)
                                            ->where('estado', 'activo')
                                            ->latest('created_at')
                                            ->first();

                                        return $costoActivo
                                            ? $costoActivo->created_at->format('Y-m-d H:i')
                                            : '—';
                                    }),
                            ]),
                    ]),

                Section::make('Nuevo Precio')
                    ->description('Ingrese el nuevo precio y seleccione la unidad de medida.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('precio')
                                    ->label('Nuevo precio')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')         // Símbolo $ antes del número
                                    ->suffix('COP'),      // Texto COP después del número

                                Select::make('unidad_medida')
                                    ->label('Unidad de medida')
                                    ->options([
                                        'kg' => 'KILOGRAMOS',
                                        'g' => 'GRAMOS',
                                        'l' => 'LITROS',
                                        'ml' => 'MILILITROS',
                                        'unidad' => 'UNIDAD',
                                    ])
                                    ->required(),
                            ]),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            //->heading('Tabla de Costos')
            ->columns([
                Tables\Columns\TextColumn::make('alimento_id')
                    ->label('Código')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alimento.nombre_del_alimento')
                    ->label('Alimento')
                    ->searchable()
                    ->formatStateUsing(fn(string $state) => strtoupper($state)),
                Tables\Columns\TextColumn::make('precio')
                    ->label('Precio')
                    ->sortable()
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 0, ',', '.') . ' COP'),
                Tables\Columns\TextColumn::make('unidad_medida')
                    ->searchable()
                    ->formatStateUsing(fn(string $state) => strtoupper($state)),
                IconColumn::make('estado')
                    ->label('Estado')
                    ->icon(fn(string $state) => $state === 'activo' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn(string $state) => $state === 'activo' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Action::make('ver_costos')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn($record) => 'Costos del alimento: ' . strtoupper($record->alimento->nombre_del_alimento))
                    ->modalWidth('6xl')
                    ->modalContent(fn($record) => view('livewire.costos-alimento-modal', [
                        'alimento_id' => $record->alimento_id,
                    ])),
                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCostos::route('/'),
            'create' => Pages\CreateCosto::route('/create'),
            'edit' => Pages\EditCosto::route('/{record}/edit'),
        ];
    }
}
