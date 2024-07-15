<?php

namespace App\Http\Controllers;

use App\DAO\TurmaDAO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestoesAcertadasController extends Controller

{


    public function index(Request $request)
    {
        $data= $request->all(); 
        
        $profid= Auth::user()->id;

        $aluno = DB::table('users')->where('id', $data['aluno_id'])->first();


        if($data['type_question']== "corretas"){
            $where = TurmaDAO::buscarQuestoesCorretas($data['turma_id'], $aluno->id, $profid)->get();
        }else if($data['type_question']== "incorretas"){
            $where = TurmaDAO::buscarQuestoesIncorretas($data['turma_id'], $aluno->id, $profid)->get();
        }else{

        }
        $questoes = array();
        $questao= null;

        foreach($where as $w){
            $questao = [
                'question'=> $w->question,
                'alternative_answered'=> $w->alternative_answered
            ];

            $activity_name = DB::table('activities')->where('id', $w->activity_id)->first()->name;
            $content_name = DB::table('contents')->where('id', $w->content_id)->first()->name;

            $questao['activity_name'] = $activity_name;
            $questao['content_name'] = $content_name;

            array_push($questoes, $questao);
        }

        
        return view('pages.turma.questoesDosAlunos', compact('questoes', 'aluno'));
    }

}