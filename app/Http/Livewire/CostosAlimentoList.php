<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Costo;  // Ajusta según tu modelo

class CostosAlimentoList extends Component
{
    public $alimento_id;
    public $costos;

    public function mount($alimento_id)
    {
        $this->alimento_id = $alimento_id;
    }

    public function render()
    {
        // Consulta los costos del alimento ordenados por fecha (creado más reciente primero)
        $this->costos = Costo::where('alimento_id', $this->alimento_id)
                             ->orderBy('created_at', 'desc')
                             ->get();

        return view('livewire.costos-alimento-list', [
            'costos' => $this->costos,
            'alimento_id' => $this->alimento_id,
        ]);
    }
}
