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
            ->orderBy("aluno");

        return $sql;
    }
    public static function getResponsesByQuestion($turma_id, $question_id)
    {
        return DB::table('student_answers')
            ->select('alternative_answered', DB::raw('count(*) as count'))
            ->where('question_id', $question_id)
            ->where('activity_id', $turma_id)
            ->groupBy('alternative_answered')
            ->get(); // Retorna uma coleção

    }


}