<?php

use App\Livewire\Web;
use Illuminate\Support\Facades\Route;
use App\Filament\Resources\CalculadoraPorcionesResource\Pages\ViewReceta;



/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', Web::class);
