<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\AnoLetivo;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use App\Models\AlunoTurma;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\User;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;

class MatriculaNovoAluno extends Component
{

    public $turmas;
    public $alunos;
    public $nome;
    public $anoletivo;
    public $turmaKey;
    public $existe;
    public $Nometurma;
    public $turma;
    public $aluno_id;
    public $alterar;
    public $turmaAluno;
    public $habilitar = true;

    protected $rules = [
        'nome' => 'required|min:6'
    ];
    public function updatedTurma($propertyTurma_id)
    {

        $this->turmaKey = $propertyTurma_id;
        if ($this->existe) {
            $this->alterar = true;
        }
    }
    public function render()
    {
        $this->alunos = DB::table('users')
            ->select('users.name', 'users.id')
            ->where([
                ['type', '=', 'student'],
                ['school_id', '=', Auth::user()->school_id]
            ])
            ->get();
        $this->turmas = DB::table('turmas')
            ->where([
                ['ano_id', '=', $this->anoletivo->id],
                ['school_id', '=', Auth::user()->school_id],
                ['id', '!=', $this->turmaAluno]
            ])
            ->get();
        //'nome', 'turma_modelo_id', 'school_id', 'ano_id';
        //turmaficticia = {'nome': 'Sem alteração', 'id' : 0};
        $this->turmas->push((object)['nome' => 'Sem alteração', 'id' => 0]);

        if (!empty($this->turmaKey)) {
            $this->turma = $this->turmaKey;
            $this->turmaKey = "";
        } else {
            if (empty($this->turma))
                $this->turma = $this->turmas->where('id', 0)->first()->id;
        }

        return view('livewire.matricula-novo-aluno');
    }

    public function updatedNome($propertyName)
    {

        $aluno = User::where('name', 'like', '%' . $this->nome . '%')->first();
        $this->aluno_id = $aluno->id;
        $this->validateOnly($propertyName);

        if ($this->nome != "") {
            $this->habilitar = false;
            $query = DB::table('turmas as t')
                ->join('anos_letivos as ano', 'ano.id', '=', 't.ano_id')
                ->join('alunos_turmas as at', 'at.turma_id', '=', 't.id')
                ->where([
                    ['at.aluno_id', '=', $this->aluno_id],
                    ['ano.id', '=', $this->anoletivo->id]
                ]);

            $this->existe = $query->exists();
            if ($this->existe) {
                $this->Nometurma = $query->first()->nome;
                $this->turmaAluno = $query->first()->turma_id;
            } else {
                $this->turmaAluno = "";
            }
        } else {
            $this->habilitar = true;
        }
    }

    public function closeModal()
    {
        $this->alterar = false;
        $this->turma = null;
        $this->turmaAluno = "";
    }

    public function updateAluno()
    {

        $this->alterar = false;
    }
}
