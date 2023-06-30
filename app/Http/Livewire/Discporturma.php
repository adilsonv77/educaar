<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Discporturma extends Component
{

    public $turmas;

    public $turma;

    public $disciplinas = [];

    public $disciplina;

    public $disciplinaKey;

    public function updatedDisciplina($disc)
    {
        $this->disciplinaKey = $disc;
    }

    public function render()
    {

        $this->turmas = DB::table('turmas_modelos')
            ->select('turmas_modelos.id as tid', 'turmas_modelos.serie as tnome')
            ->where("school_id", "=", Auth::user()->school_id)
            ->get();

        $turmakey = $this->turma;
        if (empty($turmakey)) {
            $turmakey = $this->turmas->first()->tid;
        }

        $this->disciplinas = DB::table('disciplinas_turmas_modelos')
            ->select("disciplinas_turmas_modelos.disciplina_id as did", "disciplinas.name as dnome")
            ->join("disciplinas", "disciplinas.id", "=", "disciplinas_turmas_modelos.disciplina_id")
            ->where("turma_modelo_id", "=", $turmakey)
            ->get();

        if (!empty($this->disciplinaKey)) {
            $this->disciplina = $this->disciplinaKey;
            $this->disciplinaKey = "";
        } else {
            $this->disciplina = $this->disciplinas->first()->did;
        }

        return view('livewire.discporturma');
    }
}
