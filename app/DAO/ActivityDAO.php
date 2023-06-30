<?php

namespace App\DAO;

use App\Models\Question;

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
            where anos_letivos.bool_atual = 1 and turmas_disciplinas.professor_id = 1
    */
        $sql = DB::table("activities")
            ->join("contents", "activities.content_id", "=", "contents.id")
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            ->join("turmas_disciplinas", "contents.disciplina_id", "=", "turmas_disciplinas.disciplina_id")
            ->join("turmas", "turmas_disciplinas.turma_id", "=", "turmas.id")
            ->join("anos_letivos", "turmas.ano_id", "=", "anos_letivos.id")
            
            ->where('turmas_disciplinas.professor_id', $profid)
            ->where("anos_letivos.bool_atual", 1);


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