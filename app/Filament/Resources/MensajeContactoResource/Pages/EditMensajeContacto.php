<?php

namespace App\Filament\Resources\MensajeContactoResource\Pages;

use App\Filament\Resources\MensajeContactoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMensajeContacto extends EditRecord
{
    protected static string $resource = MensajeContactoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
