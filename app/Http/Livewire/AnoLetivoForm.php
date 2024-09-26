<?php

namespace App\Http\Livewire;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class AnoLetivoForm extends Component
{
    public $id_anoletivo, $name;

    public $filtro = null;

    public $allanoletivo;

    public $modalTitulo;

    public $inserir;

    public $anoLetivoExcluir;

    public $filtroTemp = ''; 
    public function aplicarFiltro()
    {
        $this->filtro = $this->filtroTemp; 
    }

    public function render()
    {
        $where = AnoLetivo::where('school_id', Auth::user()->school_id);

        $anoLetivo = $this->filtro;

        if ($anoLetivo) {
            $r = '%' . $anoLetivo . '%';
            $where = $where->where('name', 'like', $r);
        }
        $this->allanoletivo = $where->get();
        $anosletivos = $where->paginate(10);

        return view('livewire.ano-letivo-form', ['anosletivos'=>$anosletivos]);
    }

    public function novo() {
        $this->reset();
        $this->resetValidation();

        $this->id_anoletivo = 0;
        $this->name = "";
        $this->modalTitulo = "Novo ano letivo";
        $this->inserir = true;

        $this->dispatchBrowserEvent('openAnoLetivoModal');
    }

    public function editar($id_al) {
        $this->reset();
        $this->resetValidation();

        $anoletivo = AnoLetivo::find($id_al);

        $this->id_anoletivo = $anoletivo->id;
        $this->name = $anoletivo->name;
        $this->modalTitulo = "Editar ano letivo";
        $this->inserir = false;

        $this->dispatchBrowserEvent('openAnoLetivoModal');
    }

    public function salvar() {

        $data = [
            'id' => $this->id_anoletivo,
            'name' => $this->name
        ];
        
         $validation = Validator::make(
            $data,
            $rules = [
                'name' => 'ano_letivo_ja_existe'
            ]
        )->validate();

        $data['school_id'] = Auth::user()->school_id;

        if ($this->inserir) {

            $data['bool_atual'] = false;
            $anoletivo = AnoLetivo::create($data);
        } else {

            $anoletivo = AnoLetivo::find($data['id']);
            $anoletivo->update($data);
        }

        $this->dispatchBrowserEvent('closeAnoLetivoModal');
    }

    public function confirmarExcluir($id_al) {

        $anoletivo = AnoLetivo::find($id_al);
        $this->id_anoletivo = $id_al;
        $this->anoLetivoExcluir = $anoletivo->name;     
        
        $this->dispatchBrowserEvent('openConfirmarExcluirModal');
    }

    public function excluir() {
        $anoletivo = AnoLetivo::find($this->id_anoletivo);
        $anoletivo->delete();

        $this->dispatchBrowserEvent('closeConfirmarExcluirModal');
    }

}
