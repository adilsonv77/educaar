<?php

namespace App\Http\Controllers;

use App\Models\TurmaModelo;
use App\Models\DisciplinaTurmaModelo;
use App\Models\AnoLetivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TurmasModelosController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->titulo;

        $where = DB::table('turmas_modelos')
            ->where('school_id', Auth::user()->school_id)
            ->addSelect([
                'qntTurmas' => DB::table('turmas')
                    ->selectRaw('count(*)')
                    ->whereColumn([['turmas_modelos.id', '=', 'turma_modelo_id']])
                    ->where('school_id', Auth::user()->school_id)
            ])
            ->addSelect([
                'conteudos' => DB::table('contents as c')
                    ->selectRaw('count(c.id)')
                    ->join('disciplinas_turmas_modelos as dtm', function ($join) {
                        $join->on('dtm.disciplina_id', '=', 'c.disciplina_id');
                        $join->on('dtm.turma_modelo_id', '=', 'c.turma_id');
                    })
                    ->whereColumn('dtm.turma_modelo_id', '=', 'turmas_modelos.id')
            ]);

        if ($filtro) {
            $where = $where->where("serie", "like", '%' . $filtro . '%');
        }

        $turmas = $where->paginate(20);

        return view('pages.turmasModelos.index', compact('turmas'));
    }

    public function create()
    {
        $acao = 'insert';
        $anosletivos = AnoLetivo::where('school_id', Auth::user()->school_id)->get();
        $disciplinas = DB::table('disciplinas')->get();

        $disciplinas_turma = $disciplinas->map(function ($d) {
            return [
                'id' => $d->id,
                'name' => $d->name,
                'selected' => false
            ];
        });

        $params = [
            'titulo' => 'Adicionar Turma Modelo',
            'acao' => $acao,
            'id' => 0,
            'serie' => '',
            'anosletivos' => $anosletivos,
            'disciplinas' => $disciplinas_turma,
            'disciplinas_turmas' => []
        ];

        return view('pages.turmasModelos.register', $params);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $discs = $request->duallistbox_disc ?? [];

        // Validação e verificação do nome da turma (agora separado)
        $nome = $data['serie']; // Nome da turma
        $exists = TurmaModelo::where('serie', $nome)
                             ->where('school_id', Auth::user()->school_id)
                             ->exists();

        if ($exists) {
            return back()->withErrors(['serie' => 'Este nome de turma já está cadastrado.']);
        }

        // Adiciona o school_id ao dados
        $data['school_id'] = Auth::user()->school_id;

        // Criação ou atualização do modelo de turma
        if ($data['acao'] == 'insert') {
            $turma = TurmaModelo::create($data);
        } else {
            $turma = TurmaModelo::find($data['id']);
            $turma->update($data);
            DB::table('disciplinas_turmas_modelos')->where('turma_modelo_id', $turma->id)->delete();
        }

        // Adicionar as disciplinas à tabela `disciplinas_turmas_modelos`
        foreach ($discs as $disc) {
            DisciplinaTurmaModelo::create([
                'disciplina_id' => $disc,
                'turma_modelo_id' => $turma->id
            ]);
        }

        return redirect('/turmasmodelos');
    }

    public function edit(Request $request, $id)
    {
        $turma = TurmaModelo::find($id);
        $disciplinas = DB::table('disciplinas')->get();
        $disciplinasturmas = DB::table('turmas_modelos')
            ->select('disciplinas.id', 'disciplinas.name')
            ->join('disciplinas_turmas_modelos', 'disciplinas_turmas_modelos.turma_modelo_id', '=', 'turmas_modelos.id')
            ->join('disciplinas', 'disciplinas.id', '=', 'disciplinas_turmas_modelos.disciplina_id')
            ->where('turmas_modelos.id', $turma->id)
            ->get();

        $disciplinas_turma = $disciplinas->map(function ($d) use ($disciplinasturmas) {
            $selected = $disciplinasturmas->contains('id', $d->id);
            return [
                'id' => $d->id,
                'name' => $d->name,
                'selected' => $selected
            ];
        });

        $params = [
            'titulo' => 'Editar Turma Modelo',
            'acao' => 'edit',
            'id' => $turma->id,
            'disciplinas' => $disciplinas_turma,
            'serie' => $turma->serie,
            'nome' => $turma->nome // Supondo que agora tenha o campo nome
        ];

        return view('pages.turmasModelos.register', $params);
    }

    public function destroy($id)
    {
        if (session('type') == 'student') {
            return redirect('/');
        }

        $turma = TurmaModelo::find($id);
        if ($turma != null) {
            $turma->delete();
        }

        return redirect('/turmasmodelos');
    }
}
