<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\MensajeUsuario;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $mensaje = $this->form->getState()['mensaje_al_usuario'] ?? null;

        if ($mensaje) {
            $this->record->mensajeEnviado()->create([
                'mensaje' => $mensaje,
                'fecha_envio' => now(),
            ]);
        }
    }
}
