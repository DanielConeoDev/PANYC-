<?php

namespace App\Filament\Resources\AlimentoResource\Pages;

use App\Filament\Resources\AlimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAlimento extends CreateRecord
{
    protected static string $resource = AlimentoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
