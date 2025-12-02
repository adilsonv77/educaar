<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Content;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FecharController extends Controller
{ 

    public function index(Request $request)
    {
        $content  = DB::table('contents')
            ->select('contents.*', 'disciplinas.name as dname', 'turmas_modelos.serie as tserie')
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            ->join('turmas_modelos', 'turmas_modelos.id', '=', 'contents.turma_modelo_id')
            ->where('contents.id', $request['content'])
            ->first();

        $activities = Activity::where('content_id', '=', $request['content'])
        ->orderBy('position', 'asc')
        ->get();
   
        $titulo = "Fechar conteúdo";

        $id = $content->id;

        session()->put("content.id", $request['content']);

        return view('pages.fechar.register', compact('titulo', 'content', 'activities', 'id'));
    }


    public function store()
    {
        $content = Content::find(session()->pull("content.id"));
        $content ->update(['fechado' => 1]);
        
        return redirect('/content');
    }
}

?>
  