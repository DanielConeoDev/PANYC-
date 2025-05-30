<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Costo;
use App\Models\Alimento;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Preparacion;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;

use Filament\Forms\Components\Placeholder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PreparacionResource\Pages;
use App\Filament\Resources\PreparacionResource\RelationManagers;


class PreparacionResource extends Resource
{
    protected static ?string $model = Preparacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $label = 'Preparaciones';
    protected static ?string $navigationGroup = 'Cálculos';
    protected static ?string $pluralLabel = 'Preparaciones';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos Iniciales de la Preparación')
                    ->icon('heroicon-o-information-circle')
                    ->description('Ingresa la información básica necesaria para registrar una preparación.')
                    ->schema([
                        Fieldset::make('')
                            ->schema([
                                TextInput::make('codigo_preparacion')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->suffix('AT')
                                    ->label('Código Único de la Preparación')
                                    ->helperText(new HtmlString('Este código <strong>identifica de forma única</strong> la preparación. <em>Se asigna automáticamente</em>.'))
                                    ->disabled()
                                    ->default(fn() => \App\Models\Preparacion::generarCodigo()), // Muestra el próximo código
                                TextInput::make('nombre')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->label('Nombre Completo de la Preparación')
                                    ->helperText(new HtmlString('Proporcione un nombre claro y completo que describa esta preparación. No utilice abreviaturas.'))
                                    ->required(),
                            ])
                    ]),
                Section::make('Cálculo Nutricional y Estimación de Costos')
                    ->icon('heroicon-o-calculator')
                    ->description('Selecciona los ingredientes de una receta para conocer sus aportes nutricionales y calcular automáticamente el costo total de la preparación.')
                    ->schema([
                        Repeater::make('preparacion_alimentos')
                            ->schema([
                                //-------------------------------------------------------------------------------------------------------
                                Fieldset::make('Seleccion Ingredientes')
                                    ->schema([
                                        Select::make('alimento_codigo')
                                            ->prefixIcon('heroicon-o-list-bullet')
                                            ->helperText(new HtmlString('Selecciona el ingrediente a evaluar.'))
                                            ->label('Alimento')
                                            /* 
                                            ✅ Obtiene todos los registros de la tabla "alimentos"
                                            Retorna un array donde:
                                            La clave es el código del alimento (lo que se almacena en la BD)
                                            El valor es una cadena que concatena el código y el nombre del alimento (lo que se muestra en la lista)
                                            */
                                            ->options(
                                                Alimento::with('costoActivo')->get()->mapWithKeys(function ($alimento) {
                                                    $precio = $alimento->costoActivo?->precio ?? 0;
                                                    $precioFormateado = '$ ' . number_format($precio, 0, ',', '.') . ' COP';
                                                    return [
                                                        $alimento->codigo => "{$alimento->codigo} - {$alimento->nombre_del_alimento} - {$precioFormateado}"
                                                    ];
                                                })
                                            )


                                            //-------------------------------------------------------------------------------------------------------
                                            ->searchable()
                                            ->required()
                                            ->reactive()

                                            //-------------------------------------------------------------------------------------------------------
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                if ($state) {
                                                    // Busca el alimento en la base de datos usando el código seleccionado.
                                                    $alimento = Alimento::where('codigo', $state)->first();

                                                    // Obtiene el costo más reciente del alimento seleccionado
                                                    $costo = \App\Models\Costo::where('alimento_id', $state)
                                                        ->latest('created_at') // Ordena por la fecha más reciente
                                                        ->value('precio'); // Devuelve el precio del último registro


                                                    if ($alimento) {
                                                        /* 
                                                ✅ Asigna los valores nutricionales y características del alimento seleccionado 
                                                a los campos del formulario para su visualización y cálculo.
                                                */
                                                        $set('codigo', $alimento->codigo);
                                                        $set('nombre_alimento', $alimento->nombre_del_alimento);
                                                        $set('parte_analizada', $alimento->parte_analizada);
                                                        $set('humedad_g', $alimento->humedad_g);
                                                        $set('energia_kcal', $alimento->energia_kcal);
                                                        $set('proteina_g', $alimento->proteina_g);
                                                        $set('energia_kj', $alimento->energia_kj);
                                                        $set('lipidos_g', $alimento->lipidos_g);
                                                        $set('carbohidratos_totales_g', $alimento->carbohidratos_totales_g);
                                                        $set('carbohidratos_disponibles_g', $alimento->carbohidratos_disponibles_g);
                                                        $set('fibra_dietaria_g', $alimento->fibra_dietaria_g);
                                                        $set('cenizas_g', $alimento->cenizas_g);
                                                        $set('calcio_mg', $alimento->calcio_mg);
                                                        $set('hierro_mg', $alimento->hierro_mg);
                                                        $set('sodio_mg', $alimento->sodio_mg);
                                                        $set('fosforo_mg', $alimento->fosforo_mg);
                                                        $set('yodo_mg', $alimento->yodo_mg);
                                                        $set('zinc_mg', $alimento->zinc_mg);
                                                        $set('magnesio_mg', $alimento->magnesio_mg);
                                                        $set('potasio_mg', $alimento->potasio_mg);
                                                        $set('tiamina_mg', $alimento->tiamina_mg);
                                                        $set('riboflavina_mg', $alimento->riboflavina_mg);
                                                        $set('niacina_mg', $alimento->niacina_mg);
                                                        $set('folatos_mcg', $alimento->folatos_mcg);
                                                        $set('vitamina_b12_mcg', $alimento->vitamina_b12_mcg);
                                                        $set('vitamina_c_mg', $alimento->vitamina_c_mg);
                                                        $set('vitamina_a_er', $alimento->vitamina_a_er);
                                                        $set('grasa_saturada_g', $alimento->grasa_saturada_g);
                                                        $set('grasa_monoinsaturada_g', $alimento->grasa_monoinsaturada_g);
                                                        $set('grasa_poliinsaturada_g', $alimento->grasa_poliinsaturada_g);
                                                        $set('colesterol_mg', $alimento->colesterol_mg);
                                                        $set('parte_comestible_porcentaje', $alimento->parte_comestible_porcentaje);
                                                        $set('fuente_id', $alimento->fuente_id);
                                                    }

                                                    // Asigna el costo del alimento si está disponible, de lo contrario, usa 0.
                                                    $set('costo_asignado', $costo ?? 0);
                                                }
                                            })
                                    ])->columns(1),
                                //-------------------------------------------------------------------------------------------------------
                                Fieldset::make('Costo de la Porción')
                                    ->schema([
                                        // 💾 Campo para ingresar la cantidad bruta en gramos
                                        TextInput::make('cantidad')
                                            ->helperText(new HtmlString('Los cálculos están basados únicamente en <strong>gramos (g)</strong> y <strong>mililitros (ml)</strong>.'))
                                            ->numeric()
                                            ->prefixIcon('heroicon-o-scale')
                                            ->suffix('g/ml')
                                            ->required()
                                            ->label('Cantidad Bruta')
                                            ->reactive()
                                            ->debounce(250)
                                            //🛠️Función encargada de realizar los cálculos para los campos derivados (cantidad neta, nutrientes por porción, costo total, etc.)
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                                //✅Obtener los valores del formulario y asignar valores predeterminados en caso de que no estén definidos.
                                                $parteComestible = $get('parte_comestible_porcentaje') ?? 100;
                                                //Análisis Proximal
                                                $db_proteina100g = $get('proteina_g') ?? 0;
                                                $db_lipidos100g = $get('lipidos_g') ?? 0;
                                                $db_carbohidratos100g = $get('carbohidratos_totales_g') ?? 0;
                                                $db_ceniza100g = $get('cenizas_g') ?? 0;
                                                //Minerales
                                                $db_calcio100g = $get('calcio_mg') ?? 0;
                                                $db_hierro100g = $get('hierro_mg') ?? 0;
                                                $db_sodio100g = $get('sodio_mg') ?? 0;
                                                $db_fosforo100g = $get('fosforo_mg') ?? 0;
                                                $db_yodo100g = $get('yodo_mg') ?? 0;
                                                $db_zinc100g = $get('zinc_mg') ?? 0;
                                                $db_magnesio100g = $get('magnesio_mg') ?? 0;
                                                $db_potasio100g = $get('potasio_mg') ?? 0;
                                                //Vitaminas
                                                $db_tiamina100g = $get('tiamina_mg') ?? 0;
                                                $db_riboflavina100g = $get('riboflavina_mg') ?? 0;
                                                $db_niacina100g = $get('niacina_mg') ?? 0;
                                                $db_folatos100g = $get('folatos_mcg') ?? 0;
                                                $db_vitaminab12100g = $get('vitamina_b12_mcg') ?? 0;
                                                $db_vitaminac100g = $get('vitamina_c_mg') ?? 0;
                                                $db_vitaminaa100g = $get('vitamina_a_er') ?? 0;
                                                //Grasas
                                                $db_saturada100g = $get('grasa_saturada_g') ?? 0;
                                                $db_monoinsaturada100g = $get('grasa_monoinsaturada_g') ?? 0;
                                                $db_poliinsaturada100g = $get('grasa_poliinsaturada_g') ?? 0;
                                                $db_colesterol100g = $get('colesterol_mg') ?? 0;

                                                $costo100g = $get('costo_asignado') ?? 0;
                                                //--------------------------------------------------------------------------------------------------

                                                //✅Definir funciones antes de usarlas para realizar los cálculos necesarios

                                                // Función para calcular la cantidad neta de un alimento según su porcentaje de parte comestible
                                                $calcularCantidadNeta = function ($cantidad, $parteComestible) {
                                                    return ($cantidad * $parteComestible) / 100;
                                                };

                                                // Función para calcular la cantidad de un nutriente en una porción, basada en la cantidad neta y su valor por 100g
                                                $calcularNutrientePorcion = function ($cantidadNeta, $nutrientePor100g) {
                                                    return ($cantidadNeta * $nutrientePor100g) / 100;
                                                };

                                                // Función para calcular las calorías
                                                $calcularCalorias = function ($totalPorcion, $factorCalorico) {
                                                    return $totalPorcion * $factorCalorico;
                                                };

                                                //--------------------------------------------------------------------------------------------------

                                                /*✅Calcular el costo total, multiplicando el costo por 100 gramos por la cantidad proporcional
                                                Si no hay costo asignado, el valor será 0*/
                                                $costoTotal = $costo100g ? ($state * ($costo100g / 1000)) : 0;
                                                //--------------------------------------------------------------------------------------------------

                                                //✅Calcular los valores nutricionales y la cantidad neta en base a las funciones definidas previamente

                                                // Calcular la cantidad neta de alimento según la cantidad y el porcentaje de la parte comestible
                                                $cantidadNeta = $calcularCantidadNeta($state, $parteComestible);

                                                // Calcular la cantidad de proteína en la porción, utilizando la cantidad neta y el valor de proteína por 100g
                                                $proteinaPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_proteina100g);
                                                $caloriasProteinaPorcionMat = $calcularCalorias($proteinaPorcionMat, 4);

                                                // Calcular la cantidad de lípidos en la porción, utilizando la cantidad neta y el valor de lípidos por 100g
                                                $lipidosPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_lipidos100g);
                                                $caloriasLipidosPorcionMat = $calcularCalorias($lipidosPorcionMat, 9);

                                                // Calcular la cantidad de carbohidratos en la porción, utilizando la cantidad neta y el valor de carbohidratos por 100g
                                                $carbohidratosPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_carbohidratos100g);
                                                $caloriasCarbohidratosPorcionMat = $calcularCalorias($carbohidratosPorcionMat, 4);

                                                //Calcular calorias
                                                $caloriasPorcionMat = $caloriasProteinaPorcionMat + $caloriasLipidosPorcionMat + $caloriasCarbohidratosPorcionMat;

                                                //Calcular ceniza
                                                $cenizaPorcionMat  = $calcularNutrientePorcion($cantidadNeta, $db_ceniza100g);

                                                //calcular calcio
                                                $calcioPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_calcio100g);
                                                $hierroPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_hierro100g);
                                                $sodioPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_sodio100g);
                                                $fosforoPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_fosforo100g);
                                                $yodoPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_yodo100g);
                                                $zincPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_zinc100g);
                                                $magnesioPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_magnesio100g);
                                                $potasioPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_potasio100g);

                                                //
                                                $tiaminaPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_tiamina100g);
                                                $riboflavinaPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_riboflavina100g);
                                                $niacinaPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_niacina100g);
                                                $folatosPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_folatos100g);
                                                $vitaminab12PorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_vitaminab12100g);
                                                $vitaminacPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_vitaminac100g);
                                                $vitaminaaPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_vitaminaa100g);

                                                //
                                                $saturadaPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_saturada100g);
                                                $monoinsaturadaPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_monoinsaturada100g);
                                                $poliinsaturadaPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_poliinsaturada100g);
                                                $colesterolPorcionMat = $calcularNutrientePorcion($cantidadNeta, $db_colesterol100g);


                                                //--------------------------------------------------------------------------------------------------

                                                //✅Asignar los valores calculados a los campos correspondientes en el formulario o modelo
                                                $set('cantidad_neta', $cantidadNeta);
                                                $set('calorias_porcion_input', $caloriasPorcionMat);
                                                $set('proteina_porcion_input', $proteinaPorcionMat);
                                                $set('calorias_proteina_porcion_input', $caloriasProteinaPorcionMat);
                                                $set('lipidos_porcion_input', $lipidosPorcionMat);
                                                $set('calorias_lipidos_porcion_input', $caloriasLipidosPorcionMat);
                                                $set('carbohidratos_porcion_input', $carbohidratosPorcionMat);
                                                $set('calorias_carbohidratos_porcion_input', $caloriasCarbohidratosPorcionMat);
                                                $set('ceniza_porcion_input', $cenizaPorcionMat);
                                                $set('costo', $costoTotal);
                                                $set('costo_asignado', $costo100g);

                                                $set('calcio_porcion_input', $calcioPorcionMat);
                                                $set('hierro_porcion_input', $hierroPorcionMat);
                                                $set('sodio_porcion_input', $sodioPorcionMat);
                                                $set('fosforo_porcion_input', $fosforoPorcionMat);
                                                $set('yodo_porcion_input', $yodoPorcionMat);
                                                $set('zinc_porcion_input', $zincPorcionMat);
                                                $set('magnesio_porcion_input', $magnesioPorcionMat);
                                                $set('potasio_porcion_input', $potasioPorcionMat);

                                                $set('tiamina_porcion_input', $tiaminaPorcionMat);
                                                $set('riboflavina_porcion_input', $riboflavinaPorcionMat);
                                                $set('niacina_porcion_input', $niacinaPorcionMat);
                                                $set('folatos_porcion_input', $folatosPorcionMat);
                                                $set('vitaminab12_porcion_input', $vitaminab12PorcionMat);
                                                $set('vitaminac_porcion_input', $vitaminacPorcionMat);
                                                $set('vitaminaa_porcion_input', $vitaminaaPorcionMat);

                                                $set('saturada_porcion_input', $saturadaPorcionMat);
                                                $set('monoinsaturada_porcion_input', $monoinsaturadaPorcionMat);
                                                $set('poliinsaturada_porcion_input', $poliinsaturadaPorcionMat);
                                                $set('colesterol_porcion_input', $colesterolPorcionMat);
                                                //--------------------------------------------------------------------------------------------------

                                            }),

                                        // 💾 Campo calculado que muestra la cantidad neta después de aplicar el porcentaje comestible
                                        TextInput::make('cantidad_neta')
                                            ->helperText(new HtmlString('La cantidad neta se calcula como <strong>cantidad bruta × % comestible</strong>.'))
                                            ->numeric()
                                            ->prefixIcon('heroicon-o-scale')
                                            ->suffix('g/ml')
                                            ->label('Cantidad Neta Comestible')
                                            ->default(0)
                                            ->readOnly(),

                                        // 💾 Campo calculado que muestra el costo en pesos colombianos (COP)
                                        TextInput::make('costo')
                                            ->helperText(new HtmlString('Costo = <strong>cantidad × precio</strong> del módulo de costos.'))
                                            ->label('Costo Estimado')
                                            ->prefixIcon('heroicon-o-currency-dollar')
                                            ->suffix('COP')
                                            ->readOnly()
                                            ->numeric()
                                            ->default(0),
                                    ])->columns(3),


                                //-------------------------------------------------------------------------------------------------------
                                Tabs::make('Tabs')
                                    ->tabs([
                                        //---------------------------------------------------------------------------------------------
                                        Tabs\Tab::make('')
                                            ->icon('heroicon-s-x-mark')
                                            ->schema(
                                                []
                                            ),
                                        //------------------------------------------------------------------------------------------------
                                        Tabs\Tab::make('Ficha Técnica del Alimento')
                                            ->icon('heroicon-o-document-text')
                                            ->schema([
                                                Grid::make(2)->schema(
                                                    collect([
                                                        'codigo' => 'Código del Alimento',
                                                        'nombre_alimento' => 'Nombre del Alimento',
                                                        'costo_asignado' => 'Costo Unitario (COP)',
                                                        'fuente_id' => 'Fuente de Información',
                                                        'parte_analizada' => 'Parte Analizada',
                                                        'parte_comestible_porcentaje' => 'Porcentaje Comestible (%)',
                                                    ])->map(fn($label, $name) => TextInput::make($name)->label($label)->disabled())->toArray()

                                                ),
                                            ]),
                                        //-------------------------------------------------------------------------------------------------    
                                        Tabs\Tab::make('Cálculo Nutricional')
                                            ->icon('heroicon-o-calculator')
                                            ->schema([
                                                Tabs::make('Tabs')
                                                    ->tabs([
                                                        Tabs\Tab::make('Análisis Proximal')
                                                            ->icon('heroicon-o-puzzle-piece')
                                                            ->schema([
                                                                Grid::make(3)->schema(
                                                                    collect([
                                                                        'calorias_porcion_input' => 'Calorías en la Porción (kcal)',
                                                                        'proteina_porcion_input' => 'Proteína en la Porción (g)',
                                                                        'calorias_proteina_porcion_input' => 'Calorías por Proteínas (kcal)',
                                                                        'lipidos_porcion_input' => 'Lípidos en la Porción (g)',
                                                                        'calorias_lipidos_porcion_input' => 'Calorías por Lípidos (kcal)',
                                                                        'carbohidratos_porcion_input' => 'Carbohidratos en la Porción (g)',
                                                                        'calorias_carbohidratos_porcion_input' => 'Calorías por Carbohidratos (kcal)',
                                                                        'ceniza_porcion_input' => 'Ceniza en la Porción (g)',
                                                                    ])->map(
                                                                        fn($label, $name) =>
                                                                        TextInput::make($name)
                                                                            ->label($label)
                                                                            ->readOnly()
                                                                            ->numeric()
                                                                            ->formatStateUsing(fn($state) => number_format((float) $state, 2))
                                                                    )->toArray()
                                                                ),
                                                            ]),
                                                        Tabs\Tab::make('Minerales')
                                                            ->icon('heroicon-o-cube-transparent')
                                                            ->schema([
                                                                Grid::make(4)->schema(
                                                                    collect([
                                                                        'calcio_porcion_input' => 'Calcio (mg)',
                                                                        'hierro_porcion_input' => 'Hierro (mg)',
                                                                        'sodio_porcion_input' => 'Sodio (mg)',
                                                                        'fosforo_porcion_input' => 'Fósforo (mg)',
                                                                        'yodo_porcion_input' => 'Yodo (mg)',
                                                                        'zinc_porcion_input' => 'Zinc (mg)',
                                                                        'magnesio_porcion_input' => 'Magnesio (mg)',
                                                                        'potasio_porcion_input' => 'Potasio (mg)',
                                                                    ])->map(
                                                                        fn($label, $name) =>
                                                                        TextInput::make($name)
                                                                            ->label($label)
                                                                            ->readOnly()
                                                                            ->numeric()
                                                                            ->formatStateUsing(fn($state) => number_format((float) $state, 2))
                                                                    )->toArray()
                                                                ),
                                                            ]),
                                                        Tabs\Tab::make('Vitaminas')
                                                            ->icon('heroicon-o-sun')
                                                            ->schema([
                                                                Grid::make(4)->schema(
                                                                    collect([
                                                                        'tiamina_porcion_input' => 'Tiamina (mg)',
                                                                        'riboflavina_porcion_input' => 'Riboflavina (mg)',
                                                                        'niacina_porcion_input' => 'Niacina (mg)',
                                                                        'folatos_porcion_input' => 'Folatos (mcg)',
                                                                        'vitaminab12_porcion_input' => 'B12 (mcg)',
                                                                        'vitaminac_porcion_input' => 'Vit C (mg)',
                                                                        'vitaminaa_porcion_input' => 'Vit A (ER)',
                                                                    ])->map(
                                                                        fn($label, $name) =>
                                                                        TextInput::make($name)
                                                                            ->label($label)
                                                                            ->readOnly()
                                                                            ->numeric()
                                                                            ->formatStateUsing(fn($state) => number_format((float) $state, 2))
                                                                    )->toArray()
                                                                )
                                                            ]),
                                                        Tabs\Tab::make('Ácidos Grasos y Colesterol')
                                                            ->icon('heroicon-o-fire')
                                                            ->schema([
                                                                Grid::make(3)->schema(
                                                                    collect([
                                                                        'saturada_porcion_input' => 'Grasa Saturada (g)',
                                                                        'monoinsaturada_porcion_input' => 'Monoinsaturada (g)',
                                                                        'poliinsaturada_porcion_input' => 'Poliinsaturada (g)',
                                                                        'colesterol_porcion_input' => 'Colesterol (mg)',
                                                                    ])->map(
                                                                        fn($label, $name) =>
                                                                        TextInput::make($name)
                                                                            ->label($label)
                                                                            ->readOnly()
                                                                            ->numeric()
                                                                            ->formatStateUsing(fn($state) => number_format((float) $state, 2))
                                                                    )->toArray()
                                                                )
                                                            ]),
                                                    ]),
                                            ]),
                                        //---------------------------------------------------------------------------------------    
                                        Tabs\Tab::make('Composición Nutricional')
                                            ->icon('heroicon-o-chart-pie')
                                            ->schema([
                                                Tabs::make('Tabs')
                                                    ->tabs([
                                                        Tabs\Tab::make('Análisis Proximal')
                                                            ->icon('heroicon-o-puzzle-piece')
                                                            ->schema([
                                                                Grid::make(3)->schema(
                                                                    collect([
                                                                        'humedad_g' => 'Humedad (g)',
                                                                        'energia_kcal' => 'Energía (Kcal)',
                                                                        'energia_kj' => 'Energía (Kj)',
                                                                        'proteina_g' => 'Proteína (g)',
                                                                        'lipidos_g' => 'Lípidos (g)',
                                                                        'carbohidratos_totales_g' => 'Carbohidratos Totales (g)',
                                                                        'carbohidratos_disponibles_g' => 'Carbohidratos Disponibles (g)',
                                                                        'fibra_dietaria_g' => 'Fibra Dietaria (g)',
                                                                        'cenizas_g' => 'Cenizas (g)',
                                                                    ])->map(fn($label, $name) => TextInput::make($name)->label($label)->disabled())->toArray()
                                                                ),
                                                            ]),
                                                        Tabs\Tab::make('Minerales')
                                                            ->icon('heroicon-o-cube-transparent')
                                                            ->schema([
                                                                Grid::make(3)->schema(
                                                                    collect([
                                                                        'calcio_mg' => 'Calcio (mg)',
                                                                        'hierro_mg' => 'Hierro (mg)',
                                                                        'sodio_mg' => 'Sodio (mg)',
                                                                        'fosforo_mg' => 'Fósforo (mg)',
                                                                        'yodo_mg' => 'Yodo (mg)',
                                                                        'zinc_mg' => 'Zinc (mg)',
                                                                        'magnesio_mg' => 'Magnesio (mg)',
                                                                        'potasio_mg' => 'Potasio (mg)',
                                                                    ])->map(fn($label, $name) => TextInput::make($name)->label($label)->disabled())->toArray()
                                                                ),
                                                            ]),
                                                        Tabs\Tab::make('Vitaminas')
                                                            ->icon('heroicon-o-sun')
                                                            ->schema([
                                                                Grid::make(3)->schema(
                                                                    collect([
                                                                        'tiamina_mg' => 'Tiamina (mg)',
                                                                        'riboflavina_mg' => 'Riboflavina (mg)',
                                                                        'niacina_mg' => 'Niacina (mg)',
                                                                        'folatos_mcg' => 'Folatos (mcg)',
                                                                        'vitamina_b12_mcg' => 'Vitamina B12 (mcg)',
                                                                        'vitamina_c_mg' => 'Vitamina C (mg)',
                                                                        'vitamina_a_er' => 'Vitamina A (ER)',
                                                                    ])->map(fn($label, $name) => TextInput::make($name)->label($label)->disabled())->toArray()
                                                                ),
                                                            ]),
                                                        Tabs\Tab::make('Ácidos Grasos y Colesterol')
                                                            ->icon('heroicon-o-fire')
                                                            ->schema([
                                                                Grid::make(3)->schema(
                                                                    collect([
                                                                        'grasa_saturada_g' => 'Grasa Saturada (g)',
                                                                        'grasa_monoinsaturada_g' => 'Grasa Monoinsaturada (g)',
                                                                        'grasa_poliinsaturada_g' => 'Grasa Poliinsaturada (g)',
                                                                        'colesterol_mg' => 'Colesterol (mg)',
                                                                    ])->map(fn($label, $name) => TextInput::make($name)->label($label)->disabled())->toArray()
                                                                ),
                                                            ]),
                                                    ])
                                            ])
                                    ])
                            ])
                            ->relationship('items')
                            ->addActionLabel('Agregar Porción')
                            ->reorderable(false)
                            ->collapsible()
                            ->live()
                            ->itemLabel(fn(array $state): ?string => trim(implode(' | ', array_filter([
                                ($state['alimento_codigo'] ?? '') . ' - ' . ($state['nombre_alimento'] ?? ''),
                                !empty($state['cantidad']) ? "Cantidad: {$state['cantidad']}(g/ml)*" : null,
                                !empty($state['costo']) ? "Costo: $ {$state['costo']} COP" : null,
                            ]))))
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $items = $get('preparacion_alimentos') ?? [];

                                $totales = [
                                    'calorias_total' => 0,
                                    'proteina_total' => 0,
                                    'lipidos_total' => 0,
                                    'carbohidratos_total' => 0,
                                    'calcio_total' => 0,
                                    'hierro_total' => 0,
                                    'sodio_total' => 0,
                                    'fosforo_total' => 0,
                                    'yodo_total' => 0,
                                    'zinc_total' => 0,
                                    'magnesio_total' => 0,
                                    'potasio_total' => 0,
                                    'tiamina_total' => 0,
                                    'riboflavina_total' => 0,
                                    'niacina_total' => 0,
                                    'folatos_total' => 0,
                                    'vitaminab12_total' => 0,
                                    'vitaminac_total' => 0,
                                    'vitaminaa_total' => 0,
                                    'saturada_total' => 0,
                                    'monoinsaturada_total' => 0,
                                    'poliinsaturada_total' => 0,
                                    'colesterol_total' => 0,
                                    'total_costo' => 0,
                                ];

                                foreach ($items as $item) {
                                    $totales['calorias_total'] += $item['calorias_porcion_input'] ?? 0;
                                    $totales['proteina_total'] += $item['proteina_porcion_input'] ?? 0;
                                    $totales['lipidos_total'] += $item['lipidos_porcion_input'] ?? 0;
                                    $totales['carbohidratos_total'] += $item['carbohidratos_porcion_input'] ?? 0;
                                    $totales['calcio_total'] += $item['calcio_porcion_input'] ?? 0;
                                    $totales['hierro_total'] += $item['hierro_porcion_input'] ?? 0;
                                    $totales['sodio_total'] += $item['sodio_porcion_input'] ?? 0;
                                    $totales['fosforo_total'] += $item['fosforo_porcion_input'] ?? 0;
                                    $totales['yodo_total'] += $item['yodo_porcion_input'] ?? 0;
                                    $totales['zinc_total'] += $item['zinc_porcion_input'] ?? 0;
                                    $totales['magnesio_total'] += $item['magnesio_porcion_input'] ?? 0;
                                    $totales['potasio_total'] += $item['potasio_porcion_input'] ?? 0;
                                    $totales['tiamina_total'] += $item['tiamina_porcion_input'] ?? 0;
                                    $totales['riboflavina_total'] += $item['riboflavina_porcion_input'] ?? 0;
                                    $totales['niacina_total'] += $item['niacina_porcion_input'] ?? 0;
                                    $totales['folatos_total'] += $item['folatos_porcion_input'] ?? 0;
                                    $totales['vitaminab12_total'] += $item['vitaminab12_porcion_input'] ?? 0;
                                    $totales['vitaminac_total'] += $item['vitaminac_porcion_input'] ?? 0;
                                    $totales['vitaminaa_total'] += $item['vitaminaa_porcion_input'] ?? 0;
                                    $totales['saturada_total'] += $item['saturada_porcion_input'] ?? 0;
                                    $totales['monoinsaturada_total'] += $item['monoinsaturada_porcion_input'] ?? 0;
                                    $totales['poliinsaturada_total'] += $item['poliinsaturada_porcion_input'] ?? 0;
                                    $totales['colesterol_total'] += $item['colesterol_porcion_input'] ?? 0;
                                    $totales['total_costo'] += $item['costo'] ?? 0;
                                }

                                foreach ($totales as $campo => $valor) {
                                    $set($campo, $valor);
                                }
                            }),
                    ]),
                Section::make('Información Nutricional')
                    ->icon('heroicon-o-document-chart-bar')
                    ->description('Este análisis detalla el contenido de macronutrientes, minerales, vitaminas y grasas en una porción de alimento, junto con su aporte calórico total.')
                    ->schema([
                        Tabs::make('Tabs')
                            ->lazy() // 🔹 Carga las pestañas solo cuando se abren, mejorando el rendimiento
                            ->tabs([
                                Tabs\Tab::make('Macronutrientes')
                                    ->icon('heroicon-o-puzzle-piece')
                                    ->schema([
                                        Grid::make(3)->schema(
                                            collect([
                                                'calorias_total' => 'Calorías por porción',
                                                'proteina_total' => 'Proteína (g)',
                                                'lipidos_total' => 'Lípidos (g)',
                                                'carbohidratos_total' => 'Carbohidratos (g)',
                                            ])->map(
                                                fn($label, $name) =>
                                                TextInput::make($name)
                                                    ->label($label)
                                                    ->numeric()
                                                    ->default(0)
                                                    ->readOnly()
                                                    ->formatStateUsing(fn($state) => number_format((float) $state, 2))
                                            )->toArray()
                                        ),

                                        Placeholder::make('')
                                            ->label('📌 Notas')
                                            ->content('(g): Gramos, unidad de medida para la cantidad de macronutrientes.  
                                                       (Cal): Calorías, unidad de medida de la energía proporcionada por cada macronutriente.')
                                            ->columnSpanFull(),
                                    ]),

                                Tabs\Tab::make('Minerales')
                                    ->icon('heroicon-o-cube-transparent')
                                    ->schema([
                                        Grid::make(3)->schema(
                                            collect([
                                                'calcio_total' => 'Calcio (mg)',
                                                'hierro_total' => 'Hierro (mg)',
                                                'sodio_total' => 'Sodio (mg)',
                                                'fosforo_total' => 'Fósforo (mg)',
                                                'yodo_total' => 'Yodo (mg)',
                                                'zinc_total' => 'Zinc (mg)',
                                                'magnesio_total' => 'Magnesio (mg)',
                                                'potasio_total' => 'Potasio (mg)',
                                            ])->map(
                                                fn($label, $name) =>
                                                TextInput::make($name)
                                                    ->label($label)
                                                    ->readOnly()
                                                    ->numeric()
                                                    ->default(0)
                                                    ->formatStateUsing(fn($state) => number_format((float) $state, 2))
                                            )->toArray()
                                        ),

                                        Placeholder::make('')
                                            ->label('📌 Notas')
                                            ->content('(mg): Miligramos, unidad de medida utilizada para la cantidad de minerales y vitaminas.')
                                            ->columnSpanFull(),
                                    ]),
                                Tabs\Tab::make('Vitaminas')
                                    ->icon('heroicon-o-sun')
                                    ->schema([
                                        Grid::make(3)->schema(
                                            collect([
                                                'tiamina_total' => 'Tiamina (mg)',
                                                'riboflavina_total' => 'Riboflavina (mg)',
                                                'niacina_total' => 'Niacina (mg)',
                                                'folatos_total' => 'Folatos (mcg)',
                                                'vitaminab12_total' => 'Vitamina B12 (mcg)',
                                                'vitaminac_total' => 'Vitamina C (mg)',
                                                'vitaminaa_total' => 'Vitamina A (ER)',
                                            ])->map(
                                                fn($label, $name) =>
                                                TextInput::make($name)
                                                    ->label($label)
                                                    ->readOnly()
                                                    ->numeric()
                                                    ->default(0)
                                                    ->formatStateUsing(fn($state) => number_format((float) $state, 2))
                                            )->toArray()
                                        ),


                                        Placeholder::make('')
                                            ->label('📌 Notas')
                                            ->content('(mg): Miligramos, unidad de medida utilizada para la cantidad de minerales y vitaminas.  
                                                       (mcg): Microgramos, una unidad menor que el miligramo.  
                                                       (ER): Equivalentes de retinol, medida para expresar la actividad de la vitamina A.')
                                            ->columnSpanFull(),
                                    ]),

                                Tabs\Tab::make('Ácidos Grasos y Colesterol')
                                    ->icon('heroicon-o-fire')
                                    ->schema([
                                        Grid::make(3)->schema(
                                            collect([
                                                'saturada_total' => 'Grasa total (g)',
                                                'monoinsaturada_total' => 'Grasa Monoinsaturada (g)',
                                                'poliinsaturada_total' => 'Grasa Poliinsaturada (g)',
                                                'colesterol_total' => 'Colesterol (mg)',
                                            ])->map(
                                                fn($label, $name) =>
                                                TextInput::make($name)
                                                    ->label($label)
                                                    ->readOnly()
                                                    ->numeric()
                                                    ->default(0)
                                                    ->formatStateUsing(fn($state) => number_format((float) $state, 2))
                                            )->toArray()
                                        ),


                                        Placeholder::make('')
                                            ->label('📌 Notas')
                                            ->content('(mg): Miligramos, unidad de medida utilizada para la cantidad de minerales y vitaminas.  
                                                       (Cal): Calorías, unidad de medida de la energía proporcionada por los macronutrientes.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        TextInput::make('total_costo')
                            ->label('Costo Total de la Receta')
                            ->readOnly()
                            ->numeric()
                            ->default(0)
                            ->debounce(500) // 🔹 Reduce recálculo innecesario en operaciones costosas
                            ->prefixIcon('heroicon-o-currency-dollar')
                            ->suffix('COP')
                    ])


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TextColumn::make('codigo_preparacion')
                        ->label('Código de Preparación')
                        ->searchable(),

                    TextColumn::make('nombre')
                        ->label('Nombre de Preparación')
                        ->searchable(),

                    TextColumn::make('total_costo')
                        ->label('Costo de Preparación')
                        ->formatStateUsing(fn($state) => '$ ' . number_format($state, 0, ',', '.') . ' COP'),

                ]),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([])
            ->actions([
                ViewAction::make()
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->color('secondary'),      // Color secundario (puedes usar otro si quieres)
                DeleteAction::make()
                    ->label('Eliminar')
                    ->icon('heroicon-o-trash')  // Icono de "basura" para eliminar
                    ->color('danger'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListPreparacions::route('/'),
            'create' => Pages\CreatePreparacion::route('/create'),
            //'edit' => Pages\EditPreparacion::route('/{record}/edit'),
            'view' => Pages\ViewPreparacion::route('/{record}'),
        ];
    }
}
