<?php

namespace App\DAO;

use App\Models\Turma;

use App\Models\AnoLetivo;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultContentDAO
{

    public static function getStatusQuestionPorConteudo($turmaid, $contentid)
    {
        /*
         select a.id as activity_id, q.id as question_id, atu.aluno_id as aluno, sta.correct from turmas t 
             join contents c on c.turma_modelo_id = t.turma_modelo_id 
             join activities a on c.id = a.content_id 
             join questions q on q.activity_id = a.id 
             join alunos_turmas atu on atu.turma_id = t.id 
             left outer join student_answers sta on sta.question_id = q.id and sta.user_id = atu.aluno_id 
             where t.id = 4 and c.id = 1
         */
        $sql = DB::table('turmas as t')
            ->join("contents as c", "c.turma_modelo_id", "=", "t.turma_modelo_id")
            ->join("activities as a", "c.id", "=", "a.content_id")
            ->join("questions as q", "q.activity_id", "=", "a.id")
            ->join("alunos_turmas as atu", "atu.turma_id", "=", "t.id")
            ->leftJoin("student_answers as sta", function ($join) {
                $join->on("sta.question_id", "=", "q.id");
                $join->on("sta.user_id", "=", "atu.aluno_id");
            })
            ->select("a.id as activity_id", "a.name as activity_name", "q.id as question_id", "atu.aluno_id as aluno", "sta.correct")
            ->where([
                ['t.id', '=', $turmaid],
                ['c.id', '=', $contentid]
            ])
            ->orderBy("activity_id")
            ->orderBy("aluno")
            ->get(); // Aqui você já executa a consulta e obtém os resultados

        return $sql;
    }

    public static function getCountRespostas($turmaid, $questionid) {
        /*
        select sa.alternative_answered, COUNT(sa.id) as respostas_count, q.answer from student_answers sa
            join questions q on q.id = sa.question_id
            where q.id = 94
            group by sa.alternative_answered, q.answer
        */

        /* Subquery para pegar somente as últimas tentativas */
        $ultimaTentativa = DB::table('student_answers')
            ->select('user_id', DB::raw('MAX(tentativas) as ult'))
            ->where('question_id', $questionid)
            ->groupBy('user_id');

        return DB::table('student_answers as sa')
                ->join('questions as q', 'sa.question_id', '=', 'q.id')
                ->join("alunos_turmas as atu", "atu.aluno_id", "=", "sa.user_id")
                ->join("turmas as t", "t.id", "=", "atu.turma_id")
                ->joinSub($ultimaTentativa, 'ult_sa', function($join) {
                    $join->on('ult_sa.user_id', '=', 'sa.user_id')
                        ->on('ult_sa.ult', '=', 'sa.tentativas');
                })
                ->select(
                    'sa.alternative_answered',
                    DB::raw('COUNT(sa.id) as respostas_count'),
                    'q.answer'
                )
                ->where('q.id', $questionid) // Filtra pela questão atual
                ->where('t.id', $turmaid)
                ->groupBy('sa.alternative_answered', 'q.answer')
                ->get();
    }

    public static function getQuestoesAtividade($activityid) {
        return DB::table('questions')->where('activity_id', $activityid)->get();

    }
}