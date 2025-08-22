<?php

namespace App\Http\Controllers;

use App\DAO\UserDAO;
use App\Models\AlunoTurma;
use App\Models\User;
use App\Models\TurmaDisciplina;
use App\Models\AnoLetivo;
use App\Models\School;
use App\Models\TurmaModelo;
use App\Models\Turma;
use App\DAO\TurmaDAO;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class TurmaController extends Controller
{
    public function index(Request $request)
    {

        $where = DB::table('turmas')
            ->select('turmas.id', 'turmas.nome', 'turmas.school_id', 'turmas.ano_id', 'tm.serie')
            ->where('turmas.school_id', Auth::user()->school_id)
            ->join('turmas_modelos as tm', 'turmas.turma_modelo_id', '=', 'tm.id');

        $data = $request->input('ano_id');
        if ($data) {
            $id = $data;
            $where->where('ano_id', $id);
            $anoletivo = AnoLetivo::find($data);
        } else {
            $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
                ->where('bool_atual', 1)->first();
            $where->where('ano_id', $anoletivo->id);
        }

        $where = $where->addSelect([
            'qtosAlunos' => AlunoTurma::selectRaw('count(*)')
                ->join('users as u', 'alunos_turmas.aluno_id', '=', 'u.id')
                ->where('u.type', '=', 'student')
                ->whereColumn('turmas.id', '=', 'turma_id')
        ]);


        $turmas = $where->paginate(20);
        $anosletivos = AnoLetivo::where('school_id', Auth::user()->school_id)->get();

        //dd($turmas);

        session()->remove('turma_id');

        return view('pages.turma.index', compact('turmas', 'anosletivos', 'anoletivo'));
    }

    public function create()
    {
        $acao = 'insert';

        $anosletivos = AnoLetivo::where('school_id', Auth::user()->school_id)->get();
        $anoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();

        $turmasmodelos = DB::table('turmas_modelos')->where('school_id', Auth::user()->school_id)->get();

        $params = [
            'titulo' => 'Adicionar Turma',
            'acao' => $acao,
            'id' => 0,
            'nome' => '',
            'ano_id' => $anoAtual->id,
            'anosletivos' => $anosletivos,
            'turma_modelo_id' => 0,
            'turmasModelo' => $turmasmodelos

        ];
        return view('pages.turma.create', $params);
    }
    public function store(Request $request)
    {


        $data = $request->all();
        //$request->turmaModelo;
        //  return var_dump($data);
        $data['school_id'] =  Auth::user()->school_id;


        if ($data['acao'] == 'edit') {
            $turma = Turma::find($data['id']);
            $turma->update($data);
        } else {
            $turma = Turma::create($data);
            $tm = $turma->turma_modelo_id;
            $turmamodelo = TurmaModelo::find($tm);
            $disciplinasturmas = DB::table('turmas_modelos')
                ->select('disciplinas.id', 'disciplinas.name')
                ->join('disciplinas_turmas_modelos', 'disciplinas_turmas_modelos.turma_modelo_id', '=', 'turmas_modelos.id')
                ->join('disciplinas', 'disciplinas.id', '=', 'disciplinas_turmas_modelos.disciplina_id')
                ->where('turmas_modelos.id', $turmamodelo->id)
                ->get();

            $prof = School::find(Auth::user()->school_id);
            // Adicionar as disciplinas em turmasdisciplinas
            foreach ($disciplinasturmas as $disc) {

                $disc_turma = TurmaDisciplina::create([
                    'disciplina_id' => $disc->id,
                    'turma_id'  => $turma->id,
                    'professor_id' => $prof->prof_atual_id
                ]);

                //$disc_turmamodelo->save();
            }
        }

        // return var_dump($disciplinasturmas);



        return redirect('/turmas');
    }
    public function edit(Request $request, $id)
    {

        $turma = Turma::find($id);
        $acao = 'edit';

        $anosletivos = AnoLetivo::where('school_id', Auth::user()->school_id)->get();
        $turmasmodelos = DB::table('turmas_modelos')->where('school_id', Auth::user()->school_id)->get();

        $params = [
            'titulo' => 'Editar Turma',
            'acao' => $acao,
            'id' => $turma->id,
            'nome' => $turma->nome,
            'ano_id' => $turma->ano_id,
            'anosletivos' => $anosletivos,
            'turmasModelo' => $turmasmodelos,
            'turma_modelo_id' => $turma->turma_modelo_id,
        ];
        return view('pages.turma.create', $params);
    }
    public function disciplinasIndex(Request $request)
    {
        $data = $request->input('turma_id');

        session()->put('turma_id', $data);
        //return var_dump($data);
        //$turma= Turma::find($data);

        $disciplinas = DB::table('turmas_disciplinas')
            ->select('disciplinas.name as dname', 'disciplinas.id as did', 'users.id as pid')
            ->join('disciplinas', 'disciplinas.id', '=', 'turmas_disciplinas.disciplina_id')
            ->join('users', 'users.id', '=', 'turmas_disciplinas.professor_id')
            ->where('turma_id', $data)
            ->get();

        $professores = DB::table('users')
            ->select('users.name', 'users.id')
            ->where('users.type', 'teacher')
            ->where('users.school_id', Auth::user()->school_id)
            ->get();

     
        return view('pages.turma.turmaDisciplinas', compact('disciplinas', 'professores'));
    }
    public function storeDisciplinaProf(Request $request)
    {
        $turma = session()->get('turma_id');
        $data = $request->all();
        $disciplinas = DB::table('turmas_disciplinas')
            ->where('turma_id', $turma)
            ->get();

        $s = "";
        foreach ($disciplinas as $disc) {
            $cbx = "cbx_" . $disc->disciplina_id;
            $disc->professor_id = $data[$cbx];
            DB::table('turmas_disciplinas')
                ->where(['disciplina_id' => $disc->disciplina_id, 'turma_id' => $turma])
                ->update(['professor_id' => $disc->professor_id]);
            /*
            $s = $s . " " . $cbx . " " . $codprofessor . "\n";
            update turmas_disciplinas set professor_id = $codprofessor where disciplina_id = $disc->disciplina_id and turma_id = $turma
            */
        }

        //return var_dump($s);

        return redirect('/turmas');
    }
    public function novoAlunoTurma()
    {
        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();

        $params = [
            'acao' => 'insert',
            'nome' => '',
            'titulo' => 'Matricular aluno em uma Turma',
            'anoletivo' => $anoletivo
        ];


        return view('pages.turma.alunoNovoTurma', $params);
    }
    public function novoAlunoTurmaStore(Request $request)
    {
        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();
        $data = $request->all();

        // dd($data);
        if ($data['acao'] == 'edit') {
            AlunoTurma::where('aluno_id', $data['aluno_id'])
                ->update(['turma_id' => $data['turma']]);
        } else {
            AlunoTurma::create(['turma_id' => $data['turma'], 'aluno_id' => $data['aluno_id']]);
        }

        return redirect(route('turmas.indexmatricula'));
    }

    public function indexmatricula(Request $request)
    {
        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();

        $where = DB::table('turmas')
            ->where('school_id', Auth::user()->school_id)
            ->where('ano_id', $anoletivo->id);

        $data = $request->all();

        $turmas = $where->get();
        if (empty($data)) {
            $turma = $where->first();
        } else {
            $turma = Turma::find($data['turma_id']);
        }

        $alunos = DB::table('users')
            ->select('users.name', 'users.id')
            ->join('alunos_turmas as at', 'at.aluno_id', '=', 'users.id')
            ->where([
                ['at.turma_id', '=', $turma->id],
                ['users.type', '=', 'student'],
                ['users.school_id', '=', $turma->school_id]
            ])
            ->orderBy('users.name')
            ->get();




        return view('pages.turma.indexmatricula', compact('alunos', 'turma', 'turmas', 'anoletivo'));
    }

    public function turmasAlunosIndex(Request $request)
    {
        $data = $request->all();

        //         SELECT u.name from users as u 
        // 	inner join alunos_turmas as at 
        //     on at.aluno_id= u.id
        //   where at.turma_id= 4 and u.type= 'student';
        $turma = Turma::find($data['turma_id']);

        $alunos = DB::table('users')
            ->select('users.name', 'users.id')
            ->join('alunos_turmas as at', 'at.aluno_id', '=', 'users.id')
            ->where([
                ['at.turma_id', '=', $turma->id],
                ['users.type', '=', 'student'],
                ['users.school_id', '=', $turma->school_id]
            ])
            ->orderBy('users.name')
            ->get();

        $turma = Turma::find($data['turma_id'])->nome;


        return view('pages.turma.alunosTurma', compact('alunos', 'turma'));
    }
    public function desmatricular(Request $request)
    {
        $data = $request->all();

        $query = DB::table('alunos_turmas')
            ->where('alunos_turmas.aluno_id', '=', $data['aluno_id'])
            ->delete();

        $turma = $data['turma_id'];
        return redirect(route('turmas.indexmatricula', 'turma_id=' . $turma));
    }
    public function destroy($id)
    {
        if (session('type') == 'student') {
            return redirect('/');
        }

        $turma = Turma::find($id);

        $disciplinas = DB::table('turmas_disciplinas')->where('turma_id', $turma->id);

        if($disciplinas->exists()){
            $disciplinas->delete();
        }

        if ($turma != null) {
            $turma->delete();
        }
        return redirect('/turmas');
    }

    // tela do professor > Alunos
    public function listarTurmasAlunosProf(Request $request){
        $turma_id = $request->input('turma_id');

        $prof_id = Auth::user()->id;

        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
                    ->where('bool_atual', 1)->first();
        
        $where = TurmaDAO::buscarTurmasProf($prof_id, $anoletivo->id);
        $turmas= $where->get();

        if ($turma_id) {
            $turma = Turma::find($turma_id);
            
        } else {
            $turma = $where->first();
            $turma_id = $turma->id;
        }

        $where2 = TurmaDAO::buscarAlunosTurma($prof_id, $turma_id, $anoletivo->id);
        
        $where2 = $where2->get();  // paginate(20) as turmas não são tão grandes, e a paginação vai exigir uma alteração fudida nos filtros
        
        $alunos = array();
        $newd = null;
        // dd($where2);

        

        foreach($where2 as $aluno){
            $newd = [
                'name' => $aluno->name,
                'id' => $aluno->aluno_id,
            ];
            $alunoRespondeuCorreto = TurmaDAO::resultadosCorretosAlunos($turma_id, $aluno->aluno_id)->get()->first();
            $alunoNaoRespondeuCorreto = TurmaDAO::resultadosIncorretosAlunos($turma_id, $aluno->aluno_id)->get()->first();
            $alunoNaoRespondeu= TurmaDAO::resultadosQuestoesNaoFeitas($turma_id)->get()->first();

            $newd['qntCorretas'] = ($alunoRespondeuCorreto == null?0:$alunoRespondeuCorreto->qntCorretas);
            $newd['qntIncorretas'] = ($alunoNaoRespondeuCorreto == null?0:$alunoNaoRespondeuCorreto->qntIncorretas);
            $newd['qntNaoRespondidas'] = $alunoNaoRespondeu->qntQuestoes - ($newd['qntCorretas'] + $newd['qntIncorretas']);
       

            array_push($alunos, $newd);
        }

        return view('pages.turma.listarTurmasAlunosProf', compact('turmas', 'alunos', 'turma'));
    }
}
