<?php

namespace App\Http\Controllers;

use App\DAO\ActivityDAO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;
use App\Models\Matricula;
use App\Models\User;
use App\Models\StudentAnswer;
use App\Models\School;
use App\Models\DisciplinaProfessor;
use App\Models\AnoLetivo;
use App\DAO\ContentDAO;
use App\DAO\UserDAO;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // $contents = DB::table('contents')->where('user_id', Auth::user()->id)->get();
        // $contentsCount = DB::table('contents')->where('user_id', Auth::user()->id)->count();
        /* $contadorAtividades = 0; */

        /* foreach($contents as $content) {
            if($content->hasActivity == 1 && $content->user_id == Auth::user()->id) {
                $contadorAtividades++;
            }
        }
        */

        //$activities = $contents;
        $schools = School::all()->where('id', Auth::user()->school_id)->first();

        if (Auth::user()->type == 'admin') {
            return view('home', compact('schools'));
        }
        if(Auth::user()->type == 'developer'){
            $activities= DB::table('activities')
                        ->join('contents', 'activities.content_id', '=', 'contents.id')
                        ->join('content_developer','content_developer.content_id','=','contents.id')
                        ->where('content_developer.developer_id',Auth::user()->id);
            $activities = $activities->distinct()->paginate(20);
            return view('home', compact('activities'));
        }

        if (Auth::user()->type == 'student') {
            $ano_letivo = AnoLetivo::where('bool_atual', 1)->first();
            $disciplinas = DB::table('disciplinas as d')
                ->select('d.id', 'd.name')
                ->join('turmas_disciplinas as td', 'd.id', '=', 'td.disciplina_id')
                ->join('turmas as t', 'td.turma_id', '=', 't.id')
                ->join('alunos_turmas as at', 't.id', '=', 'at.turma_id')
                ->where('at.aluno_id', Auth::user()->id)
                ->where('t.ano_id', $ano_letivo->id)
                ->distinct()
                ->get();



            $rota = route("student.conteudos") . "?id=1";
            return view('mobHome', compact('disciplinas', 'rota'));
            /*
            $isActivityFinish = StudentAnswer::where('user_id', Auth::user()->id)->get();
            return view('home', compact('activities', 'isActivityFinish', 'schools'));
            */
        }

        if (Auth::user()->type == 'teacher') {
         
            DB::enableQueryLog();
            $contents = ContentDAO::buscarContentsDoProf(Auth::user()->id, false)
                ->selectRaw("count(distinct(contents.id)) as quantos")->get();
            //dd(DB::getQueryLog());
            $activities = ActivityDAO::buscarActivitiesDoProf(Auth::user()->id, false)
                ->selectRaw("count(distinct(activities.id)) as quantos")->get();
                
            $fechados = ContentDAO::buscarContentsDoProf(Auth::user()->id, false)
                ->selectRaw("count(distinct(contents.fechado)) as quantos")->get();

            $fechadoCount = $fechados[0] -> quantos;
            $activitiesCount = $activities[0]->quantos;
            $usersCount = 83;
            $contentCount = $contents[0]->quantos;

            return view('home', compact('activitiesCount', 'usersCount', 'contentCount', 'activitiesCount', 'schools', 'fechadoCount'));
        }

        // return view('home')->withErrors('Login ou senha inválidos. Por favor, tente novamente.');
        return redirect()->back()->withErrors('Login ou senha inválidos. Por favor, tente novamente.');
    }

    function goBack()
    {
        return redirect()->back();
    }
}
