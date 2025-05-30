<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Costo;

class CostosAlimentoList extends Component
{
    public $alimento_id;
    public $costos;

    public function mount($alimento_id)
    {
        $this->alimento_id = $alimento_id;
        $this->costos = Costo::where('alimento_id', $alimento_id)->get();
    }

    public function render()
    {
        return view('livewire.costos-alimento-list');
    }
}
