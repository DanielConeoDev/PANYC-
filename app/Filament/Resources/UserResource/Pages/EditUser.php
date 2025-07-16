<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\MensajeUsuario;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function afterSave(): void
    {
        $mensaje = $this->form->getState()['mensaje_al_usuario'] ?? null;

        if ($mensaje) {
            $this->record->mensajeEnviado()->updateOrCreate([], [
                'mensaje' => $mensaje,
                'fecha_envio' => now(),
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
