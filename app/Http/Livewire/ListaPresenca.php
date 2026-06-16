<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sala;


class ListaPresenca extends Component
{

    public $salaId;

    public function render()
    {
        $sala = Sala::with('alunosPresentes')->find($this->salaId);

        return view('livewire.lista-presenca', ['alunos' => $sala ? $sala->alunosPresentes : []]);
    }
}
