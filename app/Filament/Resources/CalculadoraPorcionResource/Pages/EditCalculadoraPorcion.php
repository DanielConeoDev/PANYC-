<?php

namespace App\Filament\Resources\CalculadoraPorcionResource\Pages;

use App\Filament\Resources\CalculadoraPorcionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCalculadoraPorcion extends EditRecord
{
    protected static string $resource = CalculadoraPorcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
