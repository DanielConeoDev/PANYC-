<?php

namespace App\Filament\Resources\CostoResource\Pages;

use App\Filament\Resources\CostoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCosto extends CreateRecord
{
    protected static string $resource = CostoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
