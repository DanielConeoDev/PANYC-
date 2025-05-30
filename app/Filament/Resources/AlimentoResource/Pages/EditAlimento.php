<?php

namespace App\Filament\Resources\AlimentoResource\Pages;

use App\Filament\Resources\AlimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlimento extends EditRecord
{
    protected static string $resource = AlimentoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
