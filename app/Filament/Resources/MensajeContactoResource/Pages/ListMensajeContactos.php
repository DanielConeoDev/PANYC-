<?php

namespace App\Filament\Resources\MensajeContactoResource\Pages;

use App\Filament\Resources\MensajeContactoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMensajeContactos extends ListRecords
{
    protected static string $resource = MensajeContactoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
