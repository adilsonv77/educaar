<?php

namespace App\Http\Livewire;

use App\Models\Mural;
use App\Models\MuralPainel;
use App\DAO\MuralDAO;
use App\DAO\DisciplinaDAO;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;

class MuralListing extends Component
{
    use WithPagination;

    public $nome;
    public $disciplina_id;

    public $muralId;
    public $muralExcluir;

    public $filtro = '';
    public $filtroTemp = ''; 
    public function aplicarFiltro()
    {
        $this->filtro = $this->filtroTemp; 
    }

    public function render()
    {
        // TODO: na verdade nao vai mostrar todos, pois isso tem que mostrar de um professor ou do autor, ou algo assim
        $murais = MuralDAO::getAll();
        $disciplinas = DisciplinaDAO::getDisciplinasDoProfessor(Auth::user()->id);

        return view('livewire.mural-listing', ['murais' => $murais, 'disciplinas' => $disciplinas]);
    }

    public function novo()
    {
        $this->reset();

        $this->dispatchBrowserEvent('openMuralModal');
    }
    
 
    public function salvar()
    {
        $data = [
            'name' => $this->nome,
            'author_id'  => Auth::user()->id,
            'muralId' => 0, // para usar no validator
            'disciplina_id' => $this->disciplina_id 
        ];

        $validation = Validator::make($data, [
            'name' => 'mural_ja_existe', 
        ])->validate();

        MuralDAO::create($data);

        $this->dispatchBrowserEvent('closeMuralModal');

    }

    public function confirmarExcluir($id)
    {
        $mural = Mural::find($id);
        
        $this->muralId = $id;
        $this->muralExcluir = $mural->name;
    
        $this->dispatchBrowserEvent('openConfirmarExcluirModal');
    }

    public function excluir()
    {
        $mural = Mural::find($this->muralId);
        
        if ($mural) {
            $mural->delete();
        }

        $this->dispatchBrowserEvent('closeConfirmarExcluirModal');
        $this->reset();
    }


}