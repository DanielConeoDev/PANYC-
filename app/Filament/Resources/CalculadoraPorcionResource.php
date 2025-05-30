<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Alimento;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Preparacion;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use App\Models\CalculadoraPorciones;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;

use Filament\Tables\Filters\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CalculadoraPorcionResource\Pages;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Forms\Components\TextInput as FormTextInput; // Alias para Forms TextInput
use Filament\Tables\Filters\TextInput as FilterTextInput; // Alias para Tables Filter TextInput

class CalculadoraPorcionResource extends Resource
{
    protected static ?string $model = CalculadoraPorciones::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Cálculos';
    protected static ?string $label = 'Calculadora de Porciones';
    protected static ?string $pluralLabel = 'Calculadora de Porciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos generales')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->description('Selecciona una preparación y define la cantidad de porciones necesarias.')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('preparacion_id')
                                ->label('Preparación')
                                ->placeholder('Selecciona una preparación...')
                                ->relationship('preparacion', 'nombre')
                                ->required()
                                ->reactive()
                                ->prefixIcon('heroicon-o-book-open')
                                ->hint('Cargar alimentos automáticamente según la preparación')
                                ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                    if ($get('is_editing')) return;

                                    $preparacion = Preparacion::with('items')->find($state);

                                    if (!$preparacion) {
                                        $set('detalles', []);
                                        return;
                                    }

                                    $detalles = [];

                                    foreach ($preparacion->items as $item) {
                                        $alimento = Alimento::with('costoActivo')->where('codigo', $item->alimento_codigo)->first();

                                        $nombreAlimento = $alimento?->nombre_del_alimento ?? 'Nombre no encontrado';
                                        $costoActivo = $alimento?->costoActivo?->precio ?? 0;

                                        $detalles[] = [
                                            'alimento_codigo' => $item->alimento_codigo,
                                            'alimento_nombre' => $nombreAlimento,
                                            'cantidad_base_gramos' => $item->cantidad,
                                            'cantidad_total_gramos' => $item->cantidad,
                                            'costo_unitario' => $costoActivo,
                                            'subtotal' => $item->cantidad * ($costoActivo / 1000),
                                        ];
                                    }

                                    $set('detalles', $detalles);
                                    $set('cantidad_porciones_original', 1);
                                    $set('costo_total_porciones', array_sum(array_column($detalles, 'subtotal')));
                                }),

                            FormTextInput::make('cantidad_porciones')
                                ->label('Cantidad de porciones')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->default(1)
                                ->live(debounce: 500)
                                ->prefixIcon('heroicon-o-user-group')
                                ->suffix('porciones')
                                ->hint('Cantidad total deseada para ajustar los cálculos')
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    if ($get('is_editing')) return;

                                    $detalles = $get('detalles') ?? [];
                                    $nuevaCantidad = (float) $state ?: 1;

                                    foreach ($detalles as &$detalle) {
                                        $cantidadBase = $detalle['cantidad_base_gramos'] ?? 0;
                                        $nuevoPeso = $cantidadBase * $nuevaCantidad;
                                        $costoPorGramo = $detalle['costo_unitario'] / 1000;

                                        $detalle['cantidad_total_gramos'] = $nuevoPeso;
                                        $detalle['subtotal'] = $nuevoPeso * $costoPorGramo;
                                    }

                                    $set('detalles', $detalles);
                                    $set('costo_total_porciones', array_sum(array_column($detalles, 'subtotal')));
                                }),
                        ]),
                    ])
                    ->columns(1),

                Hidden::make('is_editing')->default(fn($record) => $record !== null),
                Hidden::make('cantidad_porciones_original'),

                Section::make('Lista de alimentos')
                    ->icon('heroicon-o-clipboard')
                    ->description('Aquí se muestran los alimentos usados, el peso total y los costos calculados por cada uno.')
                    ->schema([
                        Repeater::make('detalles')
                            ->relationship('detalles')
                            ->columns(1)
                            ->addable(false)
                            ->deletable(false)
                            ->itemLabel(fn(array $state): ?string => $state['alimento_nombre'] ?? null)
                            ->schema([
                                Grid::make(3)->schema([
                                    FormTextInput::make('alimento_codigo')
                                        ->label('Código')
                                        ->readonly()
                                        ->prefixIcon('heroicon-o-tag'),

                                    FormTextInput::make('alimento_nombre')
                                        ->label('Nombre del alimento')
                                        ->readonly()
                                        ->prefixIcon('heroicon-o-cube'),

                                    FormTextInput::make('cantidad_total_gramos')
                                        ->label('Cantidad total (g/ml)')
                                        ->readonly()
                                        ->numeric()
                                        ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.') . ' g/ml')
                                        ->suffix('g/ml'),
                                ]),
                                Grid::make(2)->schema([
                                    FormTextInput::make('costo_unitario')
                                        ->label('Costo unitario (por kg)')
                                        ->readonly()
                                        ->numeric()
                                        ->prefix('$')
                                        ->suffix('COP')
                                        ->formatStateUsing(fn($state) => number_format((float) floor($state), 0, ',', '.')),

                                    FormTextInput::make('subtotal')
                                        ->label('Subtotal')
                                        ->readonly()
                                        ->numeric()
                                        ->prefix('$')
                                        ->suffix('COP')
                                        ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),
                                ]),
                            ])
                            ->columnSpan('full'),
                    ])
                    ->columns(1),

                Section::make('Resumen de costos')
                    ->icon('heroicon-o-currency-dollar')
                    ->description('Costo total de todos los alimentos para la preparación.')
                    ->schema([
                        FormTextInput::make('costo_total_porciones')
                            ->label('Costo total de la preparación')
                            ->readonly()
                            ->numeric()
                            ->prefix('$')
                            ->suffix('COP')
                            ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.'))
                            ->extraInputAttributes(['class' => 'text-xl font-bold text-green-700']),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('preparacion.nombre')
                    ->label('Preparación')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cantidad_porciones')
                    ->label('Porciones')
                    ->sortable(),

                TextColumn::make('costo_total_porciones')
                    ->label('Costo total')
                    ->formatStateUsing(fn($state) => '$ ' . number_format($state, 0, ',', '.') . ' COP')
                    ->sortable(),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('preparacion.nombre')
                            ->label('Preparación'),

                        NumberConstraint::make('cantidad_porciones')
                            ->label('Porciones'),

                        NumberConstraint::make('costo_total_porciones')
                            ->label('Costo total'),

                        DateConstraint::make('created_at')
                            ->label('Fecha de creación'),
                    ]),
            ])
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
                DeleteAction::make(),
            ]) // Sin editar
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->icon('heroicon-o-trash'),
            ]);
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalculadoraPorcions::route('/'),
            'create' => Pages\CreateCalculadoraPorcion::route('/create'),
            //'edit' => Pages\EditCalculadoraPorcion::route('/{record}/edit'),
            'view' => Pages\ViewCalculadoraPorcion::route('/{record}'),
        ];
    }
}
