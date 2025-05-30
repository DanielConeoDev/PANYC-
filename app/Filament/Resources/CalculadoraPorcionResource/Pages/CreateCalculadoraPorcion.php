<?php

namespace App\Filament\Resources\CalculadoraPorcionResource\Pages;

use App\Models\Preparacion;
use App\Models\CalculadoraPorcion;
use App\Models\CalculadoraPorciones;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CalculadoraPorcionResource;

class CreateCalculadoraPorcion extends CreateRecord
{
    protected static string $resource = CalculadoraPorcionResource::class;

}
