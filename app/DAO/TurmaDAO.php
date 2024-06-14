<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TurmaDAO
{

    public static function buscarTurmasProf($profid, $anoletivoid)
    {
   /*
        $sql = DB::table('turmas_disciplinas')
        ->join("alunos_turmas as ta", "ta.turma_id", "=", "turmas_disciplinas.turma_id")
        ->join("turmas", "turmas.id", "=", "ta.turma_id")
            ->where([
                ['turmas_disciplinas.professor_id', '=', $profid],
                ['turmas.ano_id', '=', $anoletivoid]
            ]);
*/
        $sql = DB::table('turmas_disciplinas')
            ->select("turmas.id", "turmas.nome")
            ->join("turmas", "turmas.id", "=", "turmas_disciplinas.turma_id")
            ->where([
                ['turmas_disciplinas.professor_id', '=', $profid],
                ['turmas.ano_id', '=', $anoletivoid]
            ])
            ->distinct();
        return $sql;
    }

    public static function buscarAlunosTurma($turmaid) {
        $sql = DB::table('alunos_turmas as ta')
            ->join("users as u", "u.id", "=", "ta.aluno_id")
            ->where("ta.turma_id" , "=", $turmaid);

        return $sql;        
    }

    public static function resultadosCorretosAlunos($turmaid, $alunoid, $profid) {
        
        /*select u.name, COUNT(q.id) as qntCorretas from turmas_disciplinas as td
        join turmas as t on td.turma_id = t.id
        join contents as c on t.turma_modelo_id = c.turma_id and
                            td.disciplina_id = c.disciplina_id
        join activities as a on a.content_id= c.id
        JOIN questions as q on q.activity_id= a.id
        JOIN student_answers as sta on sta.question_id= q.id and 
        							sta.activity_id= a.id
        JOIN users as u on sta.user_id = u.id
        JOIN alunos_turmas as alunt on alunt.turma_id= t.id
        								and alunt.aluno_id= u.id
where td.professor_id = 4 and t.ano_id = 4 AND sta.correct= 0 
AND u.id = 8
 */
        $sql = DB::table('turmas_disciplinas as td')
            ->select("u.name", DB::raw("COUNT(q.id) as qntCorretas"))
            ->join("turmas as t", "td.turma_id", "=", "t.id")
            ->join("contents as c", function($join) {
                $join->on("t.turma_modelo_id", "=", "c.turma_id")
                    ->on("td.disciplina_id", "=", "c.disciplina_id");
            })
            ->join("activities as a", "a.content_id", "=", "c.id")
            ->join("questions as q", "q.activity_id", "=", "a.id")
            ->join("student_answers as sta", function($join) {
                $join->on("sta.question_id", "=", "q.id")
                    ->on("sta.activity_id", "=", "a.id");
            })
            ->join("users as u", "sta.user_id", "=", "u.id")
            ->join("alunos_turmas as alunt", function($join) {
                $join->on("alunt.turma_id", "=", "t.id")
                    ->on("alunt.aluno_id", "=", "u.id");
            })
            ->where([
                ['td.professor_id', '=', $profid],
                ['t.ano_id', '=', $turmaid],
                ['sta.correct', '=', 1],
                ['u.id', '=', $alunoid]
            ])
            ->groupBy("u.name");

        return $sql;
    }


}
?>
