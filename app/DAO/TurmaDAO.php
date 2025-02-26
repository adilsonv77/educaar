<?php

namespace App\DAO;


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
            ->select("turmas.id", "turmas.nome", "turmas.turma_modelo_id")
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

    public static function resultadosCorretosAlunos($turmaid, $alunoid) {
        
        /*select u.name, COUNT(q.id) as qntCorretas from turmas_disciplinas as td
        join turmas as t on td.turma_id = t.id
        join contents as c on t.turma_modelo_id = c.turma_modelo_id and
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
                $join->on("t.turma_modelo_id", "=", "c.turma_modelo_id")
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
                ['t.id', '=', $turmaid],
                ['sta.correct', '=', 1],
                ['u.id', '=', $alunoid]
            ])
            ->groupBy("u.name");
       // dd($sql->toSql());

        return $sql;
    }

    public static function resultadosIncorretosAlunos($turmaid, $alunoid) {
        $sql = DB::table('turmas_disciplinas as td')
            ->select("u.name", DB::raw("COUNT(q.id) as qntIncorretas"))
            ->join("turmas as t", "td.turma_id", "=", "t.id")
            ->join("contents as c", function($join) {
                $join->on("t.turma_modelo_id", "=", "c.turma_modelo_id")
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
                ['t.id', '=', $turmaid],
                ['sta.correct', '=', 0],
                ['u.id', '=', $alunoid]
            ])
            ->groupBy("u.name");

        return $sql;
    }

    public static function resultadosQuestoesNaoFeitas($turmaid) {
        /*
          select COUNT(q.id) as qntQuestoes from turmas_disciplinas as td
            join turmas as t on td.turma_id = t.id
            join contents as c on t.turma_modelo_id = c.turma_modelo_id and
                                td.disciplina_id = c.disciplina_id
            join activities as a on a.content_id= c.id
            JOIN questions as q on q.activity_id= a.id
            where td.professor_id = 4 and t.ano_id = 4 
         */

        $sql = DB::table('turmas_disciplinas as td')
            ->select(DB::raw("COUNT(q.id) as qntQuestoes"))
            ->join("turmas as t", "td.turma_id", "=", "t.id")
            ->join("contents as c", function($join) {
                $join->on("t.turma_modelo_id", "=", "c.turma_modelo_id")
                    ->on("td.disciplina_id", "=", "c.disciplina_id");
            })
            ->join("activities as a", "a.content_id", "=", "c.id")
            ->join("questions as q", "q.activity_id", "=", "a.id")
            ->where([
                ['t.id', '=', $turmaid]
            ]);

            return $sql;
    }

    public static function buscarQuestoesCorretas($turmaid, $alunoid) {
        $sql = DB::table('turmas_disciplinas as td')
            ->select("a.name as activity_name", "c.name as content_name", "alternative_answered", "question")
            ->join("turmas as t", "td.turma_id", "=", "t.id")
            ->join("contents as c", function($join) {
                $join->on("t.turma_modelo_id", "=", "c.turma_modelo_id")
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
               // ['td.professor_id', '=', $profid],
                ['t.id', '=', $turmaid],
                ['sta.correct', '=', 1],
                ['u.id', '=', $alunoid]
            ]);

        return $sql;
    }
    public static function buscarQuestoesIncorretas($turmaid, $alunoid) {
        $sql = DB::table('turmas_disciplinas as td')
            ->select("a.name as activity_name", "c.name as content_name", "alternative_answered", "question")
            ->join("turmas as t", "td.turma_id", "=", "t.id")
            ->join("contents as c", function($join) {
                $join->on("t.turma_modelo_id", "=", "c.turma_modelo_id")
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
              //  ['td.professor_id', '=', $profid],
                ['t.id', '=', $turmaid],
                ['sta.correct', '=', 0],
                ['u.id', '=', $alunoid]
            ]);
        return $sql;
    }


    public static function buscarQuestoesNaoRespondidas($turmaid, $alunoid) {
        /*
        SELECT a.name as activity_name, c.name as content_name, alternative_answered, question FROM turmas_disciplinas td 
            join turmas t on t.id = td.turma_id
            join contents c on c.turma_modelo_id = t.turma_modelo_id and c.disciplina_id = td.disciplina_id
            join activities a on a.content_id = c.id
            join questions q on q.activity_id = a.id
            
            left outer join (SELECT * FROM student_answers sa WHERE user_id = 255) saq on q.id = saq.question_id and q.activity_id = saq.activity_id

            where td.turma_id = 16 and  saq.alternative_answered is null;
            */

        $sql_s_a = DB::table('student_answers as sa')
            ->where('user_id', '=', $alunoid);
        
        $sql = DB::table('turmas_disciplinas as td')
        ->select("a.name as activity_name", "c.name as content_name", "alternative_answered", "question")
        ->join("turmas as t", "td.turma_id", "=", "t.id")
        ->join("contents as c", function($join) {
            $join->on("t.turma_modelo_id", "=", "c.turma_modelo_id")
                ->on("td.disciplina_id", "=", "c.disciplina_id");
        })
        ->join("activities as a", "a.content_id", "=", "c.id")
        ->join("questions as q", "q.activity_id", "=", "a.id")
        ->leftJoinSub($sql_s_a, 'saq', function ($join) {
            $join->on('q.id', '=', 'saq.question_id')
                 ->on('q.activity_id', '=', 'saq.activity_id');
        })
        ->where('t.id', '=', $turmaid)
        ->whereNull('saq.alternative_answered')
        ->orderBy("c.id")
        ->orderBy("a.id");

        // dd($sql->toSql());

        return $sql;        
    }

    public static function buscarQuestoesNaoRespondidasTodosAlunos($turmaid) {
        /*
        select user_name, a.name as activity_name, c.name as content_name FROM 
        (select u.name as user_name, aluno_id, turma_id from alunos_turmas join users u on u.id = aluno_id where turma_id = 16) at
        join contents c on c.turma_modelo_id = at.turma_id
        join activities a on a.content_id = c.id
        left outer join student_answers sa on sa.activity_id = a.id and sa.user_id = aluno_id
        where sa.id is null
        order by c.id
        */

        $sql_at = DB::table('alunos_turmas as at')
            ->select("u.name as user_name", "aluno_id", "turma_id")
            ->join("users as u", "u.id", "=", "aluno_id")
            ->where("turma_id", "=",  $turmaid);

        $sql = DB::table("contents as c")
            ->select("user_name", "a.name as activity_name", "c.name as content_name")
            ->joinSub($sql_at, "at", "c.turma_modelo_id", "=", "at.turma_id")
            ->join("activities as a", "a.content_id", "=", "c.id")
            ->leftJoin("student_answers as sa", function ($join) {
                $join->on('sa.activity_id', '=', 'a.id')
                     ->on('sa.user_id', '=', 'aluno_id');
            })
            ->whereNull("sa.id")
            ->orderBy("c.id")
            ->orderBy("a.id")
            ->orderBy("user_name");

      // dd($sql->toSql());
 
       return $sql;   
    }
}
?>
