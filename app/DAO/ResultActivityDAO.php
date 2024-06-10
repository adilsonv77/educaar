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
    public static function getStudentDidQuestions(){

        $alunos= session()->get('alunos_fizeram_completo');

        return $alunos;

    }
    public static function getStudentsUnfinishQuestions(){

        $alunos= session()->get('alunos_fizeram_incompleto');

        return $alunos;

    }
    public static function getStudentDidNotQuestions(){
                    //alunos que não fizeram
                    $turma_id= session()->get('turma_id');
                    $questao=  session()->get('question_base');

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
    public static function respostasDosAlunos(){
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
        $turma_id= session()->get('turma_id');
        $activity_id= session()->get('activity_id');
        $totalquestoes= session()->get('totalQuestions');

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

        /*
        $where = DB::table('contents')
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            ->addSelect([
                'qtasatividades' => Activity::selectRaw('count(*)')
                    ->whereColumn('contents.id', '=', 'content_id')
            ]);
*/
        /**
         * SELECT q.question as questao,q.id, COUNT(sta.id) as qntRespondida, 
        (SELECT COUNT(sta.id) FROM student_answers as sta
                    INNER JOIN alunos_turmas as alunt 
                    on alunt.aluno_id= sta.user_id
        WHERE sta.question_id= q.id and sta.correct=1
        ) as quntRespondCerto from questions as q
            inner join student_answers as sta 
            on sta.question_id= q.id
            INNER JOIN alunos_turmas as alunt 
            on alunt.aluno_id = sta.user_id
    WHERE q.activity_id=15 AND alunt.turma_id=5 
GROUP BY q.id
         */


/**
             * 
        $results = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
        ->select('sub.nome', 'sub.qntRespondida')
        ->whereBetween('sub.qntRespondida', [1, 4])
        ->get();
             */

         /**
             * SELECT COUNT(u.name) as qntsNaofizeram from users as u 
	    INNER join alunos_turmas as alunt
        on alunt.aluno_id= u.id
        WHERE alunt.turma_id= 5 AND NOT EXISTS (SELECT * from student_answers as sta 
                           	WHERE sta.question_id= 20
                                    AND sta.user_id= u.id)

             * 
             * $repairJobs = RepairJob::with('repairJobPhoto', 'city', 'vehicle')
              ->where('active', '=', 'Y')
              ->whereNotExists(function($query)
                {
                    $query->select(DB::raw(1))
                          ->from('DismissedRequest')
                          ->whereRaw('RepairJob.id = DismissedRequest.id');
                })->get();
             */



    /**
     * 
     * 
     * 
     * 
         * SELECT COUNT(nome) as completo, (SELECT COUNT(q.id) as qntQuestions from questions as q 
			inner join activities as a 
            on q.activity_id= a.id
        where a.id= 15)as TotalQuestoes 
        from (SELECT u.name as nome, COUNT(sta.id) as qntRespondida FROM `users` AS u 
		inner join `alunos_turmas` as alunt
		on alunt.aluno_id= u.id
        inner join student_answers as sta 
        on sta.user_id= alunt.aluno_id
        inner join questions as q 
        on sta.question_id= q.id
        WHERE alunt.turma_id= 5 and q.activity_id= 15  
GROUP BY u.name) AS sub 

         */


