<?php

namespace App\Filament\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\View;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{

    public function form(Form $form): Form
    {
        return $form->schema([
            //View::make('filament.login-logo'),

            TextInput::make('email')
                ->label('Correo electrónico')
                ->email()
                ->required()
                ->autofocus(),

            TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->autocomplete('current-password')
                ->required(),

            Checkbox::make('remember')
                ->label('Recordarme'),

            View::make('filament.login-link'),
        ]);
    }
}
