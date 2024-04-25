<?php
namespace App\Http\Controllers;

use App\Models\Turma;
use App\Models\Content;
use App\Models\Activity;
use App\Models\Matricula;
use App\Models\AnoLetivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use DataTables;
use chillerlan\QRCode\QRCode;
use Illuminate\Support\Facades\Validator;
use App\DAO\ContentDAO;
use App\DAO\ResultContentDAO;

class ContentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->type == 'student') {
            return redirect('/');
        }
/*

        $where = DB::table('contents')
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            ->addSelect([
                'qtasatividades' => Activity::selectRaw('count(*)')
                    ->whereColumn('contents.id', '=', 'content_id')
            ]);

*/
    DB::enableQueryLog();
        $where = null;
        if (Auth::user()->type == 'admin') {
            $where = DB::table('contents')
                ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
                ->join('turmas_modelos', 'turmas_modelos.id', '=', 'contents.turma_id')
                ->where('turmas_modelos.school_id', '=', Auth::user()->school_id);
        } else {

            $where = ContentDAO::buscarContentsDoProf(Auth::user()->id, true);
        }

        $where = $where->select('contents.id as id', 'contents.name as content_name', 'disciplinas.name as disc_name',
                'turmas_modelos.serie as turma_name', 'contents.fechado',
                DB::raw('concat(contents.name, " - ", disciplinas.name, " (" , turmas_modelos.serie, ")") AS pesq_name'));


        $where = $where->addSelect([
            'qtasatividades' => Activity::selectRaw('count(*)')
                ->whereColumn('contents.id', '=', 'content_id')
        ]);
        $content = $request->titulo;

        if ($content) {
            $r = '%' . $content . '%';
            $where = $where->where(DB::raw('concat(contents.name, " - ", disciplinas.name, " (" , turmas_modelos.serie, ")")'), 'like', $r);
        }

        

        $contents = $where->paginate(20);
       // dd(DB::getQueryLog());

        return view('pages.content.index', compact('contents', 'content'));
    }

    public function listOfContents()
    {
        if (Auth::user()->type == 'student') {
            return redirect('/');
        }
        $data = Content::select('*');
        return datatables()::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->type == 'student') {
            return redirect('/');
        }
        $idprof = Auth::user()->id;
        $anoLetivo = AnoLetivo::where('bool_atual', 1)->first();

        //var_dump($anoLetivo->id);
        $titulo = 'Adicionar Conteúdo';
        $acao = 'insert';

        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'name' => '',
            'id' => 0
        ];

        if (Auth::user()->type == 'teacher') {
            $disciplinas = $this->buscarDisciplinas($anoLetivo->id, $idprof);
            $params['disciplinas'] = $disciplinas;
            $params['disciplina'] = "";
        } else {
            $params['turma'] = "";
            $params['disciplina'] = "";
        }

        return view('pages.content.create', $params);
    }

    private function buscarDisciplinas($anoid, $profid)
    {
        /*

        Retorna as disciplinas com suas turmas desse professor, nesse ano letivo.

        select distinct d.name, tm.serie from turmas_disciplinas td
            join turmas t on (t.id = td.turma_id)
            join turmas_modelos tm on (tm.id = t.turma_modelo_id)
            join disciplinas d on (td.disciplina_id = d.id)
            where professor_id = 4 and ano_id = 1;
        */

        $disciplinas = DB::table('turmas_disciplinas')
            ->select(
                'turmas_modelos.id as tid',
                'turmas_modelos.serie as tnome',
                'disciplinas.id as did',
                'disciplinas.name as dnome'
            )
            ->join("turmas", "turmas.id", "=", "turmas_disciplinas.turma_id")
            ->join("turmas_modelos", "turmas_modelos.id", "=", "turmas.turma_modelo_id")
            ->join("disciplinas", "turmas_disciplinas.disciplina_id", "=", "disciplinas.id")
            ->where("professor_id", "=", $profid)
            ->where("ano_id", "=", $anoid)
            ->distinct()
            ->get();

        return $disciplinas;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if (Auth::user()->type == 'teacher') {
            $turma_disc = explode("_", $data['disciplina_id']);

            $data['turma_id'] = $turma_disc[0];
            $data['disciplina_id'] = $turma_disc[1];
        } else {
            $data['turma_id'] = $data['turma'];
            $data['disciplina_id'] = $data['disciplina'];
        }

        $data['fechado'] = 0;

        if ($data['acao'] == 'insert') {
            // usuário que cadastrou
            $data['user_id'] =  Auth::user()->id;

            Content::create($data);
        } else {

            $content = Content::find($data['id']);
            $content->update($data);

        }

        return redirect('/content');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */

    // public function show($id)
    // {
    //     if (Auth::user()->type == 'student') {
    //         return redirect('/');
    //     }

    //     $content = Content::where('id', $id)->first();

    //     return view('pages.content.results');
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        $content = Content::find($id);
        $titulo = 'Editar Conteúdo';
        $acao = 'edit';
        $idprof = Auth::user()->id;
        $anoLetivo = AnoLetivo::where('bool_atual', 1)->first(); // errado

        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'id' => $content->id,
            'name' => $content->name,
            'user_id' => $content->user_id
        ];

        if (Auth::user()->type == 'teacher') {
            $disciplinas = $this->buscarDisciplinas($anoLetivo->id, $idprof);
            $params['disciplinas'] = $disciplinas;
            $params['disciplina'] = $content->turma_id . "_" . $content->disciplina_id;
        } else {
            $params['turma'] = $content->turma_id;
            $params['disciplina'] = $content->disciplina_id;
        }


        return view('pages.content.create', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     * 
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->type == 'student') {
            return redirect('/');
        }

        $content = Content::find($id);
        $content->delete();
        return redirect(route('content.index'));
    }

    public function resultsContents(Request $request){
        $content= $request->input('content_id');

        if($content){
            session()->put('content', $content); 
        }
        $turma_id = $request->input('turma_id');

        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
        ->where('bool_atual', 1)->first();

        $content_id = session()->get('content');

        $where = DB::table('turmas as t')
            ->select('t.id as id','t.nome as nome')
            ->join('turmas_disciplinas as td','td.turma_id', '=', 't.id')
            ->join('turmas_modelos as tm', 't.turma_modelo_id','=','tm.id')
            ->join('contents as c', 'c.turma_id', '=', 'tm.id')
            ->join('activities as a', 'a.content_id', '=', 'c.id')
            ->where([
                ['td.professor_id','=', Auth::user()->id],
                ['t.ano_id','=', $anoletivo->id],
                ['c.id', '=', $content_id]
            ])
            ->distinct();

            
        if ($turma_id) {
            $turma = Turma::find($turma_id);
            
        } else {
            $turma = $where->first();
        }

        $turmas= $where->get();

        $content = Content::find($content_id);

        $results= ResultContentDAO::buscarQntFizeramAsTarefas($content->id, $turma->id);

        $activities= ResultContentDAO::atividadesFeitas($content->id, $turma->id);

        return view('pages.content.results', compact('results', 'activities', 'turmas', 'turma'));
    }

    function resultsListStudents($type){
    
        
        return view('pages.content.listStudents');
    
    }
}