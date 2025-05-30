<?php

namespace App\Filament\Resources\FuenteResource\Pages;

use App\Filament\Resources\FuenteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFuente extends EditRecord
{
    protected static string $resource = FuenteResource::class;

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
