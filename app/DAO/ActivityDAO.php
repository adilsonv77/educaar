<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityDAO
{

    public static function buscarActivitiesDoProf($profid, $usarprojecao)
    {
        /*
        SELECT distinct activities.* FROM activities
            join contents on activities.content_id = contents.id
            join turmas_disciplinas on contents.disciplina_id = turmas_disciplinas.disciplina_id
            join turmas on turmas_disciplinas.turma_id = turmas.id
            join anos_letivos on turmas.ano_id = anos_letivos.id
            where anos_letivos.bool_atual = 1 and turmas_disciplinas.professor_id = 5
    */
    /* mudar para:
       select distinct activities.* from turmas_disciplinas 
                    join turmas on turmas_disciplinas.turma_id = turmas.id
                    join contents on turmas.turma_modelo_id = contents.turma_id and
                                    turmas_disciplinas.disciplina_id = contents.disciplina_id
                join activities on activities.content_id = contents.id 
                    where turmas_disciplinas.professor_id = 5/209 and ano_id = 4
    */

    $anoletivoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
        ->where('bool_atual', 1)->first();        

    $sql = DB::table('turmas_disciplinas')
            
            ->join('turmas','turmas_disciplinas.turma_id','=','turmas.id')
            ->join('contents', function($join) {
                        $join->on('turmas.turma_modelo_id', '=', 'contents.turma_id');
                        $join->on('turmas_disciplinas.disciplina_id', '=', 'contents.disciplina_id');
                    })
            ->join('activities', 'activities.content_id', '=', 'contents.id')
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            //->join('turmas_modelos', 'turmas_modelos.id', '=', 'contents.turma_id')
            ->where('turmas_disciplinas.professor_id', '=', $profid)
            ->where("ano_id", "=", $anoletivoAtual->id)
            ->distinct();

    /*    
    $sql = DB::table("activities")
            ->join("contents", "activities.content_id", "=", "contents.id")
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            ->join("turmas_disciplinas", "contents.disciplina_id", "=", "turmas_disciplinas.disciplina_id")
            ->join("turmas", "turmas_disciplinas.turma_id", "=", "turmas.id")
            ->join("anos_letivos", "turmas.ano_id", "=", "anos_letivos.id")
            
            ->where('turmas_disciplinas.professor_id', $profid)
            ->where("anos_letivos.bool_atual", 1);
*/

            /*
        if ($usarprojecao)
            $sql = $sql->select('activities.*');
*/
        //dd($sql);
        return $sql;
    }
}

/*



->join("turmas_disciplinas",  function ($join) {
    $join->on('turmas.id', '=', 'turmas_disciplinas.turma_id')
        ->on('contents.disciplina_id', '=', 'turmas_disciplinas.disciplina_id');
})
*/