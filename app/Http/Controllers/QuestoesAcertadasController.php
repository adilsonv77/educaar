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
        

        $aluno = DB::table('users')->where('id', $data['aluno_id'])->first();

        $mostra_resposta = 1;
        if($data['type_question']== "corretas"){
            $where = TurmaDAO::buscarQuestoesCorretas($data['turma_id'], $aluno->id)->get();
        }else if($data['type_question']== "incorretas"){
            $where = TurmaDAO::buscarQuestoesIncorretas($data['turma_id'], $aluno->id)->get();
        }else{
            
            
            $where = TurmaDAO::buscarQuestoesNaoRespondidas($data['turma_id'], $aluno->id)->get();
            $mostra_resposta = 0;
        }
        $questoes = array();
        $questao= null;

        foreach($where as $w){

            $questao = [
                'activity_name' => $w->activity_name,
                'content_name' => $w->content_name,
                'question'=> $w->question,
                'alternative_answered'=> $w->alternative_answered
            ];

            array_push($questoes, $questao);
        }
        return view('pages.turma.questoesDosAlunos', compact('questoes', 'aluno', 'mostra_resposta'));
    }

    public function todos(Request $request)
    {
        $data= $request->all(); 
        $where = TurmaDAO::buscarQuestoesNaoRespondidasTodosAlunos($data['turma_id'])->get();
        //dd($where);
        $questoes = array();
        $last_content = "";
        $last_activity = "";

        foreach($where as $w){
           
            if ($w->content_name != $last_content) {
                
                if ($last_activity != "") 
                    array_push($questao['activities'], $activity);

                if ($last_content != "") {

                    array_push($questoes, $questao);
                }

                $questao = [
                    'content_name' => $w->content_name,
                    'activities' => []
                ];
                $last_content = $w->content_name;
                $last_activity = "";



            } 

            if ($w->activity_name != $last_activity) {
                
                if ($last_activity != "") {
                    
                    array_push($questao['activities'], $activity);

                }
                $activity = [
                    'activity_name' => $w->activity_name,
                    'alunos' => []
                ];
                $last_activity = $w->activity_name;
                
            } 
            
            array_push($activity['alunos'], $w->user_name);

        }

        return view('pages.turma.listarNaoRespondidas', compact('questoes'));

    }

}