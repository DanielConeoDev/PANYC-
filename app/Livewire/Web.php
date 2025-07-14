<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Alimento;
use App\Models\Preparacion;
use App\Models\User;
use App\Models\CalculadoraPorciones;

class Web extends Component
{
    public $theme = 'light';

    public function render()
    {
        return view('livewire.web', [
            'totalAlimentos' => Alimento::count(),
            'totalPreparaciones' => Preparacion::count(),
            'totalPorciones' => CalculadoraPorciones::count(),
            'totalUsuarios' => User::count(),
        ]);
    }
}
