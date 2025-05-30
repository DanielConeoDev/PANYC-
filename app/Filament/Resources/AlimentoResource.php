<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Alimento;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\AlimentoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AlimentoResource\RelationManagers;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class AlimentoResource extends Resource
{
    protected static ?string $model = Alimento::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';

    protected static ?string $label = 'Alimentos';
    protected static ?string $pluralLabel = 'Alimentos';

    protected static ?string $navigationGroup = 'Gestión de alimentos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Identificación del Alimento')
                ->schema([
                    Select::make('es_nuevo')
                        ->label('¿El alimento está registrado en la tabla principal?')
                        ->options([
                            false => 'No, ya existe y tiene un código manual',
                            true => 'Sí, es nuevo y se generará un código automáticamente',
                        ])
                        ->helperText('Seleccione "Sí" si el alimento no está registrado previamente.')
                        ->required()
                        ->live()
                        ->default(false)
                        ->visible(fn($get, $record) => is_null($record)),

                    Placeholder::make('nota_equivalentes')
                        ->label('¿Qué es la tabla de equivalentes?')
                        ->content(
                            'La tabla de equivalentes está diseñada para registrar alimentos que no existen en la base de datos principal. '
                                . 'A estos alimentos se les asigna un código automático que comienza con "TE". '
                                . 'Este sistema garantiza una identificación única y ordenada de alimentos alternativos o nuevos, facilitando su gestión y trazabilidad.'
                        )
                        ->columnSpan('full'),
                ])
                ->visible(fn($get, $record) => is_null($record)),

            Section::make('Información General')->schema([
                TextInput::make('codigo')
                    ->label('Código manual')
                    ->required(fn(Get $get) => $get('es_nuevo') == false)
                    ->visible(fn(Get $get) => $get('es_nuevo') == false)
                    ->disabled(fn($record) => !is_null($record)),

                Placeholder::make('codigo')
                    ->label('Código generado automáticamente')
                    ->disabled(fn($record) => !is_null($record))
                    ->content(function () {
                        $lastCode = DB::table('alimentos')
                            ->where('codigo', 'like', 'TE%')
                            ->orderByDesc('codigo')
                            ->value('codigo');

                        $nextNumber = $lastCode ? intval(substr($lastCode, 2)) + 1 : 1;
                        return 'TE' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                    })
                    ->visible(fn(Get $get) => $get('es_nuevo') == true),
                    TextInput::make('nombre_del_alimento')
                    ->label('Nombre del alimento')
                    ->required()
                    ->maxLength(255),
        
                TextInput::make('parte_analizada')
                    ->label('Parte analizada')
                    ->maxLength(255)
                    ->default(null)
                    ->helperText('Especifique la parte del alimento que se ha analizado (ej. pulpa, semilla, etc.).'),
        
                TextInput::make('parte_comestible_porcentaje')
                    ->label('Parte comestible (%)')
                    ->numeric()
                    ->default(null)
                    ->suffix('%')
                    ->helperText('Indique el porcentaje comestible del alimento.'),
        
                Select::make('fuente_id')
                    ->label('Fuente')
                    ->relationship('fuente', 'fuente')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Seleccione la fuente de donde proviene la información.'),
        
                Select::make('grupo_id')
                    ->label('Grupo de alimentos')
                    ->relationship('grupo', 'grupo')
                    ->searchable()
                    ->preload()
                    ->helperText('Clasifique el alimento según su grupo correspondiente.'),
            ])
            ->columns(2),

            Section::make('Composición Nutricional')->schema([
                TextInput::make('humedad_g')->numeric()->default(null),
                TextInput::make('energia_kcal')->numeric()->default(null),
                TextInput::make('energia_kj')->numeric()->default(null),
                TextInput::make('proteina_g')->numeric()->default(null),
                TextInput::make('lipidos_g')->numeric()->default(null),
                TextInput::make('carbohidratos_totales_g')->numeric()->default(null),
                TextInput::make('carbohidratos_disponibles_g')->numeric()->default(null),
                TextInput::make('fibra_dietaria_g')->numeric()->default(null),
                TextInput::make('cenizas_g')->numeric()->default(null),
            ])->columns(3),

            Section::make('Minerales')->schema([
                TextInput::make('calcio_mg')->numeric()->default(null),
                TextInput::make('hierro_mg')->numeric()->default(null),
                TextInput::make('sodio_mg')->numeric()->default(null),
                TextInput::make('fosforo_mg')->numeric()->default(null),
                TextInput::make('yodo_mg')->numeric()->default(null),
                TextInput::make('zinc_mg')->numeric()->default(null),
                TextInput::make('magnesio_mg')->numeric()->default(null),
                TextInput::make('potasio_mg')->numeric()->default(null),
            ])->columns(4),

            Section::make('Vitaminas')->schema([
                TextInput::make('tiamina_mg')->numeric()->default(null),
                TextInput::make('riboflavina_mg')->numeric()->default(null),
                TextInput::make('niacina_mg')->numeric()->default(null),
                TextInput::make('folatos_mcg')->numeric()->default(null),
                TextInput::make('vitamina_b12_mcg')->numeric()->default(null),
                TextInput::make('vitamina_c_mg')->numeric()->default(null),
                TextInput::make('vitamina_a_er')->numeric()->default(null),
            ])->columns(3),

            Section::make('Grasas y Colesterol')->schema([
                TextInput::make('grasa_saturada_g')->numeric()->default(null),
                TextInput::make('grasa_monoinsaturada_g')->numeric()->default(null),
                TextInput::make('grasa_poliinsaturada_g')->numeric()->default(null),
                TextInput::make('colesterol_mg')->numeric()->default(null),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre_del_alimento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parte_analizada')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('parte_comestible_porcentaje')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('grupo.grupo')
                    ->label('Grupo')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('fuente.fuente')
                    ->label('Fuente')
                    ->limit(30)        // Limita a 30 caracteres y agrega "…" si es más largo
                    ->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make('fuente.pais')
                    ->label('País de la fuente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro por Grupo
                Tables\Filters\SelectFilter::make('grupo_id')
                    ->label('Grupo')
                    ->relationship('grupo', 'grupo')
                    ->searchable(),

                // Filtro por Fuente
                Tables\Filters\SelectFilter::make('fuente_id')
                    ->label('Fuente')
                    ->relationship('fuente', 'fuente')
                    ->searchable(),

                // Filtro por país de la fuente
                Tables\Filters\SelectFilter::make('fuente.pais')
                    ->label('País de la fuente')
                    ->options(
                        fn() => \App\Models\Fuente::query()
                            ->pluck('pais', 'pais')
                            ->unique()
                            ->filter()
                    )
                    ->searchable(),

                // Filtro por porcentaje de parte comestible mayor a cierto valor
                Tables\Filters\TernaryFilter::make('parte_comestible_porcentaje')
                    ->label('¿Tiene parte comestible definida?')
                    ->trueLabel('Sí')
                    ->falseLabel('No')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make('exportar')
                    ->label('Exportar alimentos')
                    ->exports([
                        ExcelExport::make()
                            ->fromModel()
                            ->except([
                                'grupo_id',
                                'fuente_id',
                                'created_at',
                                'updated_at',
                            ])
                            ->withFilename('alimentos_' . now()->format('Y_m_d_His'))
                    ])
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
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
            'index' => Pages\ListAlimentos::route('/'),
            'create' => Pages\CreateAlimento::route('/create'),
            'edit' => Pages\EditAlimento::route('/{record}/edit'),
        ];
    }
}
