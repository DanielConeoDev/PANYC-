<?php

namespace App\Filament\Resources\PreparacionResource\Pages;

use App\Filament\Resources\PreparacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreparacions extends ListRecords
{
    protected static string $resource = PreparacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
