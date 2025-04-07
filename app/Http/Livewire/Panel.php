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

    public function updateText()
    {
        $painelDAO = new PainelDAO();

        $json = json_decode($this->painel->panel);
        $json->txt = $this->texto;

        $painelDAO->updateById($this->painel->id,[
            'panel'=> json_encode($json)
        ]);
    }

    public function render()
    {
        return view('livewire.panel');
    }
}
