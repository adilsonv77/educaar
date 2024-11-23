<?php

namespace App\Http\Controllers;

use App\DAO\ActivityDAO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\School;

use App\Models\AnoLetivo;
use App\DAO\ContentDAO;
use App\DAO\UserDAO;
use App\DAO\DisciplinaDAO;


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

        if (session('type') == 'admin') {
            return view('home', compact('schools'));
        }

        if (session('type') == 'student') {
            $ano_letivo = AnoLetivo::where('bool_atual', 1)->first();

            // quando o usuário é developer mas o sistema trata como aluno
            
            if (Auth::user()->type != session('type')) {
                $disciplinas = ContentDAO::buscarConteudosDeveloper(Auth::user()->id);
                $disciplinas = $disciplinas
                    ->select('disciplinas.id', 'disciplinas.name')
                    ->distinct()
                    ->get();
            } else {
                $disciplinas = DisciplinaDAO::buscarDisciplinasAluno(Auth::user()->id, $ano_letivo->id)
                    -> get();
                
            }

            $rota = route("student.conteudos") . "?id=1";
            return view('mobHome', compact('disciplinas', 'rota'));
            /*
            $isActivityFinish = StudentAnswer::where('user_id', Auth::user()->id)->get();
            return view('home', compact('activities', 'isActivityFinish', 'schools'));
            */
        }

        if(session('type') == 'developer'){
 
            $contentCount = ContentDAO::buscarConteudosDeveloper(Auth::user()->id)
                ->selectRaw("count(distinct(contents.id)) as quantos")->get();
            $contentCount = $contentCount[0]->quantos;

            $activitiesCount = ActivityDAO::buscarActivitiesDoDev(Auth::user()->id)
                ->selectRaw("count(distinct(activities.id)) as quantos")->get();
            $activitiesCount = $activitiesCount[0]->quantos;

            $fechadoCount = ContentDAO::buscarConteudosDeveloper(Auth::user()->id)
                ->selectRaw("sum(contents.fechado) as quantos")->get();
            $fechadoCount = $fechadoCount[0]->quantos;

            return view('home', compact('contentCount', 'fechadoCount', 'activitiesCount'));
        }



        if (session('type') == 'teacher' || session('type') == 'developer' ) {
         
            DB::enableQueryLog();

            $prof_id = Auth::user()->id;
            
            $anoletivoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
                ->where('bool_atual', 1)->first();
            $anoletivo_id = $anoletivoAtual->id;

            $contents = ContentDAO::buscarContentsDoProf($prof_id, $anoletivo_id)
                ->selectRaw("count(distinct(contents.id)) as quantos")->get();
            //dd(DB::getQueryLog());
            $activities = ActivityDAO::buscarActivitiesDoProf($prof_id, $anoletivo_id)
                ->selectRaw("count(distinct(activities.id)) as quantos")->get();
                
            $fechados = ContentDAO::buscarContentsDoProf($prof_id, $anoletivo_id)
                ->selectRaw("sum(contents.fechado) as quantos")->get();

            $alunosProf = UserDAO::buscarAlunosProf($prof_id, $anoletivo_id)
                ->selectRaw("count(distinct(u.id)) as quantos")->get();

            $fechadoCount = $fechados[0] -> quantos;
            $activitiesCount = $activities[0]->quantos;
            $usersCount = $alunosProf[0]->quantos;
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
