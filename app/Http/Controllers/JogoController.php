<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jogo;
use App\DAO\ContentDAO;
use App\DAO\SalaDAO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\Question;

class JogoController extends Controller
{
    public function index()
    {
        $jogos = Jogo::all();
        $titulo = 'Jogos';
        
        foreach($jogos as $jogo) {
            $jogo->podeCriarSala = SalaDAO::podeCriarSala($jogo->id);
        }

        return view('pages.game.index', compact('jogos', 'titulo'));
    }

    /* Esse método não é mais utilizado, pois a criação do jogo é realizada na criação de conteúdo

    public function create()
    {
        $titulo = 'Criar Jogo';
        $where = null;
        if (session('type') == 'admin') {
            $where = DB::table('contents')
                ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
                ->join('turmas_modelos', 'turmas_modelos.id', '=', 'contents.turma_modelo_id')
                ->where('turmas_modelos.school_id', '=', Auth::user()->school_id);
        } else {
            if (session('type') == 'teacher') {
                $anoletivoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
                    ->where('bool_atual', 1)->first();
                $anoletivo_id = $anoletivoAtual->id;
                $where = ContentDAO::buscarContentsDoProf(Auth::user()->id, $anoletivo_id);
            } else {
                $where = ContentDAO::buscarConteudosDeveloper(Auth::user()->id);
            }
        }

        $where = $where->where('contents.sort_activities', 2);

        $where = $where->select(
            'contents.id as id',
            'contents.name as content_name',
            'disciplinas.name as disc_name',
            'turmas_modelos.serie as turma_name',
            'contents.fechado',
            DB::raw('concat(contents.name, " - ", disciplinas.name, " (" , turmas_modelos.serie, ")") AS pesq_name')
        );


        $where = $where->addSelect([
            'qtasatividades' => Activity::selectRaw('count(*)')
                ->whereColumn('contents.id', '=', 'content_id')
        ]);
        $where = $where->addSelect([
            'qtasQuestoes' => Question::selectRaw('count(*)')
                ->join("activities as a", "a.id", "=", "activity_id")
                ->join("contents as c", "c.id", "=", "content_id")
                ->whereColumn('contents.id', '=', 'content_id')
        ]);

        $contents = $where->paginate(20);
        return view('pages.game.create', compact('titulo', 'contents'));
    }
    */    

    public function store(Request $request)
    {
        $jogo = Jogo::create($request->all());
        return redirect()->route('content.index');
    }

    public function show(Jogo $jogo)
    {
        return view('game.show', compact('jogo'));
    }

    public function update(Request $request, Jogo $jogo)
    {
        $jogo->update($request->all());
        return redirect()->route('game.index');
    }

    public function destroy(int $jogoId)
    {
        $jogo = Jogo::find($jogoId);
        $jogo->delete();
        return redirect()->route('game.index');
    }
}
