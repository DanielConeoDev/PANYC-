<?php

namespace App\Filament\Resources\PreparacionResource\Pages;

use App\Filament\Resources\PreparacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreparacion extends EditRecord
{
    protected static string $resource = PreparacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
