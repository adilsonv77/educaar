<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultActivityDAO
{
    protected $totalQuestions;
    protected $alunos_fizeram;
    protected $alunos_fizeram_completo= array();
    protected $alunos_fizeram_incompleto= array();
    protected $alunos_nao_fizeram;
    protected $question_base;


    public static function buscarQntFizeramATarefas($activityID, $turma_id)
    {
        $where = DB::table('questions as q')->where('q.activity_id', $activityID);

        //total de questões
        $totalQuestions= $where->count();

        $sql = DB::table('questions as q')
        ->select('u.name as nome', DB::raw('COUNT(sta.id) as qntRespondida'))
            ->join('student_answers as sta', 'sta.question_id', '=', 'q.id')
            ->join('alunos_turmas as alunt', 'alunt.aluno_id', '=', 'sta.user_id')
            ->join('users as u', 'u.id', '=', 'alunt.aluno_id')
            ->where([
                ['alunt.turma_id', '=', $turma_id],
                ['q.activity_id', '=', $activityID]
            ])
            ->groupBy('u.name');
            // trocar para u.id
        
            // $alunos_fizeram= $subQuery->selectRaw('COUNT(u.name) as completo')->get();
            $alunos_fizeram= $sql->get();

            //separando os alunos que fizeram todas as questões
            $alunos_fizeram_completo= array();
            //separando os alunos que não fizeram todas as questões
            $alunos_fizeram_incompleto= array();
            foreach($alunos_fizeram as $aluno){
                $newaluno = [
                    'nome' => $aluno->nome,
                    'qntRespondida' => $aluno->qntRespondida
                ];

                if( $aluno->qntRespondida == $totalQuestions){
                    array_push($alunos_fizeram_completo,  $newaluno);
                }else{
                    array_push($alunos_fizeram_incompleto,  $newaluno);
                }
            }

            $question_base= $where->first()->id;
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

