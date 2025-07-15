<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MensajeContactoResource\Pages;
use App\Models\MensajeContacto;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MensajeContactoResource extends Resource
{
    protected static ?string $model = MensajeContacto::class;

    // 🔹 Nombre que se muestra en el menú de navegación
    protected static ?string $navigationLabel = 'Mensajes de contacto';

    // 🔹 Grupo de navegación donde se ubicará el recurso
    protected static ?string $navigationGroup = 'Comunicación';

    // 🔹 Ícono para el menú (puedes cambiarlo por otro Heroicon o Lucide)
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Correo')->searchable(),
                Tables\Columns\TextColumn::make('subject')->label('Asunto')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Fecha de envío')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([]) // sin acciones individuales
            ->bulkActions([]); // sin acciones masivas
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMensajeContactos::route('/'),
        ];
    }

    // 🔒 Desactiva el botón "Crear"
    public static function canCreate(): bool
    {
        return false;
    }
}
