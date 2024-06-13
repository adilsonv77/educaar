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



}
?>
