<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DAO\PainelDAO;

class Panel extends Component
{
    public $painel;
    public $texto;

    public function mount($painel)
    {
        $this->painel = $painel;
        $this->texto = $painel->txt;
    }

    public function update()
    {
        // Aqui você chama o DAO ou Model para salvar a mudança
        dd("Painel ID: " . $this->painel->id . " | Texto: " . $this->texto);
    }

    public function render()
    {
        return view('livewire.panel');
    }
}
