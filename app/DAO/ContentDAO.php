<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContentDAO {

    public static function buscarContentsDoProf($profid, $usarprojecao) {

        // select que pegue quais são as disciplinas e turmas do usuário atual
        // usar essas duas informações para buscar os conteúdos
        /*
        select distinct contents.* from turmas_disciplinas 
            join turmas on turmas_disciplinas.turma_id = turmas.id
            join contents on turmas.turma_modelo_id = contents.turma_id and
                            turmas_disciplinas.disciplina_id = contents.disciplina_id
            where professor_id = 4 and ano_id = 1
        */
        $anoletivoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();        

        $sql = DB::table('turmas_disciplinas')
            
            ->join('turmas','turmas_disciplinas.turma_id','=','turmas.id')
            ->join('contents', function($join) {
                        $join->on('turmas.turma_modelo_id', '=', 'contents.turma_id');
                        $join->on('turmas_disciplinas.disciplina_id', '=', 'contents.disciplina_id');
                    })
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            ->join('turmas_modelos', 'turmas_modelos.id', '=', 'contents.turma_id')
            ->where('professor_id', '=', $profid)
            ->where("ano_id", "=", $anoletivoAtual->id)
            ->distinct();
        /*
        if ($usarprojecao)
            $sql = $sql->select('contents.*');
        */
        return $sql;
    }
    
}

?>