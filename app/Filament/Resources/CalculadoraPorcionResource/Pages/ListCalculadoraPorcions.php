<?php

namespace App\Filament\Resources\CalculadoraPorcionResource\Pages;

use App\Filament\Resources\CalculadoraPorcionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCalculadoraPorcions extends ListRecords
{
    protected static string $resource = CalculadoraPorcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
