<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\DAO\MuralDAO;
use App\DAO\MuralPainelDAO;
use App\DAO\DisciplinaDAO;

use Illuminate\Support\Facades\Auth;

class MuralEdit extends Component
{
    public $muralId;
    public $mural;

    public $paineis;

    public $startPainelId;

    public function mount()
    {
        $this->mural = MuralDAO::find($this->muralId);
        $this->nameScene = $this->mural->name;

        $this->disciplinas = DisciplinaDAO::getDisciplinasDoProfessor(Auth::user()->id);        
        $this->disciplinaSelecionada = $this->mural->disciplina_id;

        $this->startPainelId = $this->mural->start_painel_id;

        $this->paineis = MuralPainelDAO::getByMuralId($this->muralId);
        foreach ($this->paineis as $painel) {
            $painel->panel = json_decode($painel->panel,true);
        }

    
    }
}