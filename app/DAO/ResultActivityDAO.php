<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultActivityDAO
{
    public static function buscarQntFizeramATarefas($activityID, $turma_id)
    {
        $where = DB::table('questions as q')->where('q.activity_id', $activityID);

        session()->put('turma_id', $turma_id);
        session()->put('activity_id', $activityID);
        //total de questões
        $totalQuestions= $where->count();
        

        $sql = DB::table('questions as q')
        ->select('u.id as id','u.name as nome', DB::raw('COUNT(sta.id) as qntRespondida'))
            ->join('student_answers as sta', 'sta.question_id', '=', 'q.id')
            ->join('alunos_turmas as alunt', 'alunt.aluno_id', '=', 'sta.user_id')
            ->join('users as u', 'u.id', '=', 'alunt.aluno_id')
            ->where([
                ['alunt.turma_id', '=', $turma_id],
                ['q.activity_id', '=', $activityID]
            ])
            ->groupBy('id','nome');
            // trocar para u.id
            // $alunos_fizeram= $subQuery->selectRaw('COUNT(u.name) as completo')->get();
            $alunos_fizeram= $sql->get();

            //separando os alunos que fizeram todas as questões
            $alunos_fizeram_completo= array();
            //separando os alunos que não fizeram todas as questões
            $alunos_fizeram_incompleto= array();
            foreach($alunos_fizeram as $aluno){
                if( $aluno->qntRespondida == $totalQuestions){
                    array_push($alunos_fizeram_completo,  $aluno);
                }else{
                    array_push($alunos_fizeram_incompleto,  $aluno);
                }
            }
            session()->put('alunos_fizeram_completo', $alunos_fizeram_completo);
            session()->put('alunos_fizeram_incompleto', $alunos_fizeram_incompleto);

            $question_base= $where->first()->id;
            session()->put('question_base', $question_base);
            //alunos que não fizeram
            $sql2= DB::table('users as u')
                    ->selectRaw('COUNT(u.name) as qntsNaofizeram')
                    ->join('alunos_turmas as alunt', 'alunt.aluno_id', '=', 'u.id')
                    ->where('alunt.turma_id', '=', $turma_id)
                    ->whereNotExists(function($query) use ($question_base)
                    {
                        $query->select(DB::raw(1))
                                ->from('student_answers as sta')
                                ->whereRaw('sta.user_id = u.id')
                                ->whereRaw('sta.question_id = '. $question_base);
                    });
            $alunos_nao_fizeram= $sql2->first();
            
            $result= [
                'totalQuestions' => $totalQuestions,
                'alunos_fizeram_completo' => count($alunos_fizeram_completo),
                'alunos_fizeram_incompleto' => count($alunos_fizeram_incompleto),
                'alunos_nao_fizeram' => $alunos_nao_fizeram->qntsNaofizeram
            ];
            

        return $result;
    }
    public static function questoesQntAcertos($activityID, $turma_id)
    {

        $sql = DB::table('questions as q')
        ->select('q.question as questao', 'q.id', DB::raw('COUNT(sta.id) as qntRespondida'))
        ->join('student_answers as sta', 'sta.question_id', '=', 'q.id')
            ->join('alunos_turmas as alunt', 'alunt.aluno_id', '=', 'sta.user_id')
            ->where([
                ['alunt.turma_id', '=', $turma_id],
                ['q.activity_id', '=', $activityID]
            ])
            ->groupBy('q.id');

            $where= $sql  ->addSelect([
                'quntRespondCerto' => DB::table('student_answers as sta')->selectRaw('COUNT(sta.id)')
                    ->join('alunos_turmas as alunt', 'alunt.aluno_id', '=', 'sta.user_id')
                    ->whereColumn('sta.question_id', '=', 'q.id')
                    ->where('sta.correct', '=', 1)
            ]);
            

            $result_questions= $where->get();

            return $result_questions;
    }

    public static function getStudentDidNotQuestions($turma_id, $questao){
                    //alunos que não fizeram


                    $sql2= DB::table('users as u')
                    ->select('u.id as id','u.name as nome')
                    ->join('alunos_turmas as alunt', 'alunt.aluno_id', '=', 'u.id')
                    ->where('alunt.turma_id', '=', $turma_id)
                    ->whereNotExists(function($query) use ($questao)
                    {
                        $query->select(DB::raw(1))
                                ->from('student_answers as sta')
                                ->whereRaw('sta.user_id = u.id')
                                ->whereRaw('sta.question_id = '. $questao);
                    });
            $alunos_nao_fizeram= $sql2->get();
            return $alunos_nao_fizeram;
    }
    public static function respostasDosAlunos($activity_id, $turma_id){
        /*SELECT u.id as aluno_id, u.name as name, q.id as question_id, sta.alternative_answered as alternativa, sta.correct as Correto from student_answers as sta 
		Join users as u
        on sta.user_id= u.id
        JOIN alunos_turmas as alunt 
        on alunt.aluno_id= u.id
        JOIN questions as q 
        on sta.question_id= q.id
WHERE alunt.turma_id= 5 AND q.activity_id=15
ORDER BY name
*/

        $sql= DB::table('student_answers as sta')
        ->select('u.id as aluno_id', 'u.name as name', 'q.id as question_id', 'sta.alternative_answered as alternativa', 'sta.correct as Correto')
        ->join('users as u', 'sta.user_id', '=', 'u.id')
        ->join('alunos_turmas as alunt', 'alunt.aluno_id', '=', 'u.id')
        ->join('questions as q', 'sta.question_id', '=', 'q.id')
        ->where([
            ['alunt.turma_id', '=', $turma_id],
            ['q.activity_id', '=', $activity_id]
        ])
        ->orderBy('name')
        ->orderBy('question_id', 'asc');

        $respostas= $sql->get();
        $tabelaresultado = array();
        $nomealuno = "";
        $newd = null;
        foreach ($respostas as $linha) {
                    
              // se questao ==1 eu sei que é a primeira linha do aluno então vou pegar o nome do aluno dessa linha e montar uma linha na tabela
            if ($linha->name <> $nomealuno) {
                    if ($newd != null){
                        array_push($tabelaresultado, $newd);
                    }
                    $newd = [
                        'name' => $linha->name,
                    ];
                    $nomealuno = $linha->name;
            } 
              // extrai o valor da coluna questao
            
            //   $idq = descobre o indice de $linha->questaoid em $questoes_atividade
            $idq = $linha->question_id;
            $questao = DB::table('questions as q')->where('q.id', $idq)->first();
            $newd['q'.$idq] = $linha->alternativa;
            $newd['q'.$idq.'correta'] = $linha->Correto;

            //   $newd = $newd[
            //            'q'.$idq => $linha->resposta,
            //             'q'.$idq.'correta' => $linha->correta
            //         ]; 
            
        }
        array_push($tabelaresultado, $newd);
        // dd($tabelaresultado);
        return $tabelaresultado;
    }
}



