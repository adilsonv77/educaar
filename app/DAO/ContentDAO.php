<?php

namespace App\DAO;

use Illuminate\Support\Facades\DB;

class ContentDAO
{

    public static function buscarContentsDoProf($profid, $anoletivoid)
    {

        // select que pegue quais são as disciplinas e turmas do usuário atual
        // usar essas duas informações para buscar os conteúdos
        /*
        select distinct contents.* from turmas_disciplinas 
            join turmas on turmas_disciplinas.turma_id = turmas.id
            join contents on turmas.turma_modelo_id = contents.turma_id and
                            turmas_disciplinas.disciplina_id = contents.disciplina_id
            where professor_id = 4 and ano_id = 5
        */


        $sql = DB::table('turmas_disciplinas')

            ->join('turmas', 'turmas_disciplinas.turma_id', '=', 'turmas.id')
            ->join('contents', function ($join) {
                $join->on('turmas.turma_modelo_id', '=', 'contents.turma_id');
                $join->on('turmas_disciplinas.disciplina_id', '=', 'contents.disciplina_id');
            })
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            ->join('turmas_modelos', 'turmas_modelos.id', '=', 'contents.turma_id')
            ->where('professor_id', '=', $profid)
            ->where("ano_id", "=", $anoletivoid)
            ->distinct();
        /*
        if ($usarprojecao)
            $sql = $sql->select('contents.*');
        */
        return $sql;
    }
    //Não testado e não utilizado
    public static function buscarTodosConteudos($turma)
    {
        $sql = DB::table('contents')->where('turma_id', '=', $turma);
        return $sql;
    }

    public static function buscarQuestoesDosConteudos($contents)
    {
        /*
select a.content_id, q.id, q.question from activities a
  join questions q on a.id = q.activity_id
  where a.content_id in (1, 10, 20)
  */
        $sql = DB::table('activities as a')
            ->select('a.content_id', 'q.id', 'q.question')
            ->join('questions as q', 'a.id', '=', 'q.activity_id')
            ->whereIn('a.content_id', $contents);

        return $sql;
    }

    public static function buscarTurmasDoContentsDoProf($profid, $anoletivoid, $contentid)
    {


        $sql = DB::table('turmas as t')
            ->select('t.id as id', 't.nome as nome')
            ->join('turmas_disciplinas as td', 'td.turma_id', '=', 't.id')
            ->join('turmas_modelos as tm', 't.turma_modelo_id', '=', 'tm.id')
            ->join('contents as c', 'c.turma_id', '=', 'tm.id')
            ->join('activities as a', 'a.content_id', '=', 'c.id')
            ->where([
                ['td.professor_id', '=', $profid],
                ['t.ano_id', '=', $anoletivoid],
                ['c.id', '=', $contentid]
            ])
            ->distinct();

        return $sql;

    }

    public static function buscarConteudosDeveloper($devid)
    {
        $contents = DB::table('contents')
            ->join('disciplinas', 'contents.disciplina_id', '=', 'disciplinas.id')
            ->join('turmas_modelos', 'contents.turma_id', '=', 'turmas_modelos.id')
            ->join('content_developer', 'content_developer.content_id', '=', 'contents.id')
            ->where('content_developer.developer_id', $devid);

        return $contents;

    }
    public static function buscarQuestoesPorConteudo($content_id, $turma_id)
    {
        return DB::table('questions as q')
            ->join('activities as a', 'q.activity_id', '=', 'a.id')
            ->join('contents as c', 'a.content_id', '=', 'c.id')
            ->leftJoin('student_answers as sa', 'q.id', '=', 'sa.question_id') 
            ->where('a.content_id', $content_id)
            ->where('c.turma_id', $turma_id) 
            ->select('q.*', 'sa.correct')  
            ->get();
    }



}