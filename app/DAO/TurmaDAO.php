<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TurmaDAO
{

    public static function buscarTurmasProf($profid, $anoletivoid)
    {
         $sql = DB::table('turmas_disciplinas')
        ->join("alunos_turmas as ta", "ta.turma_id", "=", "turmas_disciplinas.turma_id")
        ->join("turmas", "turmas.id", "=", "ta.turma_id")
            ->where([
                ['turmas_disciplinas.professor_id', '=', $profid],
                ['turmas.ano_id', '=', $anoletivoid]
            ]);

        return $sql;
    }



}
?>
