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
             join contents c on c.turma_id = t.turma_modelo_id 
             join activities a on c.id = a.content_id 
             join questions q on q.activity_id = a.id 
             join alunos_turmas atu on atu.turma_id = t.id 
             left outer join student_answers sta on sta.question_id = q.id and sta.user_id = atu.aluno_id 
             where t.id = 4 and c.id = 1
         */

        $sql = DB::table('turmas as t')
            ->join("contents as c", "c.turma_id", "=", "t.turma_modelo_id")
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

    public static function getAlternativeCountsForActivity($activityId)
    {
        return DB::table('student_answers as sa')
            ->join('questions as q', 'sa.question_id', '=', 'q.id')
            ->select('sa.alternative_answered', DB::raw('count(*) as count'))
            ->where('sa.activity_id', $activityId)
            ->whereIn('sa.alternative_answered', ['A', 'B', 'C', 'D'])
            ->groupBy('sa.alternative_answered')
            ->get();
    }
}