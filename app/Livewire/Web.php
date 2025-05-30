<?php

namespace App\Livewire;

use Livewire\Component;

class Web extends Component
{
    public $theme = 'light';
    public function render()
    {
        return view('livewire.web');
    }
}
