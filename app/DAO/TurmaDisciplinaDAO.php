<?php

namespace App\DAO;


use Illuminate\Support\Facades\DB;

class TurmaDisciplinaDAO
{
    public static function buscarTurmasProf($profid, $anoletivoid){
        $sql = DB::table('turmas_disciplinas')
        ->select("turmas.id as t_id", "turmas.nome", "disciplinas.id as d_id", "disciplinas.name")
        ->join("turmas", "turmas.id", "=", "turmas_disciplinas.turma_id")
        ->join("disciplinas", "disciplinas.id", "=", "turmas_disciplinas.disciplina_id")
        ->where([
            ['turmas_disciplinas.professor_id', '=', $profid],
            ['turmas.ano_id', '=', $anoletivoid]
        ])
        ->distinct();
    return $sql;

    }
}
?>