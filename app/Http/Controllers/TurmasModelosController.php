<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use App\Models\TurmaModelo;
use App\Models\Content;
use App\Models\DisciplinaTurmaModelo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class TurmasModelosController extends Controller
{
    //
    public function index()
    {
        DB::connection()->enableQueryLog();
        $where = DB::table('turmas_modelos')
            ->where('school_id', Auth::user()->school_id)
            ->addSelect([
                'qntTurmas' => Turma::selectRaw('count(*)')
                    ->whereColumn([['turmas_modelos.id', '=', 'turma_modelo_id'],
                                    ['turmas_modelos.school_id','=','school_id']])])
            ->addSelect([
                    'conteudos'=> DB::table('contents as c')
                    ->selectRaw('count(c.id)')
<<<<<<< Updated upstream
                ->join('disciplinas_turmas_modelos as dtm','c.disciplina_id','=','dtm.disciplina_id')
                ->whereColumn('dtm.turma_modelo_id','=','c.turma_id')]);
=======
                    ->join('disciplinas_turmas_modelos as dtm', function($join) {
                        $join->on('dtm.disciplina_id', '=', 'c.disciplina_id');
                        $join->on('dtm.turma_modelo_id', '=', 'c.turma_id');
                    })->whereColumn('dtm.turma_modelo_id','=','turmas_modelos.id')
                ]);
>>>>>>> Stashed changes
                

                
                // ->join('disciplinas_turmas_modelos as dtm','c.disciplina_id','=','dtm.disciplina_id')
                // ->whereColumn('dtm.turma_modelo_id','=','turmas_modelos.id')])


                // dd($where->get());
            
                // select * from turmas_modelo_disciplina tmd  join contents c 
                // on c.disciplina_id = tmd.disciplina_id where tmd.turma_id = ?


        $turmas = $where->paginate(20);
        //dd(DB::getQueryLog());
        return view('pages.turmasModelos.index', compact('turmas'));
    }
    public function create()
    {
        $acao = 'insert';
        $disciplinas = DB::table('disciplinas')->get();
        $disciplinas_turma = array();
        foreach ($disciplinas as $d) {
            $newd = [
                'id' => $d->id,
                'name' => $d->name,
                'selected' => FALSE
            ];

            array_push($disciplinas_turma, $newd);
        }
        $params = [
            'titulo' => 'Adicionar Turma Modelo',
            'acao' => $acao,
            'id' => 0,
            'serie' => '',
            'disciplinas' => $disciplinas_turma,
            'disciplinas_turmas' => []
        ];


        return view('pages.turmasModelos.register', $params);
    }
    public function store(Request $request)
    {

        $data = $request->all();
        /*
        $disciplinasturmas = DB::table('disciplinas_turmas_modelos')
        ->where('turma_modelo_id', $turma->id)
        ->get();
    foreach ($disciplinasturmas as $d) {
        $d->delete();
    }
*/
        $discs = $request->duallistbox_disc;
        if ($discs == null) {
            $discs = [];
        }
        $data['school_id'] = Auth::user()->school_id;

        if ($data['acao'] == 'insert') {
            $turma = TurmaModelo::create($data);
        } else {

            $turma = TurmaModelo::find($data['id']); 
            $turma->update($data);
            $deleted = DB::table('disciplinas_turmas_modelos')->where('turma_modelo_id', $turma->id)->delete();
        }

        // Adicionar as disciplinas em disciplinas_turmasmodelo
        foreach ($discs as $disc) {

                $disc_turmamodelo = DisciplinaTurmaModelo::create([
                    'disciplina_id' => $disc,
                    'turma_modelo_id'  => $turma->id
                ]);


            //$disc_turmamodelo->save();
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
        //SELECT d.id, d.name FROM turmas_modelos tm join disciplinas_turmas_modelos dtm 
        //on dtm.turma_modelo_id = tm.id join disciplinas d on d.id = dtm.disciplina_id where tm.id = 12;


        $disciplinas_turma = array();
        $disc_index  = array();
        $c = 0;
        foreach ($disciplinas as $d) {
            $newd = [
                'id' => $d->id,
                'name' => $d->name,
                'selected' => FALSE
            ];

            array_push($disciplinas_turma, $newd);
            $disc_index[$c] = $d->id;
            $c = $c + 1;
        }

        foreach ($disciplinasturmas as $dt) {
            $key = array_search($dt->id, $disc_index);
            if ($key !== FALSE) {
                $disciplinas_turma[$key]['selected'] = TRUE;
            }
        }

        $params = [
            'titulo' => 'Editar Turma Modelo',
            'acao' => 'edit',
            'id' => $turma->id,
            'disciplinas' => $disciplinas_turma,
            'serie' => $turma->serie,

        ];
        return view('pages.turmasModelos.register', $params);
    }
    public function destroy($id)
    {
        if (Auth::user()->type == 'student') {
            return redirect('/');
        }

        $turma = TurmaModelo::find($id);
        if ($turma != null) {
            $turma->delete();
        }
        return redirect('/turmasmodelos');
    }
}
