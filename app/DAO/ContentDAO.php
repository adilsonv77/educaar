<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContentDAO {

    public static function buscarContentsDoProf($profid, $anoletivoid) {

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
            
            ->join('turmas','turmas_disciplinas.turma_id','=','turmas.id')
            ->join('contents', function($join) {
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

    public static function buscarTurmasDoContentsDoProf($profid, $anoletivoid, $contentid) {

        $sql = DB::table('turmas as t')
            ->select('t.id as id','t.nome as nome')
            ->join('turmas_disciplinas as td','td.turma_id', '=', 't.id')
            ->join('turmas_modelos as tm', 't.turma_modelo_id','=','tm.id')
            ->join('contents as c', 'c.turma_id', '=', 'tm.id')
            ->join('activities as a', 'a.content_id', '=', 'c.id')
            ->where([
                ['td.professor_id','=', $profid],
                ['t.ano_id','=', $anoletivoid],
                ['c.id', '=', $contentid]
            ])
            ->distinct();

        return $sql;

    }

    public static function buscarConteudosDeveloper($devid) {
        $contents = DB::table('contents')
            ->join('disciplinas', 'contents.disciplina_id', '=', 'disciplinas.id')
            ->join('turmas_modelos', 'contents.turma_id', '=', 'turmas_modelos.id')
            ->join('content_developer', 'content_developer.content_id', '=', 'contents.id')
            ->where('content_developer.developer_id', $devid);

        return $contents;

    }

}

?>