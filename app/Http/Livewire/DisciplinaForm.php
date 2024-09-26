<?php

namespace App\Http\Livewire;

use App\Models\Disciplina;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;

class DisciplinaForm extends Component
{
    use WithPagination;

    public $disciplinaId, $name, $modalTitle; 
    public $inserir = true;
    public $filtro = '';
    public $disciplinaExcluir;

    public $filtroTemp = ''; 
    public function aplicarFiltro()
    {
        $this->filtro = $this->filtroTemp; 
    }
    
    public function render()
    {
        $disciplinas = Disciplina::where('school_id', Auth::user()->school_id)
            ->where('name', 'like', '%' . $this->filtro . '%')
            ->paginate(20);

        return view('livewire.disciplina-form', ['disciplinas' => $disciplinas]);
    }

    public function novo()
    {
        $this->reset();
        $this->modalTitle = "Adicionar Disciplina";
        $this->inserir = true;
        $this->dispatchBrowserEvent('openDisciplinaModal');
    }

    public function editar($id)
    {
        $disciplina = Disciplina::find($id);
        $this->disciplinaId = $disciplina->id; 
        $this->name = $disciplina->name;
        $this->modalTitle = "Editar Disciplina";
        $this->inserir = false;
        $this->dispatchBrowserEvent('openDisciplinaModal');
    }

    public function salvar()
    {
        $data = [
            'name' => $this->name,
            'disciplinaId' => $this->disciplinaId 
        ];
    
        $validation = Validator::make($data, [
            'name' => 'disciplina_ja_existe', 
        ])->validate();
    
        if ($this->inserir) {
            $data['school_id'] = Auth::user()->school_id;
            Disciplina::create($data);
        } else {
            $disciplina = Disciplina::find($this->disciplinaId); 
            $disciplina->update($data);
        }
    
        $this->dispatchBrowserEvent('closeDisciplinaModal');
        $this->reset();
    }
    

    public function confirmarExcluir($id)
    {
        $disciplina = Disciplina::find($id);
        
        $this->disciplinaId = $id;
        $this->disciplinaExcluir = $disciplina->name;
    
        $this->dispatchBrowserEvent('openConfirmarExcluirModal');
    }
    
    public function excluir()
    {
        $disciplina = Disciplina::find($this->disciplinaId);
        
        if ($disciplina) {
            $disciplina->delete();
        }

        $this->dispatchBrowserEvent('closeConfirmarExcluirModal');
        $this->reset();
    }
    
}



